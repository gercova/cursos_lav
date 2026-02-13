<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffValidate;
use App\Http\Requests\UserValidate;
use App\Models\CompanyPolicy;
use App\Models\Course;
use App\Models\CoursePromotionCode;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class BusinessManagementController extends Controller {

    public function __construct(){
        $this->middleware(['auth:sanctum', 'business', 'prevent.back']);
    }

    // public function index(Request $request): View {
    //     $codeE = User::where('id', Auth::id())->first();
    //     $query = User::withCount(['enrollments', 'courses', 'certificates', 'examAttempts'])
    //         ->where('users.company_code', $codeE->company_code)
    //         ->where('users.id', '!=', Auth::id())
    //         ->orderBy('created_at', 'desc');

    //     // Filtros
    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('names', 'like', "%{$search}%")
    //                 ->orWhere('email', 'like', "%{$search}%")
    //                 ->orWhere('dni', 'like', "%{$search}%")
    //                 ->orWhere('phone', 'like', "%{$search}%");
    //         });
    //     }

    //     if ($request->filled('status')) {
    //         $query->where('is_active', $request->status);
    //     }

    //     $users = $query->paginate(10);

    //     $stats = [
    //         'total'         => User::where('users.company_code', $codeE->company_code)->where('users.id', '!=', Auth::id())->count(),
    //         'students'      => User::where('users.company_code', $codeE->company_code)->where('users.id', '!=', Auth::id())->count(),
    //     ];

    //     return view('business.index', compact('users', 'stats'));
    // }

    public function index(Request $request): View {
        $codeE = User::where('id', Auth::id())->first();
        
        // Obtener límite de usuarios
        $countUser = User::where('company_code', $codeE->company_code)->count();
        $limitUser = CompanyPolicy::where('user_id', Auth::id())->first();
        $availableSlots = ($limitUser->quantity ?? 0) + 1 - $countUser;
        
        $query = User::withCount(['enrollments', 'courses', 'certificates', 'examAttempts'])
            ->where('users.company_code', $codeE->company_code)
            ->where('users.id', '!=', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('names', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $users = $query->paginate(10);

        $stats = [
            'total'         => User::where('users.company_code', $codeE->company_code)->where('users.id', '!=', Auth::id())->count(),
            'students'      => User::where('users.company_code', $codeE->company_code)->where('users.id', '!=', Auth::id())->count(),
            'available'     => $availableSlots,
            'limit'         => ($limitUser->quantity ?? 0) + 1,
        ];

        return view('business.index', compact('users', 'stats'));
    }

    public function profile (User $user): View {
        $originalArray = [
            ['code' => '+51', 'country' => '+51 - Perú'],
            ['code' => '+54', 'country' => '+54 - Argentina'],
            ['code' => '+56', 'country' => '+56 - Chile'],
            ['code' => '+591', 'country' => '+591 - Bolivia'],
            ['code' => '+593', 'country' => '+593 - Ecuador'],
            ['code' => '+598', 'country' => '+598 - Uruguay'],
        ];

        $codeCountries = collect($originalArray)->map(fn ($item) => (object) $item);
        return view('business.profile', compact('user', 'codeCountries'));
    }

    public function storeProfileData(UserValidate $request){
        $validated = $request->validated();

        // Determinar si es creación por la presencia de ID en el request
        if (!$request->has('id') || empty($request->id)) {
            $proccessData = [
                'password'          => Hash::make('P4$$w0rd#.'),
                'email_verified_at' => now(),
            ];

            $data = array_merge($validated, $proccessData);
            // Creación - usar email como identificador único
            $user = User::updateOrCreate(
                ['id' => $request->input('id')],
                $data
            );
        } else {
            // Actualización - usar ID del request
            $user = User::where('id', $request->id)->first();
            $user->update($validated);
        }

        $message = $request->has('id') ? 'actualizado' : 'creado';
        return redirect()->route('admin.users.index')->with('success', "Usuario {$message} exitosamente.");
    }

    public function storeStaff(StaffValidate $request): JsonResponse {
        $codeE      = User::where('id', Auth::id())->first();
    
        $countUser  = User::where('company_code', $codeE->company_code)->count();
        $limitUser  = CompanyPolicy::where('user_id', Auth::id())->get();

        if($countUser == (int) $limitUser->quantity + 1){
            return response()->json([
                'status' 	=> false,
                'messages' 	=> 'Ya no puedes registrar más usuarios, solicita cambio de plan al administrador',
            ]);
        } else {
            $validated  = $request->validated();
            $id         = $request->input('user_id');

            $data       = array_merge($validated, [
                'company_code' => $codeE->company_code,
            ]);
            
            DB::beginTransaction();
            try {
                $result = User::updateOrCreate(['id' => $id], $data);

                if ($request->has('role_id')) {
                    // Eliminar todos los roles actuales (asumiendo que un usuario solo tiene un rol)
                    DB::table('model_has_roles')->where('model_id', $result->id)->delete();
                    // Asignar el nuevo rol
                    DB::table('model_has_roles')->insert([
                        'role_id'       => $request->input('role_id'),
                        'model_type'    => 'App\Models\User',
                        'model_id'      => $result->id
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status' 	=> true,
                    'messages' 	=> empty($id) ? 'Datos del usuario actualizado exitosamente' : 'Se ha añadido un nuevo usuario',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' 	=> false,
                    'messages' 	=> $e->getMessage(),
                ], 500);
            }
        }
    }

    /**
     * Vista para matricular usuarios con código
     */
    public function enrollUsers(): View {
        // $codeE = User::where('id', Auth::id())->first();
        
        // Obtener todos los colaboradores de la empresa
        $collaborators = User::where('company_code', auth()->user()->company_code)
            ->where('is_active', true)
            ->where('id', '!=', Auth::id())
            ->orderBy('names')
            ->get();
            
        // Obtener los cursos activos que tienen precio de promoción
        $courses = Course::where('is_active', true)
            ->with('instructor')
            ->orderBy('title')
            ->get();
            
        return view('business.enroll-users', compact('collaborators', 'courses'));
    }

    /**
     * Matricular usuario usando su código de promoción
     */
    public function enrollWithCode(Request $request): JsonResponse {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $codeE      = User::where('id', Auth::id())->first();
        $student    = User::find($request->user_id);
        $course     = Course::find($request->course_id);

        // Verificar que el estudiante pertenezca a la empresa
        if ($student->company_code !== $codeE->company_code) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no pertenece a tu empresa'
            ], 403);
        }

        // Verificar que el curso tenga precio de promoción
        if (!$course->promotion_price) {
            return response()->json([
                'success' => false,
                'message' => 'Este curso no tiene precio de promoción activo'
            ], 400);
        }

        // Verificar que el estudiante tenga código de promoción
        if (!$student->code) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no tiene un código de promoción asignado'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Verificar si ya está matriculado
            $existingEnrollment = Enrollment::where('user_id', $student->id)
                ->where('course_id', $course->id)
                ->first();

            if ($existingEnrollment) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario ya está matriculado en este curso'
                ], 400);
            }

            // Crear o obtener el código de promoción del curso
            $coursePromotionCode = CoursePromotionCode::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'code'      => $student->code
                ],
                [
                    'discount_percentage'   => 20, // Descuento por defecto
                    'is_active'             => true,
                    'max_uses'              => 1,
                    'used_count'            => 0
                ]
            );

            // Crear la matrícula
            $enrollment = Enrollment::create([
                'user_id'       => $student->id,
                'course_id'     => $course->id,
                'enrolled_at'   => now(),
                'progress'      => 0,
                'status'        => 'active',
            ]);

            // Registrar el uso del código de promoción
            $coursePromotionCode->increment('used_count');

            // Si el estudiante tiene código, incrementar sus ventas (opcional)
            $student->increment('courses_sold_count');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Usuario {$student->names} matriculado exitosamente en el curso {$course->title}",
                'data' => [
                    'enrollment_id' => $enrollment->id,
                    'student' => $student->names,
                    'course' => $course->title,
                    'code_used' => $student->code
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al matricular: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Matriculación masiva de usuarios en un curso
     */
    public function bulkEnroll(Request $request): JsonResponse {
        $request->validate([
            'user_ids'      => 'required|array|min:1',
            'user_ids.*'    => 'exists:users,id',
            'course_id'     => 'required|exists:courses,id',
        ]);

        $codeE  = User::where('id', Auth::id())->first();
        $course = Course::find($request->course_id);

        // Verificar que el curso tenga precio de promoción
        if (!$course->promotion_price) {
            return response()->json([
                'success' => false,
                'message' => 'Este curso no tiene precio de promoción activo'
            ], 400);
        }

        $results = [
            'success' => [],
            'failed' => []
        ];

        DB::beginTransaction();
        try {
            foreach ($request->user_ids as $userId) {
                $student = User::find($userId);

                // Verificar que el estudiante pertenezca a la empresa
                if ($student->company_code !== $codeE->company_code) {
                    $results['failed'][] = [
                        'user'      => $student->names,
                        'reason'    => 'No pertenece a tu empresa'
                    ];
                    continue;
                }

                // Verificar que tenga código
                if (!$student->code) {
                    $results['failed'][] = [
                        'user'      => $student->names,
                        'reason'    => 'No tiene código de promoción'
                    ];
                    continue;
                }

                // Verificar matrícula existente
                $existingEnrollment = Enrollment::where('user_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists();

                if ($existingEnrollment) {
                    $results['failed'][] = [
                        'user'      => $student->names,
                        'reason'    => 'Ya está matriculado'
                    ];
                    continue;
                }

                // Crear o obtener el código de promoción
                $coursePromotionCode = CoursePromotionCode::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'code'      => $student->code
                    ],
                    [
                        'discount_percentage' => 20,
                        'is_active'     => true,
                        'max_uses'      => 1,
                        'used_count'    => 0
                    ]
                );

                // Crear matrícula
                Enrollment::create([
                    'user_id'       => $student->id,
                    'course_id'     => $course->id,
                    'enrolled_at'   => now(),
                    'progress'      => 0,
                    'status'        => 'active',
                ]);

                $coursePromotionCode->increment('used_count');
                $student->increment('courses_sold_count');

                $results['success'][] = $student->names;
            }

            DB::commit();

            $message = "Se matricularon " . count($results['success']) . " usuarios exitosamente";
            if (count($results['failed']) > 0) {
                $message .= ". " . count($results['failed']) . " usuarios no pudieron ser matriculados.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $results
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en la matriculación masiva: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener usuarios sin código de promoción
     */
    public function getUsersWithoutCode(): JsonResponse {
        $codeE = User::where('id', Auth::id())->first();
        $users = User::where('company_code', $codeE->company_code)
            ->where('id', '!=', Auth::id())
            ->whereNull('code')
            ->select('id', 'names', 'email', 'dni')
            ->orderBy('names')
            ->get();

        return response()->json([
            'success'   => true,
            'data'      => $users
        ]);
    }

    /**
     * Obtener matriculaciones recientes de la empresa
     */
    public function getRecentEnrollments(): JsonResponse {
        $codeE = User::where('id', Auth::id())->first();
        
        $enrollments = Enrollment::whereHas('user', function($query) use ($codeE) {
                $query->where('company_code', $codeE->company_code);
            })
            ->with(['user:id,names,email', 'course:id,title'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success'   => true,
            'data'      => $enrollments
        ]);
    }

    /**
     * Mega matrícula - Todos los usuarios en todos los cursos
     */
    public function superBulkEnroll(Request $request): JsonResponse {
        $codeE      = User::where('id', Auth::id())->first();
        // Obtener todos los colaboradores con código de promoción
        $students   = User::where('company_code', $codeE->company_code)
            ->where('id', '!=', Auth::id())
            ->whereNotNull('code')
            ->get();
        
        // Obtener todos los cursos activos con precio de promoción
        $courses    = Course::where('is_active', true)
            ->whereNotNull('promotion_price')
            ->get();

        if ($students->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay usuarios con código de promoción para matricular'
            ], 400);
        }

        if ($courses->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay cursos con precio de promoción activo'
            ], 400);
        }

        $results = [
            'success'           => [],
            'failed'            => [],
            'total_processed'   => 0,
            'total_enrollments' => $students->count() * $courses->count()
        ];

        DB::beginTransaction();
        try {
            foreach ($students as $student) {
                foreach ($courses as $course) {
                    // Verificar si ya está matriculado
                    $existingEnrollment = Enrollment::where('user_id', $student->id)
                        ->where('course_id', $course->id)
                        ->exists();

                    if ($existingEnrollment) {
                        $results['failed'][] = [
                            'user'      => $student->names,
                            'course'    => $course->title,
                            'reason'    => 'Ya está matriculado'
                        ];
                        continue;
                    }

                    // Crear o obtener el código de promoción
                    $coursePromotionCode = CoursePromotionCode::firstOrCreate(
                        [
                            'course_id' => $course->id,
                            'code'      => $student->code
                        ],
                        [
                            'discount_percentage'   => 20,
                            'is_active'             => true,
                            'max_uses'              => 1,
                            'used_count'            => 0
                        ]
                    );

                    // Crear matrícula
                    Enrollment::create([
                        'user_id'       => $student->id,
                        'course_id'     => $course->id,
                        'enrolled_at'   => now(),
                        'progress'      => 0,
                        'status'        => 'active',
                    ]);

                    $coursePromotionCode->increment('used_count');
                    $student->increment('courses_sold_count');

                    $results['success'][] = [
                        'user'      => $student->names,
                        'course'    => $course->title
                    ];
                    
                    $results['total_processed']++;
                }
            }

            DB::commit();

            $message = "🚀 Mega Matrícula completada: {$results['total_processed']} inscripciones realizadas exitosamente";
            
            if (count($results['failed']) > 0) {
                $message .= ". " . count($results['failed']) . " inscripciones omitidas (ya estaban matriculados).";
            }

            return response()->json([
                'success'   => true,
                'message'   => $message,
                'data'      => [
                    'success'   => $results['success'],
                    'failed'    => $results['failed'],
                    'summary'   => "Total de usuarios: {$students->count()}<br>
                                Total de cursos: {$courses->count()}<br>
                                Matrículas realizadas: <span class='text-green-600 font-bold'>{$results['total_processed']}</span><br>
                                Matrículas omitidas: <span class='text-amber-600 font-bold'>" . count($results['failed']) . "</span>",
                    'total_processed' => $results['total_processed']
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error en Mega Matrícula: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user): JsonResponse {
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el único administrador.'
            ], 400);
        }

        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente.'
        ]);
    }
}
