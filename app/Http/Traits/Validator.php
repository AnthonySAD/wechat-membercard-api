<?php
/**
 * Created by PhpStorm.
 * User: 31272
 * Date: 2018/12/15
 * Time: 14:30
 */

namespace App\Http\Traits;


use App\Exceptions\ApiException;
use App\Exceptions\ErrorCodes;

trait Validator
{
    private function easyValidator($request, $rules, $mark = true)
    {
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()){
            throw new ApiException(ErrorCodes::BAD_REQUEST, json_encode($validator->messages()));
        }

        if (!$mark){
            return true;
        }

        foreach ($rules as $key => $value){
            !isset($request[$key]) || $request[$key] == '' || $data[$key] = $request[$key];
        }
        if (!isset($data)){
            throw new ApiException(ErrorCodes::BAD_REQUEST);
        }

        return $data;
    }
}