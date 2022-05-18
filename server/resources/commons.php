<?php

$currentTimeStamp = gmdate("Y-m-d\TH:i:s\Z");
function setResponseStatus($response, $status, $message)
{
    $response->success = $status;
    $response->message = $message;
}

function setResponseError($response, $code, $error)
{
    $response->errorCode = $code;
    $response->errorMessage = $error;
}

function validateRequest($response, $method)
{
    if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
        setResponseStatus($response, false, "Only " . $method . " requests allowed");
    }
}

function decodeRequest($response)
{
    $rawRequest = file_get_contents('php://input');
    $reqJson = json_decode($rawRequest, false);
    if (!is_object($reqJson)) {
        setResponseStatus($response, false, "Decoding Input params failed");
    }
    return $reqJson;
}

?>