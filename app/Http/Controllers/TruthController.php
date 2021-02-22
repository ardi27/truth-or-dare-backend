<?php

namespace App\Http\Controllers;

use App\Http\Utils\ResponseTemplate;
use App\Models\Truth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TruthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    public function store(Request $request)
    {
        $res['code'] = 404;
        $res['results'] = null;
        $res['message'] = 'An error occured';
        $validator = Validator::make($request->all(), [
            'truth' => 'required|string',
            'level' => 'required|in:easy,medium,hard'
        ]);
        if ($validator->fails()) {
            $res['code'] = 417;
            $res['results'] = $validator->errors()->first();
            return response()->json($res, $res['code']);
        }
        if ($truth = Truth::create($request->all())) {
            $res['code'] = 201;
            $res['message'] = 'Truth succesfully created';
        }
        return response()->json($res, $res['code']);
    }
    public function random()
    {
        $res = ResponseTemplate::getResponse();
        if ($truth = Truth::inRandomOrder()->first()) {
            $res['code'] = 200;
            $res['results'] = $truth;
            $res['message'] = 'Random truth retrieved succesfully';
        }
        return response()->json($res, $res['code']);
    }
}
