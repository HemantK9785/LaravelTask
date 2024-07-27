<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{   
   //This method will show admin login 
   public function index(){
    return view('admin.login');

   }

   //Admin Login Auth
   public function login(Request $request){
        $validat= Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        //print_r($request->all());die;

        if($validat->passes()){
            if(Auth::guard('admin')->attempt([
                'email'=>$request->email,
                'password'=>$request->password
            ])){
                    if(Auth::guard('admin')->user()->role != 'admin'){
                        Auth::guard('admin')->logout();
                        return redirect(route('admin.login'))->with('error','You are not authorized to access this page.');
                    }
                        return redirect()->intended(route('admin.dashboard', absolute: false));

            }else{
                return redirect(route('admin.login'))->with('error','Email and Password is incorrect.');
            }
        }else{
            return redirect(route('admin.login'))
            ->withInput()
            ->withErrors($validat);
        }
    }

    //Admin Logout
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','You have logout successfully.');
    }
}
