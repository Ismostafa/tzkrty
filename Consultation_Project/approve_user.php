<?php
// approve_user.php
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
    // Query the database to get the current role of the user
    $sql = "SELECT Role FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $sql, $params);

    // if($params==" ")
    // exit;
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt);
    $role = $row['Role'];

    // If the role is 'Fan', set the UserType to 3. If the role is 'Manager', set the UserType to 2.
    $userType = ($role == 'Fan') ? 3 : 2;

    // Update the UserType in the database
    $sql = "UPDATE Users SET UserType = ? WHERE Username = ?";
    $params = array($userType, $username);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Store success message in session
    $_SESSION['message'] = "User {$username} has been successfully approved as a {$role}.";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

    header('Location: admin.php'); // Redirect back to admin page
    exit();
}
?>