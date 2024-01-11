<?php
// Database connection details
$servername = "HASSAN\SQLEXPRESS";
$username = "hassan";
$password = "1234"; 
$dbname = "Consultation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select all matches
$sql = "SELECT * FROM Matches WHERE match_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY) ORDER BY match_date ASC, match_time ASC";$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data for each match
    while($row = $result->fetch_assoc()) {
        echo '<div class="card w-75 img-fluid" style="margin-left:12.5%;margin-top: 2%;">';
        echo '<span class="card-body" style="border-style:groove; border-color:bisque">';
        echo '<img src="' . $row["team1_logo"] . '" width="80" class="img-fluid my-auto" style="display: inline;">';
        echo '<h5 class="card-title" style="font-weight:bold; display: inline;">' . $row["match_title"] . '</h5>';
        echo '<img src="' . $row["team2_logo"] . '" width="80" class="img-fluid my-auto" style="display: inline;">';
        echo '<span class="container-fluid" style="margin-left:2% ">';
        echo '<img src="stadium.png" width="70" class="img-fluid my-auto" style="display: inline;">';
        echo '<h6 class="card-text" style="display: inline;">' . $row["stadium_name"] . '</h6>';
        echo '<h6 class="card-text" style="display: inline;"> ,' . $row["city"] . '</h6>';
        echo '<div id="date" style="display: inline;margin-left:2%; font-weight:bold;">';
        echo '<img src="calendar.png" width="70" class="img-fluid my-auto">';
        echo $row["match_date"] . ', Time : ' . $row["match_time"];
        echo '</div>';
        echo '<a href="#" class="btn btn-success img-fluid " id="bookT" style="margin-left: 90% ;margin-top:-7%; width: 10%;">Book Ticket</a>';
        echo '<div id="myAlert" class="alert alert-success collapse">';
        echo '<strong>You Must Have An Account!</strong> Please Sign In First.';
        echo '</div>';
        echo '</span>';
        echo '<div class="info2">';
        echo '<div class="badge bg-success" style="margin-left: 92% ; width: 6%;">Available</div>';
        echo '<p id="matchinfo" class="card-text" style="margin-top:-2%;">' . $row["match_info"] . '</p>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "No matches found";
}
$conn->close();
?>