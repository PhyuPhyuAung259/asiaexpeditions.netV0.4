<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    public function __construct()
    {
        // // echo \Auth::user()->id;
        // // echo Auth::user();
        // // $this->middleware('IsLogin');
        // if(Auth::check()){
        //     // return redirect()->intended('admin');
        //     // return 'fdasfds';
        //     echo 'Logen';
        // }else{
        //     echo 'Not logn';
        // }
    }
    public function getLogin(){
        if (Auth::check()) {
            return redirect()->intended('/');
        }
        return view('login.login');
    }

    public function doLogin(Request $req){
        $validator = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if(!$validator->fails()){
            if (\Auth::attempt(['email'=>$req->email, 'password'=>$req->password, 'banned'=>0], $req->remember)) {
                return redirect()->intended('/');
                // return redirect()url()->previous();
            }
            return back()->withInput()->with("message", "incorrect username or password");
        }else{            
            return back()->withErrors($validator)
                        ->withInput()->with("message", "incorrect username or password");
        }
        // Session()->flush("message", "incorrect username or password");
    }

    public function getLogOut(Request $req){
        \Auth::logout();
        return back();
    }
   
}
