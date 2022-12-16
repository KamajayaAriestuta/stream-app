<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index(){
        return view('member.register');
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'phone-number' => 'required', 
            'email' => 'required', 
            'password' => 'required|min:6',
        ]);

        $data = $request->except('_token');

        $isEmailExist = User::where('email', $request->email)->exists();


        if($isEmailExist){
            return back()->withErrors([
                'email' => 'Email is Already Exist'
            ])->withInput();
        }

        $data['role']= 'member';
        $data['password']= Hash::make($request->password);

        User::create($data);
        return back();
        // return redirect()->route('member.login');
    }
}