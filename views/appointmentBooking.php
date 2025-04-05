<?php
session_start();  // Start the session to access session variables like patient ID
require_once '../includes/db_connection.php';
include '../includes/auth.php';
checkRole('patient');

// Fetch all specialities
$sqlSpeciality = "SELECT * FROM Speciality";
$resultSpeciality = $conn->query($sqlSpeciality);

// Fetch all doctors and group by specialty
$sqlDoctors = "SELECT * FROM Doctor";
$resultDoctors = $conn->query($sqlDoctors);

$doctorsBySpecialty = [];

while ($row = $resultDoctors->fetch_assoc()) {
    $doctorsBySpecialty[$row['SpecialityID']][] = $row;
}

// Fetch doctors based on selected specialty (if POST)
$doctors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['specialty'])) {
    $selectedSpecialty = $_POST['specialty'];
    $doctors = $doctorsBySpecialty[$selectedSpecialty] ?? [];
}

// Handle appointment booking (second form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctor'])) {
    $doctorId = $_POST['doctor'];

    // Check if patient is logged in
    if (isset($_SESSION['user_id'])) {
        $patientId = $_SESSION['user_id'];  // Get the patient ID from session
    } else {
        // Redirect to login page if patient is not logged in
        header("Location: login.php");
        exit();
    }

    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];
    $status = 'Pending'; // Default status

    // Insert new appointment into the database
    $stmt = $conn->prepare("INSERT INTO Appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $patientId, $doctorId, $date, $time, $reason, $status);

    if ($stmt->execute()) {
        // Redirect to the patient homepage with a success message
        header("Location: patient_homepage.php?message=Appointment booked successfully!");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- Header Section -->
    <header class="header">
        <div class="container-header">
            <img src="../assets/images/logo.png" alt="Logo" style="width: 200px; height: 200px;">
            <h1 style="color: white;">Welcome, Dr. [Doctor's Name]</h1>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h1 style="color:black">Book an Appointment</h1>

        <!-- First Form: Select Specialty -->
        <form method="POST" action="">
            <label for="specialty">Select Specialty:</label>
            <select name="specialty" id="specialty" required>
                <option value="">-- Select Specialty --</option>
                <?php while ($row = $resultSpeciality->fetch_assoc()): ?>
                    <option value="<?= $row['id']; ?>" <?= isset($selectedSpecialty) && $selectedSpecialty == $row['id'] ? 'selected' : ''; ?>>
                        <?= $row['speciality']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Submit</button>
        </form>

        <!-- Second Form: Select Doctor -->
        <?php if (!empty($doctors)): ?>
            <form method="POST" action="">
                <input type="hidden" name="specialty" value="<?= $selectedSpecialty; ?>" />
                <label for="doctor">Select Doctor:</label>
                <select name="doctor" id="doctor" required>
                    <option value="">-- Select Doctor --</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?= $doctor['id']; ?>"><?= $doctor['firstName'] . ' ' . $doctor['lastName']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="date">Select Date:</label>
                <input type="date" name="date" id="date" required>

                <label for="time">Select Time:</label>
                <input type="time" name="time" id="time" required>

                <label for="reason">Reason for Visit:</label>
                <textarea name="reason" id="reason" rows="4" required></textarea>

                <button type="submit">Submit Booking</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <div class="contact-info">
                <p>Contact us via:</p>
                <p>Email: healthhubcenter@gmail.com</p>
                <p>Telephone: +966559392734</p>
                <p>X:@heathhub</p>
            </div>
            <div class="social-media">
                <p>Follow us on social media</p>
                <div class="social-icons">
                    <a href="https://facebook.com" target="_blank">
                        <img src="facebook-icon.jpg" alt="Facebook">
                    </a>
                    <a href="https://instagram.com" target="_blank">
                        <img src="instagram-icon.jpg" alt="Instagram">
                    </a>
                    <a href="https://twitter.com" target="_blank">
                        <img src="twitter-icon.jpg" alt="Twitter">
                    </a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>