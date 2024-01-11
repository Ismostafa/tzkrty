<?php
$serverName = "HASSAN\SQLEXPRESS";
$connectionOptions = array(
    "Database" => "Consultation",
    "Uid" => "",
    "PWD" => ""
);

//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

$MatchID = isset($_POST['MatchID']) ? $_POST['MatchID'] : '';

$sql = "SELECT Status FROM SeatStatus WHERE MatchID = ?";

$params = array($MatchID);

$stmt = sqlsrv_prepare($conn, $sql, $params);

if( !$stmt ) {
    die( print_r( sqlsrv_errors(), true));
}

if( sqlsrv_execute( $stmt ) === false ) {
    die( print_r( sqlsrv_errors(), true));
}

$statusArray = array();

while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    array_push($statusArray, $row['Status']);
}

echo json_encode($statusArray);

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>