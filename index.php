<?php
include 'includes/db.php';
include 'includes/functions.php';

$tickets = getAvailableTickets($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticketing System</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1>Available Tickets</h1>
    <ul>
        <?php while($row = $tickets->fetch_assoc()): ?>
            <li>Ticket ID: <?= $row["id"] ?> - Numbers: <?= $row["numbers"] ?> 
                <a href="select_ticket.php?ticket_id=<?= $row["id"] ?>">Select</a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    h1 {
        color: #333;
    }

    ul {
        list-style: none;
        padding: 0;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    li {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 10px;
        width: 200px;
        text-align: center;
        position: relative;
        animation: fadeIn 1s ease-in-out;
    }

    li::before, li::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background: #fff;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    li::before {
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
    }

    li::after {
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
    }

    a {
        display: inline-block;
        margin-top: 10px;
        padding: 10px;
        background: #28a745;
        color: #fff;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
    }

    a:hover {
        background: #218838;
    }

    li {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        margin: 10px;
        width: 150px;
        text-align: center;
        position: relative;
        animation: fadeIn 1s ease-in-out;
    }

    li.selected {
        background: #ffeb3b;
        box-shadow: 0 0 10px rgba(255, 235, 59, 0.5);
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>