<!-- <!doctype html> -->
<?php
    session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username']; // Retrieve the username from the session variable
}
    $serverName="HASSAN\SQLEXPRESS";
    $database="Consultation";
    $uid="";
    $pass="";

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

    $sql = "SELECT * FROM Users WHERE Username= ?"; // Assuming you have a UserID
    $params = array($username ); // Assuming you have a session variable for UserID
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
?>

<?php
// session_start();
if (isset($_SESSION['error_message'])) : ?>
    <div class="alert alert-danger" id="error-message"style="margin: 0; padding: 0;">
        <?php
        error_log($_SESSION['error_message']);
        echo $_SESSION['error_message'];
        ob_flush();
        unset($_SESSION['error_message']);
        ?>
    </div>
<?php endif; ?>
<?php
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
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--makes the page scaled correctly according to device-->
    <title>EFA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        a.nav-item.nav-link.active:hover {
            text-decoration: underline;
        }
    </style>
</head>
<!--when clicking on book ticket this what appears-->
<body style="background-color: #eee;">
    <div> <!--navigation bar-->
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:	#008B8B;">
            <!--navbar-dark is for the text color-->
            <div class="container-fluid">
                <a class="navbar-brand" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif"><img src="egy.png" width="65" class="img-fluid my-auto">Egyptian Premier League </a>
                <!--The Webpage name / href=# to scroll to the top-->
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <!--navbarCollapse is for the navbar to be collapsed when the screen is small-->
                    <div class="navbar-nav ms-auto">
                        <a href="fan.php" class="nav-item nav-link active" id="a1" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">Home</a>
                        <a href="Edit_info.php" class="nav-item nav-link active" id="a2" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">Profile</a>
                        <a href="Cancel_Reservations.php" class="nav-item nav-link active" id="a4" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">Cancel Reservations</a>
                        <a href="homepage.php" class="nav-item nav-link active" id="a3" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">Sign out</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div style="display: flex; align-items: center; margin-left:12.5%; margin-top: 2%;">
        <img src="ball.svg" alt="match" width="80" class="img-fluid my-auto" style="display: inline;" >
        <p id="header1" class="fs-4 fw-bold" style= "margin-left: 1%;">Matches</p>
    </div>
    <br>
    <div id="matches">
        <?php
        // Database connection details
        // session_start();
        $servername = "HASSAN\SQLEXPRESS";
        $dbname = "Consultation";
        // Connection options
        $connectionOptions = array(
            "Database" => $dbname,
            "Uid" => "",
            "PWD" => ""
        );

        // Establishes the connection
        $conn = sqlsrv_connect($servername, $connectionOptions);

        // Check connection
        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // SQL query to select all matches
        $sql = "
        SELECT 
            M.MatchID as match_id,
            M.DateTime as match_date,
            H.TeamName as home_team_name, 
            H.TeamLogo as home_team_logo, 
            A.TeamName as away_team_name, 
            A.TeamLogo as away_team_logo,
            V.VenueName as venue_name,
            V.OtherDetails as venue_details,
            M.MainReferee as main_referee,
            M.Linesman1 as linesman1,
            M.Linesman2 as linesman2
        FROM 
            Matches M
        JOIN 
            Teams H ON M.HomeTeamID = H.TeamID
        JOIN 
            Teams A ON M.AwayTeamID = A.TeamID
        JOIN
            Venues V ON M.VenueID = V.VenueID
        WHERE 
            M.DateTime BETWEEN GETDATE() AND DATEADD(day, 100, GETDATE())
        ORDER BY 
            M.DateTime ASC
        ";
        $getResults = sqlsrv_query($conn, $sql);

        if ($getResults == FALSE) {
            die(print_r(sqlsrv_errors(), true));
        }
        $result = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC);
        $_SESSION['match_ids'] = array();
        $match_ids = array();
        $count=0;
        if ($result > 0) {
            // Output data for each match
            do {
                echo '<div class="card w-75 img-fluid" style="margin-left:12.5%;margin-top: 2%; font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif;">';
                echo '<span class="card-body" style="border-style:groove; border-color:bisque">';
                echo '<img src="' . $result["home_team_logo"] . '" width="80" class="img-fluid my-auto" style="display: inline;">';
                echo '<h5 class="card-title" style="font-weight:bold; display: inline;">' . $result["home_team_name"] . ' vs ';
                echo '<img src="' . $result["away_team_logo"] . '" width="80" class="img-fluid my-auto" style="display: inline;">';
                echo $result["away_team_name"] . '</h5>';
                echo '<span class="container-fluid" style="margin-left:2% ">';
                echo '<img src="stadium.png" width="70" class="img-fluid my-auto" style="display: inline;">';
                echo '<h6 class="card-text" style="display: inline;font-weight:bold;">' . $result["venue_name"] . ', ' . $result["venue_details"] . '</h6>';
                echo '<div id="date" style="display: inline;margin-left:2%; font-weight:bold;">';
                echo '<img src="calendar.png" width="70" class="img-fluid my-auto">';
                echo ' Date : ' . $result["match_date"]->format('Y-m-d H:i:s');
                echo '<img src="referee.jpg" width="70" class="img-fluid my-auto" style="margin-left:2%;">';
                echo ' Main Referee: ' . $result["main_referee"];
                echo '</div>';
                echo '<div id="linesmen" style=" font-weight:bold;">';
                echo '<img src="offside.webp" width="70" class="img-fluid my-auto">';
                echo ' Linesman 1: ' . $result["linesman1"];
                echo '<img src="offside.webp" width="70" class="img-fluid my-auto" style="margin-left:2%;">';
                echo ' Linesman 2: ' . $result["linesman2"];
                echo '</div>';
                echo '<div class="row">';
                echo '<div class="col-lg-10 col-md-9 col-sm-8 col-xs-6"></div>'; // This div will take up the remaining space on large, medium, small, and extra small screens
                echo '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">'; 
                $_SESSION['match_ids'][$count] = $result["match_id"];
                $match_ids[$count] = $result["match_id"];
                echo '<a href="reservations.php?match_id=' . $match_ids[$count] . '" class="btn btn-success img-fluid" id="bookT' . $match_ids[$count] . '" style="width: 100%;">Book Ticket</a>';
                echo '<div class="badge bg-success text-center" style="width: 75%; margin-top: 10px; margin-left: 12.5%">Available</div>'; // The badge will take up the full width of its container and will be centered
                echo '</div>';
                echo '</div>';
                echo '</span>';
                echo '</div>';
                $count++;
                // echo '</div>';
            } while ($result = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC));
        } else {
            echo "No matches found";
        }
        sqlsrv_free_stmt($getResults);
        // echo '<div style="background-color: #; width: 100%; height: 200px;"></div>'; // Add a new div at the end of the webpage with a different color
        ?>
    </div>
    <!-- <div style="background-color:brown; width: 100%; height: 200px;"></div> Add a new div at the end of the webpage with a different color -->
</body>

</html>