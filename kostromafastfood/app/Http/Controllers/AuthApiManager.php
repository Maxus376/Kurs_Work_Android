<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthApiManager extends Controller
{
    function login(Request $request){
        if(empty($request->email) && empty($request->password)){
            return array ("status"=> "failed", "message"=>"Не все поля заполнены");
        }

        $user = User::where("email", $request->email)->first();
        if(!$user){
            return array ("status"=> "failed", "message"=>"Такого пользователя не существует");
        }
        $credentials = $request->only("email", "password");
        if(Auth::attempt($credentials)){
            return array ("status"=> "success", "message"=>"Вы вошли в систему", "name"=>$user->name, "email"=>$user->email);
        }

        return array ("status"=> "failed", "message"=>"Такого пользователя не существует");
        
    }

    function registration(Request $request){
        if(empty($request->name) && empty($request->phone_number) && empty($request->email) && empty($request->password)){
            return array ("status"=> "failed", "message"=>"Не все поля заполнены");
        }

        $user = User::create([
            "type" => "customer",
            "name" => $request->name,
            "surname" => $request->surname,
            "phone_number" => $request->phone_number,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "destination_lat" => 57.79937,
            "destination_lon" => 40.9568
        ]);

        if(!$user){
            return "error";
        }

        return "success";

    }
}
