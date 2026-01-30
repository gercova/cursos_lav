<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExamQuestionAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'admin', 'prevent.back']);
    }

    public function store(Request $request, Exam $exam): JsonResponse {
        $request->validate([
            'question'          => 'required|string|max:1000',
            'type'              => 'required|in:multiple_choice,true_false',
            'points'            => 'required|integer|min:1|max:100',
            'options'           => 'required_if:type,multiple_choice',
            'correct_answer'    => 'required',
        ]);

        // Procesar opciones para multiple choice
        $options = null;
        if ($request->type === 'multiple_choice') {
            // Si options ya es un array, convertirlo a JSON
            if (is_array($request->options)) {
                // Filtrar opciones vacías
                $filteredOptions = array_filter($request->options, function($option) {
                    return !empty(trim($option));
                });

                if (count($filteredOptions) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe proporcionar al menos 2 opciones válidas'
                    ], 422);
                }

                $options = json_encode($filteredOptions);
            } else {
                // Si es string JSON, validarlo
                $optionsArray = json_decode($request->options, true);
                if (!is_array($optionsArray) || count(array_filter($optionsArray)) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe proporcionar al menos 2 opciones válidas'
                    ], 422);
                }
                $options = $request->options;
            }
        }

        $question = $exam->questions()->create([
            'question'          => $request->question,
            'type'              => $request->type,
            'points'            => $request->points,
            'options'           => $options,
            'correct_answer'    => $request->correct_answer,
            'order'             => $exam->questions()->count() + 1,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Pregunta creada exitosamente',
            'question'  => $question
        ]);
    }

    public function edit(ExamQuestion $question): JsonResponse {
        // Asegurar que las opciones sean un array si existen
        $questionData = $question->toArray();
        if ($question->options && is_string($question->options)) {
            $questionData['options'] = json_decode($question->options, true);
        }

        return response()->json($questionData);
    }

    public function update(Request $request, ExamQuestion $question): JsonResponse {
        $request->validate([
            'question'          => 'required|string|max:1000',
            'type'              => 'required|in:multiple_choice,true_false',
            'points'            => 'required|integer|min:1|max:100',
            'options'           => 'required_if:type,multiple_choice',
            'correct_answer'    => 'required',
        ]);

        // Procesar opciones para multiple choice
        $options = null;
        if ($request->type === 'multiple_choice') {
            // Si options ya es un array, convertirlo a JSON
            if (is_array($request->options)) {
                // Filtrar opciones vacías
                $filteredOptions = array_filter($request->options, function($option) {
                    return !empty(trim($option));
                });

                if (count($filteredOptions) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe proporcionar al menos 2 opciones válidas'
                    ], 422);
                }

                $options = json_encode($filteredOptions);
            } else {
                // Si es string JSON, validarlo
                $optionsArray = json_decode($request->options, true);
                if (!is_array($optionsArray) || count(array_filter($optionsArray)) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debe proporcionar al menos 2 opciones válidas'
                    ], 422);
                }
                $options = $request->options;
            }
        }

        $question->update([
            'question'  => $request->question,
            'type'      => $request->type,
            'points'    => $request->points,
            'options'   => $options,
            'correct_answer' => $request->correct_answer,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Pregunta actualizada exitosamente',
            'question'  => $question
        ]);
    }

    public function destroy(ExamQuestion $question): JsonResponse {
        $question->delete();

        // Reordenar preguntas restantes
        $exam   = $question->exam;
        $order  = 1;
        foreach ($exam->questions()->orderBy('order')->get() as $q) {
            $q->update(['order' => $order++]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pregunta eliminada exitosamente'
        ]);
    }

    public function move(Request $request, ExamQuestion $question): JsonResponse {
        $direction      = $request->input('direction');
        $currentOrder   = $question->order;

        if ($direction === 'up' && $currentOrder > 1) {
            $previousQuestion = $question->exam->questions()
                ->where('order', $currentOrder - 1)
                ->first();

            if ($previousQuestion) {
                $question->update(['order' => $currentOrder - 1]);
                $previousQuestion->update(['order' => $currentOrder]);
            }
        } elseif ($direction === 'down') {
            $nextQuestion = $question->exam->questions()
                ->where('order', $currentOrder + 1)
                ->first();

            if ($nextQuestion) {
                $question->update(['order' => $currentOrder + 1]);
                $nextQuestion->update(['order' => $currentOrder]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Pregunta movida exitosamente'
        ]);
    }

    public function import(Request $request, Exam $exam): JsonResponse {
        $request->validate([
            'file' => 'required|file|mimes:json|max:2048', // Máximo 2MB
        ]);

        $file = $request->file('file');

        try {
            $content = $file->get();
            $data = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error al decodificar JSON: ' . json_last_error_msg());
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo JSON no es válido. Verifica su formato.'
                ], 422);
            }

            if (!isset($data['questions']) || !is_array($data['questions'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo JSON no contiene un array válido de preguntas bajo la clave "questions".'
                ], 422);
            }

            $importedCount = 0;
            $errors = [];

            DB::beginTransaction(); // Iniciar transacción para integridad

            $order = $exam->questions()->max('order') + 1; // Comenzar a ordenar después de las existentes

            foreach ($data['questions'] as $index => $qData) {
                try {
                    // Validación de campos mínimos
                    if (!isset($qData['question']) || !isset($qData['type']) || !isset($qData['correct_answer'])) {
                        $errors[] = "La pregunta en la línea " . ($index + 1) . " no tiene los campos 'question', 'type' o 'correct_answer'.";
                        continue;
                    }

                    $question = $qData['question'];
                    $type = $qData['type'];
                    $correctAnswer = $qData['correct_answer'];
                    $points = (int) ($qData['points'] ?? 10); // Valor por defecto
                    $options = $qData['options'] ?? null; // Solo para 'multiple_choice'

                    if ($type === 'multiple_choice') {
                        if (!is_array($options) || count($options) < 2) {
                            $errors[] = "La pregunta en la línea " . ($index + 1) . " (múltiple choice) debe tener al menos 2 opciones.";
                            continue;
                        }
                        // Filtrar opciones vacías y convertir a JSON
                        $filteredOptions = array_values(array_filter($options, fn($o) => trim($o) !== ''));
                        if (count($filteredOptions) < 2) {
                             $errors[] = "La pregunta en la línea " . ($index + 1) . " (múltiple choice) tiene menos de 2 opciones válidas.";
                             continue;
                        }
                        // Asegurar que la respuesta correcta sea un índice numérico válido
                        if (!is_numeric($correctAnswer) || $correctAnswer < 0 || $correctAnswer >= count($filteredOptions)) {
                            $errors[] = "La respuesta correcta para la pregunta en la línea " . ($index + 1) . " está fuera de rango.";
                            continue;
                        }
                        $optionsJson = json_encode($filteredOptions);
                    } elseif ($type === 'true_false') {
                        // Validar que la respuesta sea 'true' o 'false'
                        if (!in_array($correctAnswer, ['true', 'false'])) {
                            $errors[] = "La respuesta correcta para la pregunta Verdadero/Falso en la línea " . ($index + 1) . " debe ser 'true' o 'false'.";
                            continue;
                        }
                        $optionsJson = null; // No se guardan opciones para V/F
                    } else {
                        $errors[] = "Tipo de pregunta desconocido '$type' en la línea " . ($index + 1) . ".";
                        continue;
                    }

                    // Crear la pregunta
                    $exam->questions()->create([
                        'question' => $question,
                        'type' => $type,
                        'points' => $points,
                        'options' => $optionsJson,
                        'correct_answer' => $correctAnswer,
                        'order' => $order++,
                    ]);

                    $importedCount++;
                } catch (\Exception $e) {
                    Log::error("Error al importar pregunta en línea " . ($index + 1) . ": " . $e->getMessage());
                    $errors[] = "Error al procesar la pregunta en la línea " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            DB::commit(); // Confirmar transacción

            $message = "Importación completada. Se importaron $importedCount preguntas.";
            if (!empty($errors)) {
                $message .= " Se encontraron " . count($errors) . " errores.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported_count' => $importedCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir si hay error
            Log::error('Error general en importación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado durante la importación.'
            ], 500);
        }
    }

    /**
     * Reordenar preguntas según un nuevo orden proporcionado.
     */
    public function reorder(Request $request, Exam $exam): JsonResponse {
        $request->validate([
            'question_order'    => 'required|array',
            'question_order.*'  => 'required|integer|exists:exam_questions,id,exam_id,' . $exam->id,
        ]);

        $orderedIds = $request->input('question_order');
        DB::beginTransaction(); // Iniciar transacción para integridad

        try {
            // Asignar nuevo orden basado en la posición en el array
            foreach ($orderedIds as $index => $questionId) {
                ExamQuestion::where('id', $questionId)->where('exam_id', $exam->id)->update(['order' => $index + 1]);
            }

            DB::commit(); // Confirmar transacción
            return response()->json([
                'success' => true,
                'message' => 'Preguntas reordenadas exitosamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir si hay error
            Log::error('Error al reordenar preguntas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al reordenar las preguntas.'
            ], 500);
        }
    }
}
