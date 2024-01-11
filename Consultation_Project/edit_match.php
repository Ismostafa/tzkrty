<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Match</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
            max-width: 400px;
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

        select, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #008B8B;
            color: #fff;
            cursor: pointer;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark " style="background-color:	#008B8B;
        font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;">
        <div class="container-fluid">
            <a class="navbar-brand" aria-disabled="true" style="color: white;">Manager Panel</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="color: white;">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="manger_home.html" style="color: white;">Return</a>
                    </li>
                </ul>
            </div>
    </nav>
    <br>
<h2 style=" font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;">Edit Match</h2>

<?php
$servername = "HASSAN\SQLEXPRESS";
$username = "";
$password = "";
$dbname = "Consultation";

$conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    if (isset($_POST['match_id'])) {
        // Retrieve selected match details
        $selectedMatchID = $_POST['match_id'];

        // Fetch selected match details from the database
        $stmt = $conn->prepare("SELECT * FROM Matches WHERE MatchID = ?");
        $stmt->execute([$selectedMatchID]);
        $selectedMatch = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$selectedMatch) {
            echo "Error: Match not found";
            exit;
        }

        // Display a form with the selected match details for editing
        echo "<form action='' method='post'>";
        echo "<h3 style='font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;'>Edit Match</h3>";

        // Display dropdown lists for home and away teams
        echo "<label for='home_team'>Home Team:</label>";
        echo "<select name='home_team'>";
        displayTeamOptions($conn, $selectedMatch['HomeTeamID']);
        echo "</select><br>";

        echo "<label for='away_team'>Away Team:</label>";
        echo "<select name='away_team'>";
        displayTeamOptions($conn, $selectedMatch['AwayTeamID']);
        echo "</select><br>";

        // Display dropdown list for venue
        echo "<label for='venue'>Venue:</label>";
        echo "<select name='venue'>";
        displayVenueOptions($conn, $selectedMatch['VenueID']);
        echo "</select><br>";

        echo "<label for='main_referee'>Main Referee:</label>";
        echo "<input type='text' name='main_referee' value='{$selectedMatch['MainReferee']}'><br>";

        echo "<label for='linesman1'>Linesman 1:</label>";
        echo "<input type='text' name='linesman1' value='{$selectedMatch['Linesman1']}'><br>";

        echo "<label for='linesman2'>Linesman 2:</label>";
        echo "<input type='text' name='linesman2' value='{$selectedMatch['Linesman2']}'><br>";

        echo "<label for='datetime'>Date and Time:</label>";
        echo "<input type='datetime-local' name='datetime' value='" . date('Y-m-d\TH:i:s', strtotime($selectedMatch['DateTime'])) . "'><br>";

        echo "<input type='hidden' name='match_id' value='{$selectedMatch['MatchID']}'>";
        echo "<input type='submit' value='Save Changes'>";
        echo "</form>";
    } if (
        isset($_POST['home_team']) &&
        isset($_POST['away_team']) &&
        isset($_POST['venue']) &&
        isset($_POST['main_referee']) &&
        isset($_POST['linesman1']) &&
        isset($_POST['linesman2']) &&
        isset($_POST['datetime']) &&
        isset($_POST['match_id'])
    ) {
        // Handle form submission with updated data
        $updatedHomeTeam = $_POST['home_team'];
        $updatedAwayTeam = $_POST['away_team'];
        $updatedVenue = $_POST['venue'];
        $updatedMainReferee = $_POST['main_referee'];
        $updatedLinesman1 = $_POST['linesman1'];
        $updatedLinesman2 = $_POST['linesman2'];
        $updatedDateTime = date('Y-m-d H:i:s', strtotime($_POST['datetime']));
        $updatedMatchID = $_POST['match_id'];
        $stmt = $conn->prepare("SELECT * FROM Matches WHERE (HomeTeamID = ? OR AwayTeamID = ?) AND DateTime = ?");
        $stmt->execute([$updatedHomeTeam, $updatedAwayTeam, $updatedDateTime]);
        $existingMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($existingMatches)) {
             echo"Error: The selected teams already have a match scheduled at the specified date and time";
            exit;
        }
        $stmt = $conn->prepare("SELECT * FROM Matches WHERE VenueID = ? AND DateTime = ?");
        $stmt->execute([$updatedVenue, $updatedDateTime]);
        $venueMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($venueMatches)) {
            echo "Error: The selected venue is already booked for a match at the specified date and time.";
            exit;
        }

        // Check if the selected venue has a match within 3 hours before or after the specified date and time
        $stmt = $conn->prepare("SELECT * FROM Matches WHERE VenueID = ? AND DateTime BETWEEN DATEADD(HOUR, -3, ?) AND DATEADD(HOUR, 3, ?)");
        $stmt->execute([$updatedVenue, $updatedDateTime, $updatedDateTime]);
        $venueMatchesWithin3Hours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($venueMatchesWithin3Hours)) {
            echo"Error: The selected venue is already booked for a match within 3 hours of the specified date and time.";
           exit;}
        // Update match details in the database
        $updateStmt = $conn->prepare("UPDATE Matches SET HomeTeamID = ?, AwayTeamID = ?, VenueID = ?, MainReferee = ?, Linesman1 = ?, Linesman2 = ?, DateTime = ? WHERE MatchID = ?");
        $updateStmt->execute([$updatedHomeTeam, $updatedAwayTeam, $updatedVenue, $updatedMainReferee, $updatedLinesman1, $updatedLinesman2, $updatedDateTime, $updatedMatchID]);

        echo "<script type='text/javascript'>alert('Done')</script>";
    }

}

