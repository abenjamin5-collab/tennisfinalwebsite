<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookLesson;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CoachController extends Controller
{
    public function getCoaches()
    {

        $users = User::where('role', 'coach')->get();
        return response()->json(['coaches' => $users]);
    }

    public function getCoachPastStudents()
    {
        $user = Auth::user();
        $students = BookLesson::with('student')
            ->where('coach_id', $user->id)
            ->distinct('student_id')
            ->get()
            ->pluck('student');

        return response()->json(['data' => $students]);
    }
}
