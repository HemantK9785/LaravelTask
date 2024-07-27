<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $validat= Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        //print_r($request->all());die;

        if($validat->passes()){
            if(Auth::attempt([
                'email'=>$request->email,
                'password'=>$request->password
            ])){
                 return redirect()->intended(route('user.dashboard', absolute: false));
            }else{
                return redirect(route('user.login'))->with('error','Email and Password is incorrect.');
            }
        }else{
            return redirect()->route('user.login')
            ->withInput()
            ->withErrors($validat);
        }
    }

    public function register(){
        return view('register');
    }

    public function newRgister(Request $request){
       $validat= Validator::make($request->all(),[
            'name'=>'required|min:6',
            'email'=>'required|email|unique:users',
            'password'=>'required|same:confirm_password|min:6'
        ]);

       if($validat->passes()){
            $user=new User;
            $user->name=$request->name;
            $user->email=$request->email;
            $user->password=Hash::make($request->password);
            $user->save();
            return redirect(route('user.login'))->with('success','You have successfully registered.');    
        }else{
            return redirect()->route('user.register')
            ->withInput()
            ->withErrors($validat);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('user.login')->with('success','You have logout successfully.');
    }
}
