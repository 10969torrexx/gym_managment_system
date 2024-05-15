<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       switch (Auth::user()->role) {
        case 0:
            return redirect(route('usersIndex'));
            break;
        case 1:
            return redirect(route('exercisesIndex'));
            break;
       }
    }
}
