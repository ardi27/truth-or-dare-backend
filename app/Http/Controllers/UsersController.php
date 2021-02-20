<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
    public function updateProfile(Request $request)
    {
        $res['code'] = 404;
        $res['message'] = 'An error occured';
        $res['results'] = null;
        if ($request->has('password')) {
            $res['code'] = 422;
            $res['message'] = "Cannot update password, please use change password endpoint";
            return response()->json($res, $res['code']);
        }
        if ($user = User::find($request->uuid)) {
            $user->update($request->all());
            $res['code'] = 200;
            $res['message'] = "Profile succesfully updated";
        }
        return response()->json($res, $res['code']);
    }
}
