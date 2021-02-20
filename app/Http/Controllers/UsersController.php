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
}
