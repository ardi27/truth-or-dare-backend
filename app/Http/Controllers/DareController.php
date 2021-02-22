<?php

namespace App\Http\Controllers;

use App\Http\Utils\ResponseTemplate;
use App\Models\Dare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DareController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['random']]);
    }
    public function store(Request $request)
    {
        $res = ResponseTemplate::getResponse();
        $validator = Validator::make($request->all(), [
            'dare' => 'required|string',
            'level' => 'required|in:easy,medium,hard'
        ]);
        if ($validator->fails()) {
            $res['code'] = 417;
            $res['results'] = $validator->errors()->first();
            return response()->json($res, $res['code']);
        }
        if ($dare = Dare::create($request->all())) {
            $res['code'] = 201;
            $res['message'] = 'Dare succesfully created';
        }
        return response()->json($res, $res['code']);
    }
    public function random()
    {
        $res = ResponseTemplate::getResponse();
        if ($dare = Dare::inRandomOrder()->first()) {
            $res['code'] = 200;
            $res['results'] = $dare;
            $res['message'] = 'Random truth retrieved succesfully';
        }
        return response()->json($res, $res['code']);
    }
}
