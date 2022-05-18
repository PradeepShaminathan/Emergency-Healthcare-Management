<?php

include 'resources/database.php';

$response = OpenCon();
validateRequest($response, 'POST');

$queryFetchHospitals = "SELECT * FROM users WHERE role='Hospital';";

if ($response->success) {
    try {
        $response->hospitals = array();
        $hospitals = $response->connection->query($queryFetchHospitals);
        for ($i = 0; $i < $hospitals->num_rows; $i++) {
            $row = $hospitals->fetch_object();
            $response->hospitals[$i] = $row;
        }
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Error occured while fetching hospitals list");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    finally {
        closeCon($response->connection);
    }
}

unset($response->connection);
echo json_encode($response, JSON_PRETTY_PRINT);

?>