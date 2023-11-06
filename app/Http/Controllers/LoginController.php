<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(){
        if(auth()->user()){
            return back();
        }

        return view('login_form');
    }
    public function postLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email','string'],
            'password' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['errors' => 'Invalid login credentials','success'=>false]);
        }

    }
    public function registerUser(Request $request){
        if(auth()->user()){
            return back();
        }
        // dd($request->password==$request->confirm_password);
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'max:20', 'string'], 
            'email' => ['required', 'email','unique:users,email'],
            'password' => ['required', 'string', 'min:5','confirmed'],
        ]);
    
       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::create([
            'name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')), 
            'role_id' =>2
        ]);
        return response()->json([
            'success'=>true,
            'message'=>'You have registered successfully'
        ]);
    }
    public function logout(){
        Auth::logout();
        return back()->with('success', 'You have been logged out successfully.');
    }
}
