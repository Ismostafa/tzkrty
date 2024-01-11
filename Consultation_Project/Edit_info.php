<?php
session_start();
if (isset($_SESSION['Success_message'])) : ?>
    <div class="alert alert-success" id="Success_message" style="margin: 0; padding: 0;">
        <?php
        error_log($_SESSION['Success_message']);
        echo $_SESSION['Success_message'];
        ob_flush();
        unset($_SESSION['Success_message']);
        // header('Location: homepage.php');
        ?>
    </div>
<?php endif; ?>
<?php
// session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Retrieve the username from the session variable
}
$serverName = "HASSAN\SQLEXPRESS";
$database = "Consultation";
$uid = "";
$pass = "";

$connectionOptions = array(
    "Database" => "Consultation",
    "Uid" => $uid,
    "PWD" => $pass
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Check connection
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT * FROM Users WHERE Username = ?"; // Assuming you have a UserID
$params = array($username); // Assuming you have a session variable for UserID
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--makes the page scaled correctly according to device-->
    <title>Edit Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        a.nav-item.nav-link.active:hover {
            text-decoration: underline;
        }

        body {
            font-family: Arial, sans-serif;
        }

        form {
            width: 300px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 20px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        input[type="submit"] {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body style="background-color: #eee;">
    <div> <!--navigation bar-->
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:	#008B8B;">
            <!--navbar-dark is for the text color-->
            <div class="container-fluid">
                <a href="#" class="navbar-brand" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif"><img src="egy.png" width="65" class="img-fluid my-auto">Egyptian Premier League </a>
                <!--The Webpage name / href=# to scroll to the top-->
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="fan.php" class="nav-item nav-link active" id="a1" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">Home</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div class="container d-flex align-items-center justify-content-center" style="height: 100vh;">
        <form id="f1" action="update_info.php" method="post" class="mt-5">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['Username']; ?>" readonly class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required class="form-control">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="form-control">
            </div>
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo $user['FirstName']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo $user['LastName']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <input type="text" id="gender" name="gender" value="<?php echo $user['Gender']; ?>" readonly class="form-control">
            </div>
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo $user['City']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $user['Address']; ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['EmailAddress']; ?>" readonly class="form-control">
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" id="role" name="role" value="<?php echo $user['Role']; ?>" readonly class="form-control">
            </div>
            <div class="form-group">
                <label for="birthdate">Birth Date:</label>
                <?php
                $birthdate = $user['BirthDate'];
                $formatted_birthdate = $birthdate->format('Y-m-d');
                ?>
                <input type="date" id="birthdate" name="birthdate" value="<?php echo $formatted_birthdate; ?>" readonly class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" value="Edit" style="width:75%; margin-left:12.5% ; " class="btn btn-primary">
            </div>
        </form>
        <script>
            document.getElementById('f1').addEventListener('submit', function(event) {
                var password = document.getElementById("password");
                var confirm_password = document.getElementById("confirm_password");
                if (password.value.length < 8) {
                    alert("Password must be at least 8 characters long");
                    event.preventDefault();
                } else if (password.value == "" || confirm_password.value == "") {
                    alert("Please enter a password");
                    event.preventDefault();
                } else if (password.value != confirm_password.value) {
                    alert("Passwords Don't Match");
                    event.preventDefault();
                }
            });
            //     // password.onchange = validatePassword;
            //     // confirm_password.onkeyup = validatePassword;
            //     function validatePassword() {
            //         if (password.value != confirm_password.value) {
            //             confirm_password.setCustomValidity("Passwords Don't Match");
            //         } else {
            //             confirm_password.setCustomValidity('');
            //         }
            //     }
            // });
        </script>
</body>

</html>