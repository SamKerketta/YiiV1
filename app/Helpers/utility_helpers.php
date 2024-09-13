<?php

/**
 * | Response Msg Version1 with apiMetaData
 */

use yii\web\Response;

if (!function_exists("responseMsg")) {
    function responseMsg($status, $message, $data)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return  ['status' => $status, "message" => $message, "data" => $data];
    }
}

/**
 * | To throw Validation Error
 */
if (!function_exists("validationError")) {
    function validationError($validator)
    {
        Yii::$app->response->statusCode = 422;
        return responseMsg(false, $validator->errors, 'Validation Error');
    }
}

/**
 * | Generate random alphabets 
 */
if (!function_exists("randomName")) {
    function randomName($length = 6)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
