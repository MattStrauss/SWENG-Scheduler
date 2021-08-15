<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('dev.users.only')->only('index');
    }

    /**
     * Show the mark course as completed form
     */
    public function completedForm()
    {
        $pageName = "Mark Completed Courses";
        $courses = Course::orderBy('abbreviation')->get()->all();
        $currentCompletedCourses = auth()->user()->completedCourses()->pluck('course_id')->toArray();

        return view('mark-completed',
            compact('courses', 'pageName', 'currentCompletedCourses'));
    }

    /**
     * Mark submitted classes as completed for auth user
     */
    public function markAsCompleted(Request $request)
    {
        auth()->user()->completedCourses()->sync($request->input('completed'));

        return redirect(route('completedForm'))->with('status', 'Completed Courses Successfully Updated!');
    }

    public function index()
    {

        $pageName = "Application Users";
        $users = User::all();

        return view('users', compact('pageName', 'users'));
    }
}
