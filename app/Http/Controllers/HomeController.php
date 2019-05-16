<?php

namespace App\Http\Controllers;

use App\Department;
use App\Doctype;
use App\Document;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Redirect;
use DB;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    public function messages()
    {
        $messages = DB::table('messages')->where('receiver_id','=',Auth::user()->id)->orderBy('time', 'desc')->get();
        return view('messages.index', compact('messages'));
    }

    public function statistics()
    {
        $doctypes = Doctype::all();
        $departments_number = Department::all()->count();
        $doctypes_number = Doctype::all()->count();
        $docs_number = Document::all()->where('status',100)->count();
        return view('statistics.index', compact('departments_number','doctypes_number','docs_number','doctypes'));
    }

    public function faq()
    {
        return view('faq.index');
    }

    public function report()
    {
        return view('report.index');
    }

    public function setLanguage()
    {
        if(!\Session::has('locale'))
        {
            \Session::put('locale', Input::get('locale'));
        } else {
            Session::set('locale',Input::get('locale'));
        }
        return Redirect::back();
        //app()->setLocale($request->input('locale'));

    }
}
