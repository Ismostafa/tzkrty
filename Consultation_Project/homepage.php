<!-- <!doctype html> -->

<?php
session_start();

if (isset($_SESSION['error_message'])) : ?>
    <div class="alert alert-danger" id="error-message" style="margin: 0; padding: 0;">
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
    <script>
        $(document).ready(function() {
            $(".btn.btn-success").click(function() {
                let id = $(this).attr('id').replace('bookT', ''); // Get the id of the clicked button
                let alertId = '#myAlert' + id; // Get the id of the corresponding alert
                if ($(alertId).is(':visible'))
                    $(alertId).hide();
                else
                    $(alertId).show();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#error-message').fadeOut('slow');
            }, 4000);
        });
        $(document).ready(function() {
            setTimeout(function() {
                $('#Success_message').fadeOut('slow');
            }, 4000);
        });
        document.getElementById('PassChange').addEventListener('submit', function(event) {
            event.preventDefault();
            var username = document.getElementById('username').value;
            var currentPassword = document.getElementById('currentPassword').value;
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            var formData = new FormData();
            formData.append('username', username);
            formData.append('currentPassword', currentPassword);
            fetch('UpdatePassword.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        document.getElementById('PassChange').submit();
                    } else {
                        // Display the error message
                        var errorMessages = document.getElementById('errorMessages');
                        errorMessages.innerText = data.error;
                        errorMessages.classList.remove('d-none');
                        setTimeout(function() {
                            $('#errorMessages').fadeOut('slow', function() {
                                // Reset the alert to its original state
                                $(this).show().css({
                                    opacity: 1
                                }).addClass('d-none');
                            });
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });


        });
    </script>
    <style>
        a.nav-item.nav-link.active:hover {
            text-decoration: underline;
        }
    </style>
</head>
<!--when clicking on book ticket this what appears-->
<div class="modal" id="ticket">
    <div class="modal-dialog">
        <form id=signinForm action="signin.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sign In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" placeholder="Enter your username" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" placeholder="Enter your password" required class="form-control">
                    </div>
                    <div id="errorMessages" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Sign in</button>
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#changePassword">Forgot Password?</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal" id="changePassword">
    <div class="modal-dialog">
        <form id="PassChange" action="UpdatePassword.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" placeholder="Enter your username" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="Email1">Email:</label>
                        <input type="email" name="email" id="email" placeholder="Enter your email" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password:</label>
                        <input type="password" name="newPassword" id="newPassword" placeholder="Enter your new password" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password:</label>
                        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm your new password" required class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                        <a href="homepage.php" class="nav-item nav-link active" id="a1" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">Home</a>
                        <!-- active is for the current page-->
                        <a href="register.html" class="nav-item nav-link active" id="a2" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">
                            <img src="register.png" width="20" class="img-fluid my-auto">Register</a>
                        <!-- <a href="#" class="nav-item nav-link disabled" tabindex="-1">Reports</a> -->
                        <a href="#ticket" class="nav-item nav-link active" data-bs-toggle="modal" data-bs-target="#ticket" id="a3" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif"> <img src="img.png" width="15" class="img-fluid my-auto">Sign In</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <div style="display: flex; align-items: center; margin-left:12.5%; margin-top: 2%;">
        <img src="ball.svg" alt="match" width="80" class="img-fluid my-auto" style="display: inline;">
        <p id="header1" class="fs-4 fw-bold" style="margin-left: 1%;">Matches</p>
    </div>
    <br>
    <div id="matches">
        <?php
        // Database connection details
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
                echo '<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">'; // This div will contain the button and the badge, and will adjust its size based on the screen size
                echo '<a href="#" class="btn btn-success img-fluid"id="bookT' . $result["match_id"] . '" style="width: 100%;">Book Ticket</a>'; // The button will take up the full width of its container
                echo '<div class="badge bg-success text-center" style="width: 75%; margin-top: 10px; margin-left: 12.5%">Available</div>'; // The badge will take up the full width of its container and will be centered
                echo '</div>';
                echo '</div>';
                echo '<div id="myAlert' . $result["match_id"] . '" class="alert alert-success collapse" style="margin-top: 15px; margin-bottom: 15px;">';
                echo '<strong>You Must Have An Account!</strong> Please Sign In First.';
                echo '</div>';
                echo '</span>';
                echo '</div>';
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