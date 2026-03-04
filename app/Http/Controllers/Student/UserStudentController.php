<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;

class UserStudentController extends Controller
{
    public function __construct(){
        $this->middleware(['auth:sanctum', 'student', 'prevent.back']);
    }

    public function updateLimitBusinessPolicies(User $user){
        $package = Enrollment::with(['course'])
            ->where('user_id', $user->id)
            ->where('enrollments.course.type', 'package')
            ->first();

        
    }
}
