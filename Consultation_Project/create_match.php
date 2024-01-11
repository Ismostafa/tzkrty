<?php
session_start();
$servername = "HASSAN\SQLEXPRESS";
$username = "";
$password = "";
$dbname = "Consultation";

try {
    $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $homeTeam = $_POST['home_team'];
        $awayTeam = $_POST['away_team'];
        $venue = $_POST['venue'];
        $mainReferee = $_POST['main_referee'];
        $linesman1 = $_POST['linesman1'];
        $linesman2 = $_POST['linesman2'];
        $userDatetime = strtotime($_POST['datetime']);
        $datetime = date('Y-m-d H:i:s', $userDatetime);
        if ($homeTeam == $awayTeam) {
            $_SESSION['error_message'] = "Can't select same team twice";
            header('Location: create_matchF.php');
            exit;
        }
        // Check if the selected teams have a match at the specified date and time
        $stmt = $conn->prepare("SELECT * FROM Matches WHERE (HomeTeamID = ? OR AwayTeamID = ?) AND DateTime = ?");
        $stmt->execute([$homeTeam, $awayTeam, $datetime]);
        $existingMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($existingMatches)) {
            $_SESSION['error_message'] = "The selected teams already have a match scheduled at the specified date and time.";
            header('Location: create_matchF.php');
            exit;
        }

        // Check if the selected venue has a match at the specified date and time
        $stmt = $conn->prepare("SELECT * FROM Matches WHERE VenueID = ? AND DateTime = ?");
        $stmt->execute([$venue, $datetime]);
        $venueMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($venueMatches)) {
            $_SESSION['error_message'] = "The selected venue is already booked for a match at the specified date and time.";
            header('Location: create_matchF.php');
            exit;
        }

        // Check if the selected venue has a match within 3 hours before or after the specified date and time
        $stmt = $conn->prepare("SELECT * FROM Matches WHERE VenueID = ? AND DateTime BETWEEN DATEADD(HOUR, -3, ?) AND DATEADD(HOUR, 3, ?)");
        $stmt->execute([$venue, $datetime, $datetime]);
        $venueMatchesWithin3Hours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($venueMatchesWithin3Hours)) {
            $_SESSION['error_message'] = "The selected venue is already booked for a match within 3 hours of the specified date and time.";
            header('Location: create_matchF.php');
            exit;
        }

        // Insert data into Matches table
        $stmt = $conn->prepare("INSERT INTO Matches (HomeTeamID, AwayTeamID, VenueID, MainReferee, Linesman1, Linesman2, DateTime) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$homeTeam, $awayTeam, $venue, $mainReferee, $linesman1, $linesman2, $datetime]);
        $lastInsertedID = $conn->lastInsertId();
        $matchID = $lastInsertedID; // Replace with your actual MatchID

        // Create 80 seat statuses with status set to 0 (Vacant)
        for ($seatNumber = 1; $seatNumber <= 80; $seatNumber++) {
            $stmt = $conn->prepare("INSERT INTO SeatStatus (MatchID, Status) VALUES (?, 0)");
            $stmt->execute([$matchID]);
        }
        $_SESSION['Success_message'] = "Match Created Successfully";
        header('Location: create_matchF.php');
        exit;
    }

    $conn = null;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
