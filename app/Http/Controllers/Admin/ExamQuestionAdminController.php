<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;

class ExamQuestionAdminController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin']);
    }

    public function store(Request $request, Exam $exam)
    {
        $request->validate([
            'question'  => 'required|string|max:1000',
            'type'      => 'required|in:multiple_choice,true_false',
            'points'    => 'required|integer|min:1|max:100',
            'options'   => 'required_if:type,multiple_choice',
            'correct_answer' => 'required',
        ]);

        // Procesar opciones para multiple choice
        if ($request->type === 'multiple_choice') {
            $options = json_decode($request->options, true);
            if (!is_array($options) || count(array_filter($options)) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar al menos 2 opciones válidas'
                ], 422);
            }
        }

        $question = $exam->questions()->create([
            'question' => $request->question,
            'type' => $request->type,
            'points' => $request->points,
            'options' => $request->type === 'multiple_choice' ? $request->options : null,
            'correct_answer' => $request->correct_answer,
            'order' => $exam->questions()->count() + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pregunta creada exitosamente',
            'question' => $question
        ]);
    }

    public function edit(ExamQuestion $question)
    {
        return response()->json($question);
    }

    public function update(Request $request, ExamQuestion $question)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'type' => 'required|in:multiple_choice,true_false',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required_if:type,multiple_choice',
            'correct_answer' => 'required',
        ]);

        if ($request->type === 'multiple_choice') {
            $options = json_decode($request->options, true);
            if (!is_array($options) || count(array_filter($options)) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar al menos 2 opciones válidas'
                ], 422);
            }
        }

        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'points' => $request->points,
            'options' => $request->type === 'multiple_choice' ? $request->options : null,
            'correct_answer' => $request->correct_answer,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pregunta actualizada exitosamente',
            'question' => $question
        ]);
    }

    public function destroy(ExamQuestion $question)
    {
        $question->delete();

        // Reordenar preguntas restantes
        $exam = $question->exam;
        $order = 1;
        foreach ($exam->questions()->orderBy('order')->get() as $q) {
            $q->update(['order' => $order++]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pregunta eliminada exitosamente'
        ]);
    }

    public function move(Request $request, ExamQuestion $question)
    {
        $direction = $request->input('direction');
        $currentOrder = $question->order;

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
}
