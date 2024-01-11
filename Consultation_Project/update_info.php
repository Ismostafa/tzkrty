<?php
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Retrieve the username from the session variable
}
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the new password from the form data
        $new_password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $city = $_POST['city'];
        $address = $_POST['address'];
    }

$serverName = "HASSAN\SQLEXPRESS";
$database = "Consultation";
$uid = "";
$pass = "";

$connectionOptions = array(
    "Database" => "Consultation",
    "Uid" => "",
    "PWD" => ""
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
if (isset($new_password)) {
    // Prepare an SQL UPDATE statement
    $sql = "UPDATE Users SET Password = ?, FirstName = ?, LastName = ?, City = ?, Address = ? WHERE Username = ?";

    // The parameters for the SQL statement
    $params = array($new_password, $firstname, $lastname, $city, $address, $username);

    // Execute the SQL statement
    $stmt = sqlsrv_query($conn, $sql, $params);

    // Check if the SQL statement was executed successfully
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    } else {
        $_SESSION['Success_message'] = "Information updated successfully";
        header('Location: Edit_info.php');
        exit;
    }
}
// $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>