<?php

namespace App\Traits\Auth;

trait AdminLogin
{
    public function login($email , $password){
        $credentials = [
            'email'    => $email,
            'password' => $password,
        ];
         $admin = Admin::where('email', $credentials['email'])->first();
         // check if two password are same
         if(!Hash::check($password , $admin->password)){
            return false;
         }

        $token = $admin->createToken('admin')->plainTextToken;
        $admin['role_type']= "Super Admin";
        return [
            'admin' => $admin,
            'token' => $token,
        ];
    }
}
