<?php
$username = $_POST['username'];
$MatchID = $_POST['MatchID'];
$CreditCardNumber = $_POST['creditCardNumber'];
$PINNumber = $_POST['cvv'];
echo "<script>console.log('arraySelected: " . $_POST['arraySelected'] . "');</script>";
$arraySelected = json_decode($_POST['arraySelected']);

// Connect to the database
$serverName = "HASSAN\SQLEXPRESS";
$connectionOptions = array(
    "Database" => "Consultation",
    "Uid" => "",
    "PWD" => ""
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT UserID FROM Users WHERE Username = ?";
$params = array($username);
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$UserID = $row['UserID'];

$sql = "INSERT INTO Reservations (MatchID, UserID, CreditCardNumber, PINNumber, ReservationDateTime, Status) VALUES (?, ?, ?, ?, ?, ?)";
$params = array($MatchID, $UserID, $CreditCardNumber, $PINNumber, date('Y-m-d H:i:s'), 'Reserved');

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$sql = "SELECT SCOPE_IDENTITY() as ReservationID";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$ReservationID = $row['ReservationID'];

echo" Count: " . count($arraySelected) . "'\n'";
for ($i = 0; $i < count($arraySelected); $i++) {
    $SeatNumber = $arraySelected[$i];
    // var_dump($ReservationID);
    // var_dump($SeatNumber);
    echo "SeatNumber: " . $SeatNumber . "'\n'";
    $sql = "INSERT INTO ReservedSeats (ReservationID, SeatNumberReserved) VALUES (?, ?)";
    $params = array($ReservationID, $SeatNumber);

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    echo "Reservation and seat reservations added successfully.";
}

?>

<!-- echo "Reservation and seat reservations added successfully.";
?> -->