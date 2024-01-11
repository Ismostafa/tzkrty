<?php
$username = $_POST['username'];
$email = $_POST['email'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

// Validate username: only letters and numbers allowed
if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    echo json_encode(['valid' => false, 'error' => 'Only letters and numbers allowed in username']);
    exit();
}
// Validate email
if($email == "")
{
    echo json_encode(['valid' => false, 'error' => 'Email is required']);
    exit();
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['valid' => false, 'error' => 'Invalid email format']);
    exit();
}
// Validate new password: must be at least 8 characters
if (strlen($newPassword) < 8) {
    echo json_encode(['valid' => false, 'error' => 'New password must be at least 8 characters']);
    exit();
}
// Validate confirm password: must be at least 8 characters
if (strlen($confirmPassword) < 8) {
    echo json_encode(['valid' => false, 'error' => 'Confirm password must be at least 8 characters']);
    exit();
}

// Check if new password and confirm password are the same
if ($newPassword !== $confirmPassword) {
    echo json_encode(['valid' => false, 'error' => 'New password and confirm password do not match']);
    exit();
}

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
else {

    //Handle form data and perform database insertion
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Collect form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Prepare SQL query to insert user data
        $sql = "UPDATE Users SET Password = ? WHERE Username = ? AND EmailAddress = ?";

        // Prepare and execute the SQL statement
        $params = array($newPassword, $username, $email);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt !== false) {
            $_SESSION['Success_message'] = "Password updated successfully!";
            header('Location: homepage.php');
            exit();
        }
        else {
            $_SESSION['error_message'] = "Password update failed!";
            header('Location: homepage.php');
            exit();
        }
        // Clean up resources
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
    }
}