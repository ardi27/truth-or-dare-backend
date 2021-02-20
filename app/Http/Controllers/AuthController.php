<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $res['code'] = 404;
        $res['results'] = null;
        $res['message'] = 'an error occured';
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|unique:users',
                'password' => 'required|min:8',
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
            ]
        );

        if ($validator->fails()) {
            $res['results'] = $validator->errors();
            $res['code'] = 417;
            $res['message'] = 'Error form validation';
            return response()->json($res, $res['code']);
        }
        if ($user = User::create($request->all())) {
            $res['code'] = 201;
            $res['message'] = 'User created succesfully';
        }
        return response()->json($res, $res['code']);
    }
}
