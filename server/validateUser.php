<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

if (empty($reqBody->email) || empty($reqBody->password) || empty($reqBody->userId))
    setResponseStatus($response, false, "Input validation failed");

if ($response->success) {
    try {
        $queryFetchUsers = "SELECT * FROM users WHERE email='$reqBody->email' AND password='$reqBody->password' AND userId='$reqBody->userId';";
        $users = $response->connection->query($queryFetchUsers);
        if ($users->num_rows > 0) {
            $response->isValidUser = true;
            $response->message = "User match found";

            $response->users = array();
            for ($i = 0; $i < $users->num_rows; $i++) {
                $row = $users->fetch_object();
                $response->users[$i] = $row;
            }
        }
        else {
            $response->isValidUser = false;
            $response->message = "No user found";
        }
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while validating user list");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

unset($response->connection);
echo json_encode($response, JSON_PRETTY_PRINT);

?>