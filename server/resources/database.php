<?php

include 'commons.php';
function OpenCon()
{
    $dbHost = "localhost";
    $dbUser = "root";
    $dbPass = "";
    $db = "EHCMS";

    // $dbHost = "b5fqyifh1mj4mi0wuazf-mysql.services.clever-cloud.com";
    // $dbUser = "uowvph8wlo5qjiuh";
    // $dbPass = "1AeEnrwhVUCpfEP2LT0S";
    // $db = "b5fqyifh1mj4mi0wuazf";

    $response = new StdClass();
    try {
        $conn = new mysqli($dbHost, $dbUser, $dbPass, $db);
        $response->connection = $conn;
        setResponseStatus($response, true, "Connection Success");
    }
    catch (Exception $e) {
        setResponseStatus($response, false, "Connection Failed");
        setResponseError($response, $e->getCode(), $e->getMessage());
    }
    return $response;
}
function closeCon($connection)
{
    $connection->close();
}

?>
