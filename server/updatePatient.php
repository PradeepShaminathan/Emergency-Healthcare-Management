<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

if (empty($reqBody->recordId) || empty($reqBody->date) || empty($reqBody->place) || empty($reqBody->symptoms) || empty($reqBody->severity) || empty($reqBody->address) || empty($reqBody->name) || empty($reqBody->phoneNumber) || empty($reqBody->dob) || empty($reqBody->bloodGroup) || empty($reqBody->assignedHospital))
    setResponseStatus($response, false, "Input validation failed");

if ($response->success) {
    $queryUpdatePatient = "UPDATE `patients` SET `date`='$reqBody->date', `place`='$reqBody->place', `symptoms`='$reqBody->symptoms', `severity`='$reqBody->severity', `address`='$reqBody->address', `fullName`='$reqBody->name', `phoneNumber`='$reqBody->phoneNumber', `dateOfBirth`='$reqBody->dob', `bloodGroup`='$reqBody->bloodGroup', `assignedHospitalId`='$reqBody->assignedHospital', `oldRecordFlag`=0 WHERE `recordId`=$reqBody->recordId;";
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