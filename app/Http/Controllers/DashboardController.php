<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        if(auth()->user()->hasRole('student')){
            return view('dashboard.student');
        }

        return view('dashboard');
        
    }
}
