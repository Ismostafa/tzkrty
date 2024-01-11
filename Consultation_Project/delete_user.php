<?php
// delete_user.php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    $sql = "DELETE FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Store success message in session
    $_SESSION['message'] = "User {$username} has been successfully deleted.";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    header('Location: admin.php'); // Redirect back to admin page
    exit();
}
?>