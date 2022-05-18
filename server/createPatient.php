<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

if (empty($reqBody->date) || empty($reqBody->place) || empty($reqBody->symptoms) || empty($reqBody->severity) || empty($reqBody->gender) || empty($reqBody->address) || empty($reqBody->name) || empty($reqBody->phoneNumber) || empty($reqBody->dob) || empty($reqBody->bloodGroup) || empty($reqBody->creatorEmail) || empty($reqBody->creatorName || empty($reqBody->assignedHospital)))
    setResponseStatus($response, false, "Input validation failed");

if ($response->success) {
    $queryCreatePatient = "INSERT INTO `patientDetails` (`date`, `place`, `symptoms`, `severity`, `gender`, `address`, `fullName`, `phoneNumber`, `dateOfBirth`, `bloodGroup`, `medicalReportFile`, `photoFile`, `creatorEmail`, `creatorName`, `recordCreationTime`, `assignedHospitalId`) VALUES ('$reqBody->date', '$reqBody->place', '$reqBody->symptoms', '$reqBody->severity', '$reqBody->gender', '$reqBody->address', '$reqBody->name', '$reqBody->phoneNumber', '$reqBody->dob', '$reqBody->bloodGroup', '{}', '{}', '$reqBody->creatorEmail', '$reqBody->creatorName','$currentTimeStamp', '$reqBody->assignedHospital');";
    try {
        $response->connection->query($queryCreatePatient);
        setResponseStatus($response, true, "Patient creation successful");
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while creating patient");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

echo json_encode($response, JSON_PRETTY_PRINT);

?>