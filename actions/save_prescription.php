<?php
session_start();
require_once "../includes/db_connection.php";
require_once "../includes/auth.php";

// Check if form data is received
if (!isset($_POST['appointment_id'], $_POST['patient_id'], $_POST['medications'])) {
    header("Location: doctor_homepage.php?error=missing_data");
    exit();
}

$appointment_id = $_POST['appointment_id'];
$patient_id = $_POST['patient_id'];
$medications = $_POST['medications']; // Array of selected medication IDs

// Start transaction
$conn->begin_transaction();

try {
    // Insert prescription records
    $prescription_query = "INSERT INTO prescription (AppointmentID, MedicationID) VALUES (?, ?)";
    $stmt = $conn->prepare($prescription_query);

    foreach ($medications as $medication_id) {
        $stmt->bind_param("ii", $appointment_id, $medication_id);
        $stmt->execute();
    }

    // Update appointment status to 'Done'
    $update_status_query = "UPDATE appointment SET status = 'Done' WHERE id = ?";
    $update_stmt = $conn->prepare($update_status_query);
    $update_stmt->bind_param("i", $appointment_id);
    $update_stmt->execute();

    // Commit transaction
    $conn->commit();

    // Redirect with success message
    header("Location: ../views/doctor_homepage.php?success=prescription_saved");
    exit();
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    header("Location: ../views/prescribe.php?error=database_error");
    exit();
}
