<?php
session_start();
if (isset($_SESSION['match_id'])) {
    $matchID = $_GET['match_id'];
} else {
    $matchID = ""; // Default value
}

if (isset($_SESSION['UserID'])) {
    $UserdID = $_SESSION['UserID'];
} else {
    $UserID = ""; // Default value
}
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    $username = ""; // Default value
}


$matchID_json = json_encode($matchID);
$UserID_json = json_encode($UserID);
$UserName_json = json_encode($username);
?>


<!DOCTYPE html>
<html>

<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: repeat(8, 90px);
            grid-template-rows: repeat(10, 50px);
            justify-content: center;
            margin-top: 50px;
        }

        .grid-item {
            border: 1px solid rgba(0, 0, 0, 0.8);
            position: relative;
        }

        .grid-item.normal {
            background-color: blue;
        }

        .grid-item.normal:hover:not(.reserved) {
            background-color: purple;
        }

        .grid-item.vip {
            background-color: blue;
        }

        .grid-item.vip:hover:not(.reserved) {
            background-color: orange;
        }

        .grid-item.selected.normal {
            background-color: green;
        }

        .grid-item.selected.vip {
            background-color: greenyellow;
        }

        .grid-item.reserved {
            background-color: red;
        }

        h1 {
            text-align: center;
        }
        #return,
        #reserveButton,
        #resetButton {
            display: block;
            margin: 20px auto;
            background-color: green;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }

        .legend {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .legend div {
            margin: 0 10px;
            display: flex;
            align-items: center;
        }

        .legend div span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        /* .bill {
            position: fixed;
            right: 20px;
            top: 50px;
            border: 1px solid black;
            padding: 20px;
            background-color: white;
        } */
        .bill {
            position: fixed;
            left: 20px;
            /* Move to the left */
            top: 50px;
            border: 3px solid black;
            /* Highlighted border */
            padding: 20px;
            background-color: white;
            font-size: 1.5em;
            /* Larger font */
        }

        body {
            background-image: url('Maksoura.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            /* Could be more or less, depending on screen size */
        }
    </style>
    <script>
        var blockNumber = "A"; // This variable can be set dynamically
        document.title = "Match Seat Selection: Block " + blockNumber;
    </script>
</head>

<body>
    <!-- <h1>Match Seat Selection: Block <span id="blockNumber"></span></h1> -->
    <div id="grid" class="grid-container"></div>
    <button id="reserveButton">Reserve ticket</button>
    <button id="resetButton">Reset</button>
    <button id= "return" onclick="window.location.href='fan.php'">Return</button>
    <div class="legend">
        <div><span style="background-color: red;"></span>Reserved</div>
        <div><span style="background-color: yellow;"></span>VIP Selected</div>
        <!-- <div><span style="background-color: green;"></span>Normal Selected</div> -->
        <div><span style="background-color: blue;"></span>VIP Available</div>
        <!-- <div><span style="background-color: blue;"></span>Normal Available</div> -->
    </div>
    <div id="bill" class="bill">
        <h2>Bill</h2>
        <!-- <p id="normalSeats">Normal Seats: 0</p> -->
        <p id="vipSeats">VIP Seats: 0</p>
        <p id="totalCost">Total Cost: 0</p>
    </div>
    <!-- <script src="database.js"></script> -->
    <script>
        var Username = JSON.parse('<?php echo $UserName_json;?>');
        var reserved = new Array(80).fill(false);
        let resRead = [];
        var selectedSeat = null;
        var priceNormal = 10;
        var priceVIP = 200;
        var costAll = 0;
        var normalSeats = 0;
        var vipSeats = 0;
        var MatchID = JSON.parse('<?php echo $matchID_json; ?>');
        var UserID = JSON.parse('<?php echo $UserID_json; ?>');
        console.log("MatchID=" + MatchID);
        console.log("UserdID=" + UserID);
        let arraySelected = [];
        function fetchSeats(MatchID) {
            fetch('get_seats.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'MatchID=' + MatchID,
                })
                .then(response => response.json())
                .then(data => {
                    resRead = data;
                    for (let i = 0; i < 80; i++) {
                        reserved[i] = resRead[i] == 1 ? true : false;
                        // console.log("reserved[" + i + "]=" + reserved[i]);
                    }
                    updateGrid();
                });
        }

        fetchSeats(MatchID);
        function updateGrid() {
            var cells = document.getElementsByClassName('grid-item');
            for (var i = 0; i < reserved.length; i++) {
                if (reserved[i]) {
                    cells[i].style.backgroundColor = 'red';
                }
            }
        }


        function createGrid() {
            // updateReserved();

            var grid = document.getElementById('grid');
            for (var i = 0; i < 80; i++) {
                var cell = document.createElement('div');
                cell.className = 'grid-item';
                cell.classList.add('vip');
                if (reserved[i]) {
                    cell.classList.add('reserved');
                }
                if (reserved[i]) {
                    cell.style.backgroundColor = 'red';
                }
                cell.id = 'cell-' + i;
                cell.addEventListener('click', function() {
                    var index = parseInt(this.id.split('-')[1]);
                    // reserved = await getReservedSeats(); // Fetch the latest reserved seats data
                    fetchSeats(MatchID);
                    if (reserved[index]) {
                        alert('Seat ' + (index + 1) + ' is already reserved.');
                    } else {
                        if (this.classList.contains('selected')) {
                            this.classList.remove('selected');
                            if (this.classList.contains('vip')) {
                                vipSeats--;
                                costAll -= priceVIP;
                                arraySelected.splice(index, 1);
                            }
                        } else {
                            this.classList.add('selected');
                            if (this.classList.contains('vip')) {
                                vipSeats++;
                                arraySelected.push(index);
                                costAll += priceVIP;

                            }
                        }
                        updateBill();
                    }
                });
                grid.appendChild(cell);
            }
        }

        function updateBill() {
            document.getElementById('vipSeats').textContent = 'VIP Seats: ' + vipSeats;
            document.getElementById('totalCost').textContent = 'Total Cost: ' + costAll;
        }

        document.getElementById('reserveButton').addEventListener('click', function() {
            if (normalSeats === 0 && vipSeats === 0) {
                alert('No seats have been selected.');
            } else if (confirm('Are you sure you want to reserve these seats?')) {
                var cells = document.getElementsByClassName('grid-item');
                for (var i = 0; i < arraySelected.length; i++) {
                    var INDEXtemp = arraySelected[i];
                    fetchSeats(MatchID);
                    if (reserved[INDEXtemp] == true) {
                        alert('Seat ' + (index + 1) + ' is already reserved.');
                        break;
                    }
                    cells[INDEXtemp].classList.add('reserved');
                    cells[INDEXtemp].classList.remove('selected');
                    reserved[INDEXtemp] = true;
                    var xhr = new XMLHttpRequest();
                    console.log("Control reached this point.");
                    xhr.open('POST', 'update_seats.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    console.log("arraySelected.length=" + arraySelected.length);
                    lastElement = arraySelected[i];
                    var indexSeat = (MatchID - 1) * 80 + 1 + INDEXtemp;
                    xhr.send('SeatNumber=' + indexSeat + '&MatchID=' + MatchID);
                    // }
                }
                modalElement();
                updateGrid();
            }
            arraySelected.splice(0, arraySelected.length);
            normalSeats = 0;
            vipSeats = 0;
            costAll = 0;
            updateBill();
            // }
        });

        document.getElementById('resetButton').addEventListener('click', function() {
            var cells = document.getElementsByClassName('grid-item');
            for (var i = 0; i < cells.length; i++) {
                if (cells[i].classList.contains('selected')) {
                    cells[i].classList.remove('selected');
                    reserved[i] = false;
                    fetch('update_seat_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'seatNumber=' + i + '&matchID=' + MatchID,
                    });
                }
            }
            normalSeats = 0;
            vipSeats = 0;
            costAll = 0;
            updateBill();
        });


        function modalElement() {
            // Get the modal and its content
            var modal = document.getElementById("creditCardModal");
            var modalContent = document.querySelector(".modal-content");
            if(modal && modalContent) {
        document.getElementById("creditCardNumber").value = "";
        document.getElementById("expiryDate").value = "";
        document.getElementById("cvv").value = "";

        modal.style.display = "block";

        modalContent.addEventListener('click', function(event) {
            event.stopPropagation();
        });
        console.log("Control reached this pointINloop.")
    }

            document.getElementById("finalizePayment").addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the form from being submitted normally

                var _creditCardNumber = document.getElementById("creditCardNumber").value;
                // var expiryDate = document.getElementById("expiryDate").value;
                var _cvv = document.getElementById("cvv").value;
                    console.log("Control reached this point199.")
                // Simple validation
                if (_creditCardNumber == "" || expiryDate == "" || _cvv == "") {
                    alert("Please fill in all fields.");
                    return;
                }
                console.log("error here");
                if (_creditCardNumber.length !== 16) {
                    alert("Card number should be 16 digits.");
                    return;
                }
                if (_cvv.length !== 3) {
                    alert("CVV should be 3 digits.");
                    return;
                }
                // var UserID = document.getElementById("UserID").value;
                // var MatchID = document.getElementById("MatchID").value;
                console.log("Control reached this point1929.")
                // Username= "mohamed";
                fetch('AddReservation.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'username':Username,
                            'MatchID': MatchID,
                            'creditCardNumber': _creditCardNumber,
                            'arraySelected': JSON.stringify(arraySelected),
                            'cvv': _cvv
                        })
                    })
                    .then(response => response.text())
                    .then(data => console.log(data))
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                console.log("Control reached this point19222.")
                    modal.style.display = "none";

            });
        }

        function finalizeReservation() {
            return new Promise((resolve, reject) => {
                var modal = document.getElementById("creditCardModal");
                modal.style.display = "block";

                document.getElementById("finalizePayment").addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent the form from being submitted normally
                    modal.style.display = "none";

                    // Make the reservation
                    fetch('AddReservation.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'seat=' + i, // Replace this with the actual seat number
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Handle the response from the server
                            if (data.success) {
                                // The reservation was successful
                                resolve(true);
                            } else {
                                // The reservation failed
                                resolve(false);
                            }
                        });
                });

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                };
            });
        }




        function updateGrid() {
            var cells = document.getElementsByClassName('grid-item');
            for (var i = 0; i < reserved.length; i++) {
                if (reserved[i]) {
                    cells[i].style.backgroundColor = 'red';
                }
            }
        }
        createGrid();
        updateGrid();
    </script>
    <!-- Credit Card Modal -->
    <div id="creditCardModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="CreditCardNumber">
                <label for="CreditCardNumber">Card Number:</label><br>
                <input type="text" id="creditCardNumber" name="creditCardNumber"><br>
                <label for="expiryDate">Expiry Date:</label><br>
                <input type="date" id="expiryDate" name="expiryDate"><br>
                <label for="cvv">CVV:</label><br>
                <input type="text" id="cvv" name="cvv"><br>
                <button id="finalizePayment">Finalize Payment</button>
            </form>
        </div>
    </div>
</body>

</html>
