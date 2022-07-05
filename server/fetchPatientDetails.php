<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

if (empty($reqBody->recordId))
    setResponseStatus($response, false, "Input validation failed");

if ($response->success) {
    try {
        $queryFetchPatients = "SELECT * FROM patients WHERE recordId='$reqBody->recordId';";
        $patients = $response->connection->query($queryFetchPatients);
        if ($patients->num_rows > 0) {
            $response->isValidUser = true;
            $response->message = "Patient match found";

            $response->patients = array();
            for ($i = 0; $i < $patients->num_rows; $i++) {
                $row = $patients->fetch_object();
                $response->patients[$i] = $row;
            }
        }
        else {
            $response->isValidUser = false;
            $response->message = "No patients found";
        }
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while validating patients list");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

unset($response->connection);
echo json_encode($response, JSON_PRETTY_PRINT);

?>