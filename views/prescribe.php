<?php
session_start();
require_once "../includes/db_connection.php";
require_once "../includes/auth.php";

// Validate appointment ID & patient ID
if (!isset($_GET['appointment_id']) || !isset($_GET['patient_id'])) {
    header("Location: doctor_homepage.php?error=missing_data");
    exit();
}

$appointment_id = $_GET['appointment_id'];
$patient_id = $_GET['patient_id'];

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescribe Medication</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        header {
            background-color: #1F2B5B;
            color: white;
            padding: 5px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        footer {
            background-color: #1F2B5B;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
            width: 100%;
        }

        .prescription-form {
            max-width: 800px;
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .radio-group input {
            margin-right: 10px;
        }

        .radio-group label {
            margin-right: 20px;
        }

        .checkbox-group {
            display: flex;
            gap: 15px;
            margin-top: 8px;
            flex-wrap: wrap;
        }

        .checkbox-group input {
            margin-right: 5px;
        }

        .submit-button {
            background-color: #4A90E2;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }

        .submit-button:hover {
            background-color: #357ABD;
        }
    </style>
</head>

<body>
    <header>
        <div class="container-header">
            <img src="../assets/images/logo.png" alt="Logo" style="width: 150px; height: auto; margin-bottom: 10px;">
        </div>
    </header>

    <main>
        <form class="prescription-form" action="../actions/save_prescription.php" method="POST">
            <input type="hidden" name="appointment_id" value="<?= $appointment_id; ?>">
            <input type="hidden" name="patient_id" value="<?= $patient_id; ?>">

            <div class="form-group">
                <label for="patient-name">Patient's Name:</label>
                <input type="text" id="patient-name" class="input-field" name="patientName" value="<?= $patient['firstName'] . ' ' . $patient['lastName']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="patient-age">Age:</label>
                <input type="number" id="patient-age" class="input-field" name="patientAge" value="<?= $age; ?>" readonly>
            </div>

            <div class="form-group">
                <label>Gender:</label>
                <div class="radio-group">
                    <input type="radio" id="male" name="gender" value="Male" <?= ($patient['Gender'] == 'Male') ? 'checked' : ''; ?> disabled>
                    <label for="male">Male</label>
                    <input type="radio" id="female" name="gender" value="Female" <?= ($patient['Gender'] == 'Female') ? 'checked' : ''; ?> disabled>
                    <label for="female">Female</label>
                </div>
            </div>

            <div class="form-group">
                <label>Medications:</label>
                <div class="checkbox-group">
                    <?php while ($med = $med_result->fetch_assoc()) { ?>
                        <input type="checkbox" id="med_<?= $med['id']; ?>" name="medications[]" value="<?= $med['id']; ?>">
                        <label for="med_<?= $med['id']; ?>"><?= $med['MedicationName']; ?></label>
                    <?php } ?>
                </div>
            </div>

            <button type="submit" class="submit-button">Submit</button>
        </form>
    </main>
</body>

</html>