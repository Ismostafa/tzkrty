<?php
$servername = "HASSAN\SQLEXPRESS";
$username = ""; // <-- Add your database username here
$password = ""; // <-- Add your database password here
$dbname = "Consultation";

$conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userID = $_POST['user_id'];

    try {
        // Fetch user reservations
        $stmt = $conn->prepare("SELECT * FROM Reservations WHERE UserID = ?");
        $stmt->execute([$userID]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }

    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #dc3545;
            color: #fff;
            cursor: pointer;
        }

        .error {
            color: #dc3545;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Your Reservations</h2>

    <?php
    if (isset($error)) {
        echo "<div class='error'>$error</div>";
    }

    if (isset($reservations)) {
        if (count($reservations) > 0) {
            echo "<form action='' method='post'>";
            echo "<label for='reservation'>Select Reservation:</label>";
            echo "<select name='reservation' id='reservation'>";
            foreach ($reservations as $reservation) {
                echo "<option value='{$reservation['ReservationID']}'>
                        {$reservation['MatchID']} - {$reservation['ReservationDateTime']}
                      </option>";
            }
            echo "</select>";
            echo "<input type='submit' value='Cancel Reservation'>";
            echo "</form>";
        } else {
            echo "<div>No reservations found.</div>";
        }
    }
    ?>
</body>
</html>
