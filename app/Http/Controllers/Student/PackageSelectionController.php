<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\UserCoursePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageSelectionController extends Controller {

    public function __construct() {
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function showSelectionForm($packageId) {
        $package    = Course::where('id', $packageId)->where('type', 'package')->firstOrFail();
        $categories = Category::where('is_active', true)->get();

        return view('student.company.select-courses', compact('package', 'categories'));
    }

    // API para obtener los cursos con filtros y paginación (AJAX)
    public function getCourses(Request $request) {
        $query = Course::where('type', 'course')->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Paginamos de 8 en 8 para el grid
        $courses = $query->paginate(8);

        return response()->json($courses);
    }

    // Guarda los cursos seleccionados
    public function storeSelection(Request $request, $packageId) {
        $package = Course::where('id', $packageId)->where('type', 'package')->firstOrFail();
        
        $request->validate([
            'selected_courses'      => 'required|array|min:1|max:'.$package->course_limit,
            'selected_courses.*'    => 'exists:courses,id'
        ]);

        foreach ($request->selected_courses as $courseId) {
            UserCoursePackage::create([
                'package_id'    => $package->id,
                'course_id'     => $courseId,
                'user_id'       => Auth::id(),
            ]);
        }

        return response()->json([
            'success'   => true,
            'message'   => '¡Excelente! Tus cursos han sido agregados al paquete con éxito.',
            'redirect'  => route('company.enroll.users') // Ajusta a tu ruta real
        ]);
    }
}
