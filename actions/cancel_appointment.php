<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    include('../includes/db_connection.php');

    $appointmentId = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM appointment WHERE id = ?");
    $stmt->bind_param("i", $appointmentId);
    $success = $stmt->execute();

    echo json_encode($success); // returns true or false
    exit();
}

echo json_encode(false);
