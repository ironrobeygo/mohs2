<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function index(){
        return view('admin.student.course.index');
    }
}
