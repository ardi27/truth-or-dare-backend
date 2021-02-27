<?php

namespace App\Http\Controllers;

use App\Http\Utils\ResponseTemplate;
use App\Models\Truth;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    //
    public function index()
    {
        $res['code'] = 404;
        $res['results'] = null;
        $res['message'] = 'an error occured';
        if ($users = User::paginate(10)) {
            $res['code'] = 200;
            $res['results'] = $users;
            $res['message'] = "Data retrieved succesfully";
        }
        return response()->json($res, $res['code']);
    }
    public function profile(Request $request)
    {
        $res = ResponseTemplate::getResponse();
        try {
            if ($user = User::findOrFail($request->auth->uuid)) {
                $res['code'] = 200;
                $res['message'] = "User succesfully retrieved";
                $res['results'] = $user;
            }
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            return response()->json($res, $res['code']);
        }
    }
    public function updateProfile(Request $request)
    {
        $res['code'] = 404;
        $res['message'] = 'An error occured';
        $res['results'] = null;
        if ($request->has('password')) {
            $res['code'] = 422;
            $res['message'] = "Cannot update password,please use change password endpoint";
            return response()->json($res, $res['code']);
        }
        if ($user = User::find($request->auth->uuid)) {
            $user->update($request->all());
            $res['code'] = 200;
            $res['message'] = "Profile succesfully updated";
        }
        return response()->json($res, $res['code']);
    }
    public function updatePassword(Request $request)
    {
        $res = ResponseTemplate::getResponse();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required'
        ]);
        if ($validator->fails()) {
            $res['code'] = 422;
            $res['results'] = $validator->errors()->first();
            $res['message'] = "Form validation error";
            return response()->json($res, $res['code']);
        }
        try {
            $user = User::findOrFail($request->auth->uuid);
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                $res['code'] = 200;
                $res['message'] = 'Password succesfully updated';
            } else {
                $res['code'] = 404;
                $res['message'] = "Password not match";
            }
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            return response()->json($res, $res['code']);
        }
    }
}
