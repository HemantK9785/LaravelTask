<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class RegisterController extends BaseController
{
    //User Register
     public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        
        if($validator->fails()){
            //Use Comman Function     
            return $this->sendError('Validation Error.', $validator->errors()); 

            //Without Function
            //  $response = [
            //     'success' => false,
            //     'message' => 'Validation Error.',
            // ]; 
            // if(!empty($validator->errors())){
            //     $response['data'] = $validator->errors();
            // } 
            // $code = 404;
            // return response()->json($response, $code);      
        }
     
        //Rigester By Model
        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $user = User::create($input);

        //Rigester Use By Model
        $user=new User;
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);
        $user->save();

        //Without Login Send Data
        // $success['token'] =  $user->createToken('MyApp')->accessToken;
        // $success['name'] =  $user->name;
        //return $this->sendResponse($success, 'User register successfully.');

        //Only Register
        return $this->sendResponse([], 'User register successfully.');
    }

    //User Login
    public function login(Request $request): JsonResponse
    {   
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if($validator->fails()){   
            return $this->sendError('Validation Error.', $validator->errors()); 
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User login successfully.');
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        } 
    }

    //User Profile
    public function profile(Request $request): JsonResponse
    {
        $user=auth()->user();   
        return $this->sendResponse($user, 'User profile update successfully.');
    }

    //User Profile
    public function profileUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^([a-zA-Z]+)(\s[a-zA-Z]+)*$/',
        ]);
     
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user=User::find(auth()->user()->id);
        $user->name=$request->name;
        $user->save();   
        return $this->sendResponse($user, 'User profile update successfully.');
    }

     //User Profile
    public function logout(Request $request)
    {  
        $uesr=Auth::user()->token();
        $uesr->revoke();
        return $this->sendResponse([], 'User logout successfully.');
    }
}
