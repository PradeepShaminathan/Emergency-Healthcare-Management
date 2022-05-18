<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

if (empty($reqBody->email) || empty($reqBody->password) || empty($reqBody->userId) || empty($reqBody->firstName) || empty($reqBody->role) || empty($reqBody->lastName))
    setResponseStatus($response, false, "Input validation failed");

if ($response->success) {
    $queryCreateUser = "INSERT INTO users (userId, email, password, firstName, lastName, role, userCreatedTime, roleConfig) VALUES ('$reqBody->userId', '$reqBody->email', '$reqBody->password', '$reqBody->firstName', '$reqBody->lastName', '$reqBody->role', '$currentTimeStamp', '$reqBody->roleConfig')";
    try {
        $response->connection->query($queryCreateUser);
        setResponseStatus($response, true, "User creation successful");
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while creating user");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

unset($response->connection);
echo json_encode($response, JSON_PRETTY_PRINT);

?>