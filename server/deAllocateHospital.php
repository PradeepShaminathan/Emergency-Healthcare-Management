<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

if (empty($reqBody->recordId))
    setResponseStatus($response, false, "Input validation failed");

if ($response->success) {
    $queryUpdatePatient = "UPDATE `patients` SET `allocatedHospitalId` = NULL WHERE `recordId` = '$reqBody->recordId';";
    try {
        $response->connection->query($queryUpdatePatient);
        setResponseStatus($response, true, "Patient updation successful");
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while updating patient");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);

?>