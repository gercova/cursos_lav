<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaffValidate;
use App\Models\CompanyPolicy;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Enterprise;
use App\Models\PackageCourse;
use App\Models\PlanType;
use App\Models\User;
use App\Models\UserCoursePackage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BusinessManagementController extends Controller {

    public function __construct(){
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function index(Request $request): View {
        $enterprise     = Enterprise::first();
        // Saber si un usuario registrado como empresa tiene comprado un paquete
        $hasAnyPackage  = User::find(auth()->id())->studentCourses()->where('courses.type', 'package')->exists();

        if($hasAnyPackage){
            // Obtener límite de usuarios
            $countUser      = User::where('company_code', auth()->user()->company_code)->count();
            $limitUser      = User::find(auth()->id())
                ->studentCourses()
                ->where('courses.type', 'package')
                ->latest()
                ->first(); // Obtener total de asientos que tiene el ultimo paquete comprado en caso de haber comprado más de uno
            
            $availableSlots = ($limitUser->seats_max ?? 0) + 1 - $countUser;
            
            $query = User::withCount(['enrollments', 'courses', 'certificates', 'examAttempts'])
                ->where('users.parent_id', auth()->id())
                ->where('users.id', '!=', auth()->id())
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

            // Obtener total de asientos
            $enrolledPackage = User::find(auth()->id())
                ->studentCourses()
                ->where('courses.type', 'package')
                ->orderByDesc('courses.plan_type_id')
                ->first();

            $stats = [
                'total'         => User::where('users.parent_id', auth()->id())->where('users.id', '!=', Auth::id())->count(),
                'seats_max'     => $enrolledPackage->seats_max,
                'available'     => $availableSlots,
                'limit'         => ($limitUser->quantity ?? 0) + 1,
            ];

            return view('student.company.index', compact('users', 'stats', 'hasAnyPackage'));
        } else {
            return view('student.company.void', compact('enterprise'));
        }
    }

    public function createStaff(User $user): View {
        $hasAnyPackage = User::find(auth()->id())
            ->studentCourses()
            ->where('courses.type', 'package')
            ->exists();
        
        $originalArray = [
            ['code' => '+51', 'country' => '+51 - Perú'],
            ['code' => '+54', 'country' => '+54 - Argentina'],
            ['code' => '+56', 'country' => '+56 - Chile'],
            ['code' => '+591', 'country' => '+591 - Bolivia'],
            ['code' => '+593', 'country' => '+593 - Ecuador'],
            ['code' => '+598', 'country' => '+598 - Uruguay'],
        ];

        $codeCountries = collect($originalArray)->map(fn ($item) => (object) $item);
        return view('student.company.create-staff', compact('hasAnyPackage', 'user', 'codeCountries'));
    }

    // public function storeStaff(StaffValidate $request): JsonResponse {
    //     $countUser  = User::where('parent_id', auth()->id())->count();
    //     $user       = auth()->user();
    //     $limitUser  = $user->studentCourses()->where('courses.type', 'package')->orderByDesc('courses.plan_type_id')->first();

    //     if($countUser == (int) $limitUser->seats_max + 1){
    //         return response()->json([
    //             'status' 	=> false,
    //             'messages' 	=> 'Ya no puedes registrar más usuarios, solicita cambio de plan al administrador',
    //         ]);
    //     } else {
    //         $validated  = $request->validated();
    //         $id         = $request->input('user_id');
    //         $data       = array_merge($validated, [
    //             'parent_id'     => auth()->id(),
    //             'company_code'  => auth()->user()->company_code,
    //             'expires_at'    => now()->addYear(), 
    //         ]);
            
    //         DB::beginTransaction();
    //         try {
    //             $result = User::updateOrCreate(['id' => $id], $data);

    //             if ($request->has('role_id')) {
    //                 // Eliminar todos los roles actuales (asumiendo que un usuario solo tiene un rol)
    //                 DB::table('model_has_roles')->where('model_id', $result->id)->delete();
    //                 // Asignar el nuevo rol
    //                 DB::table('model_has_roles')->insert([
    //                     'role_id'       => $request->input('role_id'),
    //                     'model_type'    => 'App\Models\User',
    //                     'model_id'      => $result->id
    //                 ]);
    //             }

    //             DB::commit();
    //             return response()->json([
    //                 'status' 	=> true,
    //                 'messages' 	=> empty($id) ? 'Datos del usuario actualizado exitosamente' : 'Se ha añadido un nuevo usuario',
    //             ]);
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //             return response()->json([
    //                 'status' 	=> false,
    //                 'messages' 	=> $e->getMessage(),
    //             ], 500);
    //         }
    //     }
    // }
    public function storeStaff(StaffValidate $request) {
        $countUser  = User::where('parent_id', auth()->id())->count();
        $user       = auth()->user();
        $limitUser  = $user->studentCourses()->where('courses.type', 'package')->orderByDesc('courses.plan_type_id')->first();

        if ($countUser == (int) $limitUser->seats_max + 1) {
            return redirect()->back()->with('error', 'Ya no puedes registrar más usuarios, solicita cambio de plan al administrador');
        }

        $validated = $request->validated();
        $id        = $request->input('user_id');
        $data      = array_merge($validated, [
            'password'      => Hash::make('P4$$w0rd#.'),
            'parent_id'     => auth()->id(),
            'company_code'  => auth()->user()->company_code,
            'expires_at'    => now()->addYear(),

        ]);

        DB::beginTransaction();
        try {
            $result = User::updateOrCreate(['id' => $id], $data);

            if ($request->has('role_id')) {
                DB::table('model_has_roles')->where('model_id', $result->id)->delete();
                DB::table('model_has_roles')->insert([
                    'role_id'    => $request->input('role_id'),
                    'model_type' => 'App\Models\User',
                    'model_id'   => $result->id,
                ]);
            }

            DB::commit();

            $message = empty($id) ? 'Se ha añadido un nuevo usuario' : 'Datos del usuario actualizados exitosamente';
            return redirect()->route('company.list')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vista para matricular usuarios con código
     */
    // public function enrollUsers(): View {
    //     $enterprise    = Enterprise::first();
    //     $hasAnyPackage = User::find(auth()->id())
    //         ->studentCourses()
    //         ->where('courses.type', 'package')
    //         ->exists();
    
    //     if ($hasAnyPackage) {
    
    //         // Colaboradores activos de la empresa
    //         $collaborators = User::where('company_code', auth()->user()->company_code)
    //             ->where('is_active', true)
    //             ->orderBy('names')
    //             ->get();
    
    //         $totalCourses = Course::where('is_active', true)->get();
    
    //         // 1. Mejor paquete comprado por el usuario (el de mayor plan)
    //         $bestPackageCourse = User::find(auth()->id())
    //             ->studentCourses()
    //             ->where('courses.type', 'package')
    //             ->orderByDesc('courses.plan_type_id')
    //             ->first();
    
    //         // 2. Matrícula exacta de ese paquete
    //         $package = $bestPackageCourse
    //             ? Enrollment::where('user_id', Auth::id())
    //                 ->where('course_id', $bestPackageCourse->id)
    //                 ->with('package')
    //                 ->first()
    //             : null;
    
    //         $planType = PlanType::get();
    
    //         // FIX BUG 1: inicializar siempre $courses como colección vacía
    //         $courses = collect();
    
    //         // FIX BUG 4: verificar que $package y su relación existan antes de usarlos
    //         if ($package && $package->package) {
    //             $planTypeId  = $package->package->plan_type_id;
    //             $courseLimit = $package->package->course_limit;
    
    //             if ($planTypeId == 1 && $courseLimit > 0) {
    //                 // Plan Básico: el usuario eligió sus propios cursos
    //                 $courses = UserCoursePackage::with('course')
    //                     ->where('user_id', auth()->id())
    //                     ->get();
    
    //             } elseif ($planTypeId != 1 && $courseLimit == 0) {
    //                 // Plan superior: los cursos vienen asignados al paquete
    //                 // FIX BUG 3: usar $package->course_id (ID del paquete/curso),
    //                 //            NO $package->id (que es el ID del Enrollment)
    //                 $courses = PackageCourse::with('course')
    //                     ->where('package_id', $package->course_id)
    //                     ->get();
    
    //             } else {
    //                 // FIX BUG 2: era "$course" (typo), debe ser "$courses"
    //                 $courses = Course::where('is_active', true)->get();
    //             }
    //         }
    
    //         return view('student.company.enroll-users', compact(
    //             'collaborators',
    //             'totalCourses',
    //             'package',
    //             'planType',
    //             'courses'
    //         ));
    
    //     } else {
    //         return view('student.company.void', compact('enterprise'));
    //     }
    // }
    public function enrollUsers(): View {
        $enterprise    = Enterprise::first();
        $hasAnyPackage = User::find(auth()->id())
            ->studentCourses()
            ->where('courses.type', 'package')
            ->exists();
    
        if ($hasAnyPackage) {
    
            // Colaboradores activos de la empresa
            $collaborators = User::where('company_code', auth()->user()->company_code)
                ->where('is_active', true)
                ->orderBy('names')
                ->get();
    
            $totalCourses = Course::where('is_active', true)->get();
    
            // 1. Mejor paquete comprado (el de mayor plan_type_id)
            $bestPackageCourse = User::find(auth()->id())
                ->studentCourses()
                ->where('courses.type', 'package')
                ->orderByDesc('courses.plan_type_id')
                ->first();
    
            // 2. Matrícula exacta de ese paquete
            $package = $bestPackageCourse
                ? Enrollment::where('user_id', Auth::id())
                    ->where('course_id', $bestPackageCourse->id)
                    ->with('package')
                    ->first()
                : null;
    
            $planType = PlanType::get();
    
            // ── Construir $courses normalizado ────────────────────────────────────
            // SIEMPRE será una colección plana de modelos Course (no pivots),
            // para que las vistas usen $course->id / $course->title / etc. directo.
    
            $courses = collect(); // fallback vacío seguro
    
            if ($package && $package->package) {
                $planTypeId  = $package->package->plan_type_id;
                $courseLimit = $package->package->course_limit;
    
                if ($planTypeId == 1 && $courseLimit > 0) {
                    // Plan Básico: el usuario eligió sus propios cursos
                    // UserCoursePackage es un pivot → extraer el Course relacionado
                    $courses = UserCoursePackage::with('course')
                        ->where('user_id', auth()->id())
                        ->get()
                        ->pluck('course')   // ← normalizar: Collection<Course>
                        ->filter();         // ← quitar nulls si la relación falla
    
                } elseif ($planTypeId != 1 && $courseLimit == 0) {
                    // Plan superior: cursos fijos asignados al paquete
                    // PackageCourse es un pivot → extraer el Course relacionado
                    // Usar $package->course_id (ID del curso/paquete), NO $package->id
                    // $courses = PackageCourse::with('course')
                    //     ->where('package_id', $package->course_id)
                    //     ->get()
                    //     ->pluck('course')   // ← normalizar: Collection<Course>
                    //     ->filter();
                    $courses = Course::where('is_active', true)->where('type', 'course')->get();
    
                } else {
                    // Todos los cursos activos (ya son Course directos, sin pivot)
                    $courses = Course::where('is_active', true)->where('type', 'course')->get();
                }
            }
            // ─────────────────────────────────────────────────────────────────────
    
        


            return view('student.company.enroll-users', compact(
                'collaborators',
                'totalCourses',
                'package',
                'planType',
                'courses'       // ← siempre Collection<Course>
            ));
    
        } else {
            return view('student.company.void', compact('enterprise'));
        }
    }

    /**
     * Matricular usuario
     */
    public function enrollWithCode(Request $request): JsonResponse {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $student    = User::find($request->user_id);
        $course     = Course::find($request->course_id);

        // Verificar que el estudiante pertenezca a la empresa
        if ($student->parent_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no pertenece a tu empresa'
            ], 403);
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

            // Crear la matrícula
            $enrollment = Enrollment::create([
                'user_id'       => $student->id,
                'course_id'     => $course->id,
                'enrolled_at'   => now(),
                'progress'      => 0,
                'status'        => 'active',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Usuario <b>{$student->names}</b> matriculado exitosamente en el curso <b>{$course->title}</b>",
                'data' => [
                    'enrollment_id' => $enrollment->id,
                    'student'       => $student->names,
                    'course'        => $course->title,
                    'code_used'     => $student->code
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

        // $codeE  = User::where('id', Auth::id())->first();
        $course = Course::find($request->course_id);

        $results = [
            'success'   => [],
            'failed'    => []
        ];

        DB::beginTransaction();
        try {
            foreach ($request->user_ids as $userId) {
                $student = User::find($userId);

                // Verificiar que el estudiante tenga el parent_id del usuario que lo registro
                if ($student->parent_id !== Auth::id()) {
                    $results['failed'][] = [
                        'user'      => $student->names,
                        'reason'    => 'No pertenece a tu empresa'
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

                // Crear matrícula
                Enrollment::create([
                    'user_id'       => $student->id,
                    'course_id'     => $course->id,
                    'enrolled_at'   => now(),
                    'progress'      => 0,
                    'status'        => 'active',
                ]);

                // $coursePromotionCode->increment('used_count');
                // $student->increment('courses_sold_count');

                $results['success'][] = $student->names;
            }

            DB::commit();

            $message = "Se matricularon " . count($results['success']) . " usuarios exitosamente";
            if (count($results['failed']) > 0) {
                $message .= ". " . count($results['failed']) . " usuarios no pudieron ser matriculados.";
            }

            return response()->json([
                'success'   => true,
                'message'   => $message,
                'data'      => $results
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
        $users = User::where('parent_id', Auth::id())
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
        // Obtener todos los colaboradores (filtrados por el id del usuario padre)
        $students = User::where('parent_id', Auth::id())
            ->orWhere('id', Auth::id()) // Incluimos al usuario padre para matricular en los cursos
            ->get();
        
        // Obtener todos los cursos activos por paquete o tipo de paquete
        // Verifica que el usuario padre está matriculado estrictamente en un paquete
            $package = Enrollment::where('user_id', Auth::id())
                ->whereHas('package') 
                ->with('package')
                ->first();

        // Verificamos que el paquete tenga cursos 
        $packageWithCourses = PackageCourse::with('course')->where('package_id', $package->course_id)->exists();

        if($packageWithCourses){
            // Paquete con cursos
            $courses = PackageCourse::with('course')->where('package_id', $package)->get();
            // Verificamos si el paquete el plan del paquete es 'Plan Básico' con un límite de cursos > 0
        }elseif($package->package->plan_type_id == 1 && $package->package->course_limit > 0) { 
            $courses = UserCoursePackage::with('course')->where('user_id', auth()->id())->get();
        }else{
            $course = Course::where('is_active', true)->get();
        }

        if ($students->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay usuarios para matricular'
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

                    // Crear matrícula
                    Enrollment::create([
                        'user_id'       => $student->id,
                        'course_id'     => $course->id,
                        'enrolled_at'   => now(),
                        'progress'      => 0,
                        'status'        => 'active',
                    ]);

                    $results['success'][] = [
                        'user'      => $student->names,
                        'course'    => $course->title
                    ];
                    
                    $results['total_processed']++;
                }
            }

            DB::commit();

            $message = "Matrículas completadas: {$results['total_processed']} inscripciones realizadas exitosamente";
            
            if (count($results['failed']) > 0) {
                $message .= ". " . count($results['failed']) . " inscripciones fallidas.";
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
                        Matrículas fallidas: <span class='text-amber-600 font-bold'>" . count($results['failed']) . "</span>",
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

    public function toggleStatus(User $user): JsonResponse {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Estado del usuario actualizado.',
            'status'    => $user->is_active
        ]);
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
