<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Match Details</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
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
    <h2 style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;">Match Details</h2>

    <?php
    $servername = "HASSAN\SQLEXPRESS";
    $username = "";
    $password = "";
    $dbname = "Consultation";

    try {
        $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT Matches.DateTime, HomeTeam.TeamName AS HomeTeamName, AwayTeam.TeamName AS AwayTeamName, Venues.VenueName, Matches.MainReferee, Matches.Linesman1, Matches.Linesman2
                               FROM Matches
                               INNER JOIN Teams AS HomeTeam ON Matches.HomeTeamID = HomeTeam.TeamID
                               INNER JOIN Teams AS AwayTeam ON Matches.AwayTeamID = AwayTeam.TeamID
                               INNER JOIN Venues ON Matches.VenueID = Venues.VenueID
                               ORDER BY Matches.DateTime ASC");

        $stmt->execute();
        $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($matches)) {
            echo "<table>";
            echo "<tr><th>Date and Time</th><th>Home Team</th><th>Away Team</th><th>Venue</th><th>Main Referee</th><th>Linesman 1</th><th>Linesman 2</th></tr>";

            foreach ($matches as $match) {
                echo "<tr>";
                echo "<td>{$match['DateTime']}</td>";
                echo "<td>{$match['HomeTeamName']}</td>";
                echo "<td>{$match['AwayTeamName']}</td>";
                echo "<td>{$match['VenueName']}</td>";
                echo "<td>{$match['MainReferee']}</td>";
                echo "<td>{$match['Linesman1']}</td>";
                echo "<td>{$match['Linesman2']}</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No matches found</p>";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    $conn = null;
    ?>
</body>

</html>