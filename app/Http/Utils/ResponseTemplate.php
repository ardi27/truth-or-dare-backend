<?php

namespace App\Http\Utils;

class ResponseTemplate
{
    public static function getResponse()
    {
        return [
            'code' => 404,
            'results' => null,
            'message' => 'An error occured'
        ];
    }
}
