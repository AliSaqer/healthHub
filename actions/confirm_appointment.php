<?php
session_start();
require_once "../includes/db_connection.php"; // Database connection



$doctor_id = $_SESSION['user_id']; // Get the logged-in doctor's ID

// Validate the appointment ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../doctor_home.php?error=invalid_id");
    exit();
}

$appointment_id = $_GET['id'];

// Check if the appointment exists, belongs to this doctor, and is still pending
$check_query = "SELECT id FROM appointment WHERE id = ? AND DoctorID = ? AND status = 'Pending'";
$stmt = $conn->prepare($check_query);
$stmt->bind_param("ii", $appointment_id, $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../doctor_home.php?error=not_found_or_already_confirmed");
    exit();
}

// Update the appointment status to "Confirmed"
$update_query = "UPDATE appointment SET status = 'Confirmed' WHERE id = ?";
$stmt = $conn->prepare($update_query);
$stmt->bind_param("i", $appointment_id);

if ($stmt->execute()) {
    header("Location: ../views/doctor_homepage.php");
    exit();
} else {
    header("Location: .../views/doctor_homepage.php?error=db_error");
}

exit();
