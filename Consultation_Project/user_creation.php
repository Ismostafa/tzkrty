<?php
// Establish database connection - You need to modify the connection details accordingly
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
        $password = $_POST['password'];
        $Fname = $_POST['name'];
        $Lname = $_POST['Lname'];
        $gender = $_POST['gender'];
        $date = $_POST['date'];
        $email = $_POST['email'];
        $City = $_POST['City'];
        $address = $_POST['address'];
        $role = $_POST['role'];
        
        //if role is Manager then roletype is 2
        $roletype = 1;
        if ($role == "Manager") {
            $roletype = 4;
        }
        //if role is Fan then roletype is 3 but we use 4 to make it pending
        if ($role == "Fan") {
            $roletype = 4;
        }

        // Prepare SQL query to insert user data (Modify the query as per your table structure)
        $sql = "INSERT INTO Users (Username, Password, FirstName, LastName, Gender, BirthDate, EmailAddress, City, Address, Role,UserType)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

        // Prepare and execute the SQL statement
        $params = array($username, $password, $Fname, $Lname, $gender, $date, $email, $City, $address, $role,$roletype);
        $stmt = sqlsrv_query($conn, $sql, $params);
        if ($stmt !== false) {
            $_SESSION['Success_message'] = "User created successfully! Wait For Admin Approval";
            header('Location: homepage.php');
            exit();
        }
        else {
            //redirect to home page if the username exists
            $_SESSION['error_message'] = "Registration failed!";
            header('Location: register.html');
            exit();
        }
        // Clean up resources
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);

        // if ($stmt === false) {
        //     die(print_r(sqlsrv_errors(), true));
        // } else {
        //     // User creation successful
        //     // Redirect the user to the home page
        //     header('Location: homepage.php');
        //     echo "<script type='text/javascript'>alert('User created successfully! Wait For Admin Approval')</script>";
        //     //creating alert to show the user that the user is created successfully

        //     exit;
        // }

    }
}

?>