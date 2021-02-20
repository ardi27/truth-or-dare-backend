<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        // $url = parse_url(getenv("DATABASE_URL"));
        return response()->json($users, 200);
    }
}
