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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Create Match</title>
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

        select,
        input {
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
    <script>
        function validateForm() {
            var datetimeInput = document.getElementById('datetime');
            var selectedDatetime = new Date(datetimeInput.value);

            // Get the current date and time
            var currentDatetime = new Date();
            // alert('Please select a date and time in the future.');
            // Check if the selected date and time are in the future
            if (selectedDatetime <= currentDatetime) {
                alert('Please select a date and time in the future.');
                return false; // Prevent form submission
            } else {
                return true; // Allow form submission
            }
        }
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
    </script>
</head>

<body>
    <h2>Create Match</h2>

    <form action="create_match.php" method="post" onsubmit="return validateForm()">
        <label for="home_team">Home Team:</label>
        <select name="home_team" id="home_team">
            <?php
            // PHP code to fetch teams from the database
            $servername = "HASSAN\SQLEXPRESS";
            $username = "";
            $password = "";
            $dbname = "Consultation";
            try {
                $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT * FROM Teams");
                $stmt->execute();
                $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($teams)) {
                    foreach ($teams as $team) {
                        echo "<option value='{$team['TeamID']}'>{$team['TeamName']}</option>";
                    }
                } else {
                    echo "<option value=''>No teams found</option>";
                }
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
            ?>
        </select>

        <label for="away_team">Away Team:</label>
        <select name="away_team" id="away_team">

            <?php
            if (!empty($teams)) {
                // Assuming the selected home team is sent via POST
                $selectedHomeTeam = isset($_POST['home_team']) ? $_POST['home_team'] : '';

                foreach ($teams as $team) {
                    // Check if the team is not the same as the selected home team
                    if ($team['TeamID'] !== $selectedHomeTeam) {
                        echo "<option value='{$team['TeamID']}'>{$team['TeamName']}</option>";
                    }
                }
            } else {
                echo "<option value=''>No teams found</option>";
            }
            ?>

        </select>

        <label for="venue">Venue:</label>
        <select name="venue" id="venue">
            <!-- Similar PHP code to fetch venues from the database -->
            <?php
            // PHP code to fetch venues from the database
            $servername = "HASSAN\SQLEXPRESS";
            $username = "";
            $password = "";
            $dbname = "Consultation";

            try {
                $conn = new PDO("sqlsrv:Server=$servername;Database=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT * FROM Venues");
                $stmt->execute();
                $venues = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($venues)) {
                    foreach ($venues as $venue) {
                        echo "<option value='{$venue['VenueID']}'>{$venue['VenueName']}</option>";
                    }
                } else {
                    echo "<option value=''>No venues found</option>";
                }
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
            ?>



        </select>

        <label for="main_referee">Main Referee:</label>
        <input type="text" name="main_referee" id="main_referee" required>

        <label for="linesman1">Linesman 1:</label>
        <input type="text" name="linesman1" id="linesman1" required>

        <label for="linesman2">Linesman 2:</label>
        <input type="text" name="linesman2" id="linesman2" required>

        <label for="datetime">Date and Time:</label>
        <input type="datetime-local" name="datetime" id="datetime" required>

        <input type="submit" value="Create Match">
        <button type="button" style="width:100%;   background-color: #008B8B;
            color: #fff;
            cursor: pointer;" onclick="window.location.href='manger_home.html'">Return</button>
    </form>
</body>

</html>