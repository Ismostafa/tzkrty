<?php
$servername = "HASSAN\SQLEXPRESS";
$username = ""; // <-- Add your database username here
$password = ""; // <-- Add your database password here
$dbname = "Consultation";

try {
    $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch matches for the dropdown list
    $stmt = $conn->query("SELECT Matches.MatchID, Matches.DateTime, TeamsHome.TeamName AS HomeTeam, TeamsAway.TeamName AS AwayTeam
                          FROM Matches
                          JOIN Teams AS TeamsHome ON Matches.HomeTeamID = TeamsHome.TeamID
                          JOIN Teams AS TeamsAway ON Matches.AwayTeamID = TeamsAway.TeamID");
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the selected match ID
        $selectedMatchID = $_POST['match'];

        // Fetch SeatStatus for the selected match
        $stmt = $conn->prepare("SELECT SeatNumber, Status FROM SeatStatus WHERE MatchID = ?");
        $stmt->execute([$selectedMatchID]);
        $seatStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Seat Status</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 20px;
            text-align: center;
        }

        h2, h3 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #008B8B;
            color: #fff;
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
<h2 style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;">Choose a Match to View Seat Status</h2>

<form action="" method="post">
    <label for="match">Select Match:</label>
    <select name="match" id="match">
        <?php
        foreach ($matches as $match) {
            echo "<option value='{$match['MatchID']}'>{$match['HomeTeam']} vs {$match['AwayTeam']} - {$match['DateTime']}</option>";
        }
        ?>
    </select>
    <input type="submit" value="View Seat Status">
</form>

<?php
if (isset($seatStatus)) {
    echo "<h3 style='font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;'>Seat Status for Match ID: $selectedMatchID</h3>";
    echo "<table>";
    echo "<tr><th>Seat Number</th><th>Status</th></tr>";

    foreach ($seatStatus as $seat) {
        $statusText = ($seat['Status'] == 0) ? 'Free' : 'Reserved';
        if($selectedMatchID == 2)
        {
            $seat['SeatNumber'] =$seat['SeatNumber']-80;
        }
        else{$seat['SeatNumber'] = ($selectedMatchID == 1) ? $seat['SeatNumber'] : $seat['SeatNumber']-80*($selectedMatchID-1);}
       /* $seat['SeatNumber'] = ($selectedMatchID == 1) ? $seat['SeatNumber'] : $seat['SeatNumber']-80*$selectedMatchID;
        if($selectedMatchID == 2)
        {
            $seat['SeatNumber'] =$seat['SeatNumber']-80;
        }*/

     echo "<tr><td style='font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;'>{$seat['SeatNumber']}</td><td style='font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;'>{$statusText}</td></tr>";
    }

    echo "</table>";
}
?>

</body>
</html>
