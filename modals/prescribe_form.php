<?php
require_once "../includes/db_connection.php";

if (!isset($_POST['appointment_id']) || !isset($_POST['patient_id'])) {
    echo "Missing data.";
    exit();
}

$appointment_id = $_POST['appointment_id'];
$patient_id = $_POST['patient_id'];

// Fetch patient details
$patient_query = "SELECT firstName, lastName, DoB, Gender FROM patient WHERE id = ?";
$stmt = $conn->prepare($patient_query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient_result = $stmt->get_result();
$patient = $patient_result->fetch_assoc();

// Calculate age
$dob = new DateTime($patient['DoB']);
$today = new DateTime();
$age = $today->diff($dob)->y;

// Fetch available medications
$med_query = "SELECT * FROM medication";
$med_result = $conn->query($med_query);
?>

<form action="../actions/save_prescription.php" method="POST">
    <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
    <input type="hidden" name="patient_id" value="<?= $patient_id; ?>">

    <div class="mb-3">
        <label class="form-label">Patient's Name:</label>
        <input type="text" class="form-control" value="<?= $patient['firstName'] . ' ' . $patient['lastName']; ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Age:</label>
        <input type="number" class="form-control" value="<?= $age; ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Gender:</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Male" <?= ($patient['Gender'] == 'Male') ? 'checked' : ''; ?> disabled>
                <label class="form-check-label">Male</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="Female" <?= ($patient['Gender'] == 'Female') ? 'checked' : ''; ?> disabled>
                <label class="form-check-label">Female</label>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Medications:</label>
        <div class="form-check">
            <?php while ($med = $med_result->fetch_assoc()) { ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="med_<?= $med['id']; ?>" name="medications[]" value="<?= $med['id']; ?>">
                    <label class="form-check-label" for="med_<?= $med['id']; ?>"><?= $med['MedicationName']; ?></label>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Save Prescription</button>
    </div>
</form>