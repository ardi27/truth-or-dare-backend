<?php

namespace App\Http\Controllers;

use App\Http\Utils\ResponseTemplate;
use App\Models\Dare;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DareController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['random']]);
    }
    public function index()
    {
        $res = ResponseTemplate::getResponse();
        if ($dare = Dare::with('user')->orderBy('created_at', 'desc')->paginate(10)) {
            $res['code'] = 200;
            $res['results'] = $dare;
            $res['message'] = 'dare succesfully retrieved';
        }
        return response()->json($res, $res['code']);
    }
    public function detail($uuid)
    {
        $res = ResponseTemplate::getResponse();
        try {
            $dare = Dare::with('user')->findOrFail($uuid);
            if ($dare) {
                $res['code'] = 200;
                $res['results'] = $dare;
                $res['message'] = "dare succesfully retrieved";
            } else {
                $res['message'] = "dare not found";
            }
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            $res['message'] = "dare not found";
            return response()->json($res, $res['code']);
        }
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
        $request->request->add(['user_id' => $request->auth->uuid]);
        if ($dare = Dare::create($request->all())) {
            $res['code'] = 201;
            $res['message'] = 'Dare succesfully created';
        }
        return response()->json($res, $res['code']);
    }
    public function random()
    {
        $res = ResponseTemplate::getResponse();
        if ($dare = Dare::with('user')->inRandomOrder()->first()) {
            $res['code'] = 200;
            $res['results'] = $dare;
            $res['message'] = 'Random dare retrieved succesfully';
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
            $dare = Dare::findOrFail($uuid);
            $dare->update($request->all());
            $res['message'] = 'Succesfully updated';
            $res['code'] = 200;
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            $res['message'] = 'dare not found';
            return response()->json($res, $res['code']);
        }
    }
    public function delete($uuid)
    {
        $res = ResponseTemplate::getResponse();
        try {
            $dare = Dare::findOrFail($uuid);
            if ($dare) {
                $dare->delete();
                $res['message'] = 'Succesfully deleted';
                $res['code'] = 200;
            }
            return response()->json($res, $res['code']);
        } catch (Exception $e) {
            $res['message'] = 'dare not found';
            return response()->json($res, $res['code']);
        }
    }
}
