<?php
// signin.php
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
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE Username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    //check if the username is exist
    if (!sqlsrv_has_rows($stmt)) {
        //redirect to home page if the username is not exist

        $_SESSION['error_message'] = "Incorrect username or password.";
        header('Location: homepage.php');
        exit();
    }
    //check if the password is correct
    $sql = "SELECT * FROM Users WHERE Username = ? AND Password = ?";
    $params = array($username, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if (!sqlsrv_has_rows($stmt)){
        //showing the alert message if the password is wrong in home page.html
        $_SESSION['error_message'] = "Incorrect username or password.";
        header('Location: homepage.php');
       //echo "<script>alert('Password is wrong!')</script>";
        exit();
    }

    $user = sqlsrv_fetch_array($stmt);
    $_SESSION['user'] = $user;
    // Check user type and redirect accordingly
    if ($user['Role'] == 'Admin') {
       // header('Location: home page.html');
        header('Location: admin.php'); // Redirect to admin page
        exit();
    }
    else if ($user['Role'] == 'Manager' && $user['UserType'] == 2) {
        header('Location: manger_home.html'); // Redirect to manager page
        exit();
    }
    else if ($user['Role'] == 'Fan' && $user['UserType'] == 3) 
    {
        $_SESSION['username'] = $username;
        header('Location: Fan.php'); // Redirect to user page
        exit();
    }
    else
    {
        $_SESSION['error_message'] = "Your Account is not approved yet.";
        header('Location: homepage.php'); // Redirect to home page
        exit();
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>