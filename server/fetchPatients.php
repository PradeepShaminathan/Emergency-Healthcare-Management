<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');
$reqBody = decodeRequest($response);

$queryFetchPatients = "SELECT * FROM patients;";

if (!empty($reqBody->assignedHospitalId)) {
    $queryFetchPatients = "SELECT * FROM patients WHERE assignedHospitalId='$reqBody->assignedHospitalId' ORDER BY recordCreationTime;";
}

if (!empty($reqBody->relatedUserRecordId)) {
    $queryFetchPatients = "SELECT * FROM patients WHERE relatedUserRecordId='$reqBody->relatedUserRecordId' ORDER BY recordCreationTime;";
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