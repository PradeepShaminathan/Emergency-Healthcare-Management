<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

if (empty($reqBody->recordId) || empty($reqBody->roleConfig))
    setResponseStatus($response, false, "Input validation failed");

if ($response->success) {
    $queryUpdateUser = "UPDATE `users` SET `roleConfig` = '$reqBody->roleConfig' WHERE `recordId` = '$reqBody->recordId';";
    try {
        $response->connection->query($queryUpdateUser);
        setResponseStatus($response, true, "User updation successful");
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while updating user");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);

?>