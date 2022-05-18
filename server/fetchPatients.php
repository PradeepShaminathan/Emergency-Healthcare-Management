<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

$queryFetchPatients = "SELECT * FROM patientDetails WHERE assignedHospitalId='$reqBody->assignedHospitalId';";
if (!empty($reqBody->creatorEmail)) {
    $queryFetchPatients = "SELECT * FROM patientDetails WHERE creatorEmail='$reqBody->creatorEmail';";
}

if ($response->success) {
    try {
        $response->patients = array();
        $patients = $response->connection->query($queryFetchPatients);
        for ($i = 0; $i < $patients->num_rows; $i++) {
            $row = $patients->fetch_object();
            $response->patients[$i] = $row;
        }
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while fetching patients list");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

unset($response->connection);
echo json_encode($response, JSON_PRETTY_PRINT);

?>