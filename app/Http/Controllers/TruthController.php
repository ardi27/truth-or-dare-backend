<?php

namespace App\Http\Controllers;

use App\Http\Utils\ResponseTemplate;
use App\Models\Truth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TruthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['random']]);
    }
    public function index()
    {
        $res = ResponseTemplate::getResponse();
        if ($truth = Truth::with('user')->orderBy('created_at', 'desc')->paginate(10)) {
            $res['code'] = 200;
            $res['results'] = $truth;
            $res['message'] = 'Truth succesfully retrieved';
        }
        return response()->json($res, $res['code']);
    }
    public function detail($uuid)
    {
        $res = ResponseTemplate::getResponse();
        try {
            $truth = Truth::with('user')->findOrFail($uuid);
            if ($truth) {
                $res['code'] = 200;
                $res['results'] = $truth;
                $res['message'] = "Truth succesfully retrieved";
            } else {
                $res['message'] = "Truth not found";
            }
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            $res['message'] = "Truth not found";
            return response()->json($res, $res['code']);
        }
    }
    public function random(Request $request)
    {
        $res = ResponseTemplate::getResponse();
        $truth = Truth::with('user')->inRandomOrder();
        if ($request->has("level") && $request->level != "") {
            $truth->where("level", $request->level);
        }
        if ($truth) {
            $res['code'] = 200;
            $res['results'] = $truth->first();
            $res['message'] = 'Random truth retrieved succesfully';
        }
        return response()->json($res, $res['code']);
    }
    public function getByUser(Request $request)
    {
        $res = ResponseTemplate::getResponse();
        if ($truth = Truth::where('user_id', $request->auth->uuid)->orderBy('created_at', 'desc')->paginate(10)) {
            $res['code'] = 200;
            $res['results'] = $truth;
            $res['message'] = "Truth retrieved successfully";
        };
        return response()->json($res, $res['code']);
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
        $request->request->add(['user_id' => $request->auth->uuid]);
        if ($truth = Truth::create($request->all())) {
            $res['code'] = 201;
            $res['message'] = 'Truth succesfully created';
        }
        return response()->json($res, $res['code']);
    }
    public function update(Request $request, $uuid)
    {
        $res = ResponseTemplate::getResponse();
        $validator = Validator::make($request->all(), [
            'level' => 'in:easy,medium,hard'
        ]);
        if ($validator->fails()) {
            $res['code'] = 417;
            $res['results'] = $validator->errors()->first();
            return response()->json($res, $res['code']);
        }
        try {
            $truth = Truth::findOrFail($uuid);
            $truth->update($request->all());
            $res['message'] = 'Succesfully updated';
            $res['code'] = 200;
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            $res['message'] = 'Truth not found';
            return response()->json($res, $res['code']);
        }
    }
    public function delete($uuid)
    {
        $res = ResponseTemplate::getResponse();
        try {
            $truth = Truth::findOrFail($uuid);
            if ($truth) {
                $truth->delete();
                $res['message'] = 'Succesfully deleted';
                $res['code'] = 200;
            }
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            $res['message'] = 'Truth not found';
            return response()->json($res, $res['code']);
        }
    }
}
