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
