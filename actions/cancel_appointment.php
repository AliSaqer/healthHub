<?php
session_start();
require_once "../includes/db_connection.php";
require_once "../includes/auth.php";

// Ensure appointment ID is provided
if (!isset($_GET['id'])) {
    header("Location: ../pages/patient_homepage.php?error=missing_id");
    exit();
}

$appointment_id = $_GET['id'];

// Delete appointment
$delete_query = "DELETE FROM appointment WHERE id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("i", $appointment_id);

if ($stmt->execute()) {
    header("Location: ../views/patient_homepage.php?success=appointment_cancelled");
} else {
    header("Location: ../views/patient_homepage.php?error=delete_failed");
}

$stmt->close();
$conn->close();
