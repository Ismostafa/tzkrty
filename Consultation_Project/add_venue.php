<?php
$servername = "HASSAN\SQLEXPRESS";
$username = ""; // <-- Add your database username here
$password = ""; // <-- Add your database password here
$dbname = "Consultation";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $venueName = $_POST['venue_name'];
    $seatCapacity = $_POST['seat_capacity'];
    $otherDetails = $_POST['other_details'];
    if(!preg_match("/^[a-zA-Z]*$/", $venueName)) {
        $_SESSION['error_message'] = "Only letters allowed in venue name";
        header('Location: add_Venues.php');
        exit;
    }
    if ($seatCapacity <= 0) {
        $_SESSION['error_message'] = "Seat capacity must be a positive number";
        header('Location: add_Venues.php');
        exit;
    }
    else
    {
    try {
        $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
        $stmt = $conn->prepare("SELECT VenueID FROM Venues WHERE VenueName = ?");
        $stmt->execute([$venueName]);
        $existingVenue = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingVenue) {
            // Venue with the same name already exists, handle the error
            $_SESSION['error_message'] =" Venue with the same name already exists. Please choose a different name.";
            header('Location: add_Venues.php');
            exit;
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insert data into Venues table
        $stmt = $conn->prepare("INSERT INTO Venues (VenueName, SeatCapacity, OtherDetails) VALUES (?, ?, ?)");
        $stmt->execute([$venueName, $seatCapacity, $otherDetails]);

        $_SESSION['Success_message'] = "Venue Added Successfully";
        header('Location: add_Venues.php');
        exit;
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    }
    $conn = null;

}
?>