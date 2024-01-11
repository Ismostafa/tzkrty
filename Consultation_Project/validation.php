<?php
// session_start();
$name = $_POST['name'];
$Lname = $_POST['Lname'];
$password = $_POST['password'];
$email = $_POST['email'];
$city = $_POST['city'];
$username = $_POST['username'];
$birthdate= $_POST['date'];

$birthdate_timestamp = strtotime($birthdate);

// Get today's date and convert it to a Unix timestamp
$today_timestamp = strtotime(date('Y-m-d'));

// Validate birthdate: must be before today
if ($birthdate_timestamp >= $today_timestamp) {
    echo json_encode(['valid' => false, 'error' => 'Birthdate must be before today']);
    exit();
}
// Validate username: only letters and numbers allowed
if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    echo json_encode(['valid' => false, 'error' => 'Only letters and numbers allowed in username']);
    exit();
}
// Validate name: only letters allowed
if (!preg_match("/^[a-zA-Z]*$/", $name)) {
    echo json_encode(['valid' => false, 'error' => 'Only letters allowed in name']);
    exit();
}

// Validate last name: only letters allowed
if (!preg_match("/^[a-zA-Z]*$/", $Lname)) {
    echo json_encode(['valid' => false, 'error' => 'Only letters allowed in last name']);
    exit();
}

// Validate password: must be at least 8 characters
if (strlen($password) < 8) {
    echo json_encode(['valid' => false, 'error' => 'Password must be at least 8 characters']);
    exit();
}

// Validate email
if ($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['valid' => false, 'error' => 'Invalid email format']);
    exit();
}

// Validate city: only letters allowed
if (!preg_match("/^[a-zA-Z]*$/", $city)) {
    echo json_encode(['valid' => false, 'error' => 'Only letters allowed in city']);
    exit();
}

echo json_encode(['valid' => true]);
exit();
