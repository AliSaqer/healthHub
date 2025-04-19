<?php
header('Content-Type: application/json');
include('../includes/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = $_POST['id'];

    $stmt = $conn->prepare("UPDATE appointment SET status = 'confirmed' WHERE id = ?");
    $stmt->bind_param("i", $appointmentId);

    if ($stmt->execute()) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }
}
