<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;

class CustomerManager extends Controller
{
    function updateAddress(Request $request)
    {
        $user = User::where("email", $request->email)->first();
        $user->destination_address = $request->address;
        $user->destination_lat = 57.79937;
        $user->destination_lon = 40.9568;
        if ($user->save()) {
            return "success";
        }

        return "error";
    }
}
