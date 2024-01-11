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
<!-- <!DOCTYPE html> -->
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--makes the page scaled correctly according to device-->
    <title>Venues</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        a.nav-item.nav-link.active:hover {
            text-decoration: underline;
        }

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
    <h2>Add Venue</h2>

    <form action="add_venue.php" method="post">
        <label for="venue_name">Venue Name:</label>
        <input type="text" name="venue_name" id="venue_name" required>

        <label for="seat_capacity">Seat Capacity:</label>
        <input type="number" name="seat_capacity" id="seat_capacity" required>

        <label for="other_details">Other Details:</label>
        <input type="text" name="other_details" id="other_details">

        <input type="submit" value="Add Venue">
        <button onclick="window.location.href='manger_home.html'" style=" background-color: #008B8B;
    color: #fff;
    cursor: pointer; width:100%;">Return</button>

    </form>
</body>

</html>