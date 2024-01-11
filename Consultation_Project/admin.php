<?php
session_start();
?>

<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--makes the page scaled correctly according to device-->
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $.get('get_pending_users.php', function(data) {
                console.log(data);
                var users = JSON.parse(data);
                users.forEach(function(user) {
                    $('#username-approve').append(new Option(user.Username, user.Username));
                });
            });
        });
        $(document).ready(function() {
            var currentUsername = '<?php echo $_SESSION['user']['Username']; ?>'; // Get current user's username from PHP session
            $.get('get_users.php', function(data) {
                var users = JSON.parse(data);
                users.forEach(function(user) {
                    if (user.Username !== currentUsername) { // Exclude the current user's username
                        $('#username-delete').append(new Option(user.Username, user.Username));
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#show-delete-details').click(function() {
                var username = $('#username-delete').val();
                $.get('get_user_details.php', {
                    username: username
                }, function(data) {
                    var user = JSON.parse(data);
                    $('#delete-user-details tbody').html('<tr><td>' + user.Username + '</td><td>' + user.FirstName + '</td><td>' + user.LastName + '</td><td>' + user.Role + '</td></tr>');
                });
            });
        });
        $(document).ready(function() {
            $('#show-details').click(function() {
                var username = $('#username-approve').val();
                $.get('get_user_details.php', {
                    username: username
                }, function(data) {
                    var user = JSON.parse(data);
                    $('#user-details tbody').html('<tr><td>' + user.Username + '</td><td>' + user.FirstName + '</td><td>' + user.LastName + '</td><td>' + user.Role + '</td></tr>');
                });
            });
        });
        setTimeout(function() {
            $('.alert-success').fadeOut('slow');
        }, 1000);
        // document.getElementById('myForm').addEventListener('submit', function(event) {
        //     var selectValue = document.getElementById('mySelect').value;
        //     if (selectValue === '') {
        //         event.preventDefault();
        //         alert('Please select an option');
        //     }
        // });
    </script>

</head>

<body style="background-color: #eee;">
    <div> <!-- Navigation bar -->
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #008B8B;">
            <div class="container-fluid">
                <a class="navbar-brand" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">
                    <img src="egy.png" width="65" class="img-fluid my-auto">Egyptian Premier League </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <!-- Include links to other sections/pages -->
                        <a href="#" class="nav-item nav-link active" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">
                            Admin Panel</a>
                        <a href="homepage.php" class="nav-item nav-link active" style="font-family: Century Gothic, CenturyGothic, AppleGothic, sans-serif">
                            Sign Out</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Admin Panel Section -->
    <div class="container mt-4">
        <h2>Welcome, Administrator</h2>
        <div class="row">
            <!-- Add administrator functionalities -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Pending User Approvals</h5>
                        <p class="card-text">View and approve pending user accounts.</p>
                        <form id="myForm" action="approve_user.php" method="POST">
                            <select name="username" id="username-approve" class="form-control">
                                <!-- Options will be populated with JavaScript -->
                            </select>
                            <button type="button" id="show-details" class="btn btn-info mt-2">Show Details</button>
                            <button type="submit" class="btn btn-primary mt-2">Approve User</button>
                        </form>
                        <table class="table mt-3" id="user-details">
                            <thead>
                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- User details will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
            <div class="col-md-6">
                <!-- ... (rest of your code) -->

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Remove User</h5>
                        <p class="card-text">Remove an existing user account.</p>
                        <form action="delete_user.php" method="POST">
                            <select name="username" id="username-delete" class="form-control">
                                <!-- Options will be populated with JavaScript -->
                            </select>
                            <button type="button" id="show-delete-details" class="btn btn-info mt-2">Show Details</button>
                            <button type="submit" class="btn btn-danger mt-2">Delete User</button>
                        </form>
                        <table class="table mt-3" id="delete-user-details">
                            <thead>
                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- User details will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>


    <?php if (isset($_SESSION['message'])) : ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>
</body>

</html>