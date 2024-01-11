
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection parameters
$serverName = "HASSAN\SQLEXPRESS";
$connectionOptions = array(
    "Database" => "Consultation",
    "Uid" => "",
    "PWD" => ""
);

// Create connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Get the seat number and match ID from the POST request
$SeatNumber = $_POST['SeatNumber'];
$MatchID = $_POST['MatchID'];

// Update the status in the database
$sql = "UPDATE SeatStatus SET Status = 1 WHERE SeatNumber = ? AND MatchID = ?";

$params = array($SeatNumber, $MatchID);

$stmt = sqlsrv_prepare($conn, $sql, $params);

if (sqlsrv_execute($stmt) === true) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . print_r(sqlsrv_errors(), true);
}

sqlsrv_close($conn);
?>