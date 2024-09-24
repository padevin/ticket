<?php
include 'includes/db.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $iban = $_POST['iban'];

    if (selectTicket($conn, $ticket_id, $name, $phone, $iban)) {
        $payment_info = true;
        $_SESSION['ticket_id'] = $ticket_id;
        $_SESSION['payment_start_time'] = time();
    } else {
        $error = "Error selecting ticket.";
    }
} else {
    $ticket_id = $_GET['ticket_id'];
}

if (isset($_SESSION['payment_start_time']) && (time() - $_SESSION['payment_start_time']) > 600) {
    $ticket_id = $_SESSION['ticket_id'];
    $conn->query("UPDATE tickets SET is_selected=0, user_id=NULL WHERE id=$ticket_id");
    unset($_SESSION['ticket_id']);
    unset($_SESSION['payment_start_time']);
    $error = "Payment time expired. The ticket has been released.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Ticket</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script>
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = 0;
                    alert("Payment time expired. The ticket has been released.");
                    window.location.reload();
                }
            }, 1000);
        }

        window.onload = function () {
            var tenMinutes = 60 * 10,
                display = document.querySelector('#time');
            startTimer(tenMinutes, display);
        };
    </script>
</head>
<body>
    <h1>Select Ticket</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <?php if (isset($payment_info)): ?>
        <h2>Payment Information</h2>
        <p>NAME: Yusuf Arslan</p>
        <p>IBAN: TR6674278547257237</p>
        <?php
        $stmt = $conn->prepare("SELECT numbers FROM tickets WHERE id=?");
        $stmt->bind_param("i", $ticket_id);
        $stmt->execute();
        $stmt->bind_result($ticket_numbers);
        $stmt->fetch();
        $stmt->close();
        ?>
        <p>Your Ticket Numbers: <?= $ticket_numbers ?></p>
        <p>You have <span id="time">10:00</span> minutes to pay.</p>
        </style>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
     <?php else: ?>
        <form method="POST" action="select_ticket.php" class="mt-4">
            <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>" class="form-control">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="iban">IBAN:</label>
                <input type="text" id="iban" name="iban" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Confirm</button>
        </form>
    <?php endif; ?>
</body>
</html>
<style>
            form {
                display: flex;
                flex-direction: column;
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                width: 100%;
            }

            label {
                margin-bottom: 5px;
                color: #555;
            }

            input {
                margin-bottom: 15px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            button {
                padding: 10px;
                background: #28a745;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                transition: background 0.3s;
            }

            button:hover {
                background: #218838;
            }
        </style>