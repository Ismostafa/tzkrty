<?php
// check_username.php
$serverName = "HASSAN\SQLEXPRESS";
$connectionOptions = array(
    "Database" => "Consultation",
    "Uid" => "",
    "PWD" => ""
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$username = $_GET['username'];

$sql = "SELECT * FROM Users WHERE Username = ?";
$params = array($username);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_fetch_array($stmt) !== null) {
    echo json_encode(array('exists' => true));
} else {
    echo json_encode(array('exists' => false));
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>