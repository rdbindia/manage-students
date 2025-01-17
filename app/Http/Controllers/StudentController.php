<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class StudentController extends Controller
{
    public function index(): View|Factory|Application
    {
        $students = Student::with('media')->paginate(10);

        return view('students.index', compact('students'));
    }
}
