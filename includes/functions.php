<?php
session_start();

function generateTickets($conn) {
    $stmt = $conn->prepare("INSERT INTO tickets (numbers) VALUES (?)");
    for ($i = 0; i < 250; $i++) {
        $numbers = implode('-', array_map(function() {
            return str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
        }, range(1, 4)));
        $stmt->bind_param("s", $numbers);
        $stmt->execute();
    }
    $stmt->close();
}

function getAvailableTickets($conn) {
    $sql = "SELECT * FROM tickets WHERE is_selected=0";
    return $conn->query($sql);
}

function selectTicket($conn, $ticket_id, $name, $phone, $iban) {
    $stmt = $conn->prepare("INSERT INTO users (name, phone, iban, ticket_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $phone, $iban, $ticket_id);
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $stmt->close();
        $update_ticket = $conn->prepare("UPDATE tickets SET is_selected=1, user_id=? WHERE id=?");
        $update_ticket->bind_param("ii", $user_id, $ticket_id);
        $update_ticket->execute();
        $update_ticket->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

function confirmPayment($conn, $ticket_id) {
    $update_ticket = $conn->prepare("UPDATE tickets SET is_paid=1 WHERE id=?");
    $update_ticket->bind_param("i", $ticket_id);
    $result = $update_ticket->execute();
    $update_ticket->close();
    return $result;
}

function login($conn, $username, $password) {
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['admin_id'] = $id;
        return true;
    } else {
        return false;
    }
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function logout() {
    session_destroy();
}
?>