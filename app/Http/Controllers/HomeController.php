<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(Auth::user()->status == 99 || Auth::user()->status == 1){
        return redirect('teacher');
      }

      if(Auth::user()->status == NULL){
        return redirect('student');
      }

        return view('home');
    }
}