// Display the dropdown list of matches
echo "<form action='' method='post'>";
echo '<h3 style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;">Select Match to Edit</h3>';
echo "<label for='match_id'>Select Match:</label>";
echo "<select name='match_id'>";
// PHP code to fetch matches from the database
$stmt = $conn->prepare("SELECT * FROM Matches");
$stmt->execute();
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($matches)) {
    foreach ($matches as $match) {
        $homeTeamName = getTeamName($conn, $match['HomeTeamID']);
        $awayTeamName = getTeamName($conn, $match['AwayTeamID']);
        echo "<option value='{$match['MatchID']}'>{$homeTeamName} vs {$awayTeamName} - {$match['DateTime']}</option>";
    }
} else {
    echo "<option value=''>No matches found</option>";
}
echo "</select>";

echo "<input type='submit' value='Edit Match'>";
echo "</form>";

$conn = null;

// Function to display team options in a dropdown list
function displayTeamOptions($conn, $selectedTeamID) {
    $stmt = $conn->prepare("SELECT * FROM Teams");
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($teams)) {
        foreach ($teams as $team) {
            $selected = ($team['TeamID'] == $selectedTeamID) ? 'selected' : '';
            echo "<option value='{$team['TeamID']}' $selected>{$team['TeamName']}</option>";
        }
    } else {
        echo "<option value=''>No teams found</option>";
    }
}

// Function to display venue options in a dropdown list
function displayVenueOptions($conn, $selectedVenueID) {
    $stmt = $conn->prepare("SELECT * FROM Venues");
    $stmt->execute();
    $venues = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($venues)) {
        foreach ($venues as $venue) {
            $selected = ($venue['VenueID'] == $selectedVenueID) ? 'selected' : '';
            echo "<option value='{$venue['VenueID']}' $selected>{$venue['VenueName']}</option>";
        }
    } else {
        echo "<option value=''>No venues found</option>";
    }
}

// Function to get team name by team ID
function getTeamName($conn, $teamID) {
    $stmt = $conn->prepare("SELECT TeamName FROM Teams WHERE TeamID = ?");
    $stmt->execute([$teamID]);
    $team = $stmt->fetch(PDO::FETCH_ASSOC);

    return ($team) ? $team['TeamName'] : 'Unknown Team';
}
?>
</body>
</html>
