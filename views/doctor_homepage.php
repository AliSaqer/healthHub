<?php
if (isset($_GET['success']) && $_GET['success'] == 'prescription_saved') {
  echo "<p style='color: green;'>prescripthin added successfuly.</p>";
}
session_start();

require_once '../includes/db_connection.php'; // Database connection file
include '../includes/auth.php';
checkRole('doctor'); // Only doctors can access

$doctor_id = $_SESSION['user_id'];

// Fetch doctor details
$query = "SELECT id , firstName, lastName, SpecialityID, emailAddress, uniqueFileName FROM doctor WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Doctor's Homepage</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>

<body>
  <header class="header">
    <div class="container-header">
      <img src="../assets/images/logo.png" alt="Logo" style="width: 200px; height: 200px" />
      <h1 style="color: white">Welcome, <?php echo $doctor['firstName'] . " " . $doctor['lastName']; ?></h1>

    </div>
  </header>

  <main class="e-width">
    <section>
      <div style="flex: 1;">
        <h2>Your Information</h2>
        <p>Name: <?php echo $doctor['firstName'] . " " . $doctor['lastName']; ?></p>
        <p>Specialty: <?php echo $doctor['SpecialityID']; ?></p>
        <p>Email: <?php echo $doctor['emailAddress']; ?></p>
      </div>
      <div>
        <img src="<?php echo $doctor['uniqueFileName']; ?>" alt="Doctor Photo" style="width: 150px; height: 150px; border-radius: 50%;">
      </div>
    </section>



    <section>
      <h2>Upcoming Appointments</h2>
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Patient's Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Reason for Visit</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $appointment_query = "SELECT 
        a.id, 
        a.date, 
        a.time, 
        p.id AS patient_id, 
        p.firstName, 
        p.lastName, 
        p.DoB, 
        p.gender, 
        a.reason, 
        a.status 
    FROM appointment a 
    JOIN patient p ON a.PatientID = p.id 
    WHERE a.DoctorID = ? 
    ORDER BY a.date, a.time;";

          $stmt = $conn->prepare($appointment_query);
          $stmt->bind_param("i", $doctor_id); // Corrected: Now filtering by DoctorID
          $stmt->execute();
          $appointment_result = $stmt->get_result();

          while ($row = $appointment_result->fetch_assoc()) {
            // Calculate age from DoB
            $dob = new DateTime($row['DoB']);
            $today = new DateTime();
            $age = $today->diff($dob)->y;

            echo "<tr>
            <td>{$row['date']}</td>
            <td>{$row['time']}</td>
            <td>{$row['firstName']} {$row['lastName']}</td>
            <td>{$age}</td>
            <td>{$row['gender']}</td>
            <td>{$row['reason']}</td>
            <td>{$row['status']}</td>
            <td>";

            if ($row['status'] == 'Pending') {
              echo "<a href='../actions/confirm_appointment.php?id={$row['id']}' class='btn btn-success'>Confirm</a>";
            } elseif ($row['status'] == 'Confirmed') {
              echo "<a href='prescribe.php?appointment_id={$row['id']}&patient_id={$row['patient_id']}' class='btn btn-primary'>Prescribe</a>";
            }

            echo "</td></tr>";
          }
          ?>

          <!-- <tr>
            <td>23/2/2025</td>
            <td>11:00 AM</td>
            <td>Nora Saad</td>
            <td>15</td>
            <td>Female</td>
            <td>Headache</td>
            <td>Pending</td>
            <td><button>Confirm</button></td>
          </tr>
          <tr>
            <td>16/7/2025</td>
            <td>5:00 PM</td>
            <td>Majed Ahmad</td>
            <td>45</td>
            <td>Male</td>
            <td>Back pain</td>
            <td>Confirmed</td>
            <td><button disabled>Confirmed</button></td>
          </tr> -->
        </tbody>
      </table>
    </section>

    <section>
      <h2>Your Patients</h2>
      <table>
        <thead>
          <tr>
            <th>Patient's Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Medications</th>

          </tr>
        </thead>
        <?php
        $patients_query = "SELECT 
        p.id, 
        p.firstName, 
        p.lastName, 
        p.DoB, 
        p.gender, 
        GROUP_CONCAT(m.MedicationName SEPARATOR ', ') AS medications 
        FROM patient p 
        JOIN appointment a ON p.id = a.PatientID 
        LEFT JOIN prescription pr ON a.id = pr.AppointmentID 
        LEFT JOIN medication m ON pr.MedicationID = m.id 
        WHERE a.DoctorID = ? AND a.status = 'Done' 
        GROUP BY p.id 
        ORDER BY p.firstName, p.lastName;"; // Order alphabetically

        $stmt = $conn->prepare($patients_query);
        $stmt->bind_param("i", $doctor_id); // Bind doctor ID
        $stmt->execute();
        $patients_result = $stmt->get_result();

        ?>
        <tbody>

          <?php while ($row = $patients_result->fetch_assoc()) {
            // Calculate Age from DoB
            $dob = new DateTime($row['DoB']);
            $today = new DateTime();
            $age = $today->diff($dob)->y;
          ?>
            <tr>
              <td><?= $row['firstName'] . " " . $row['lastName']; ?></td>
              <td><?= $age; ?></td>
              <td><?= $row['gender']; ?></td>
              <td><?= $row['medications'] ?: "No medications prescribed"; ?></td>
            </tr>
          <?php } ?>
          <!-- <tr>
            <td>Leena Naser</td>
            <td>40</td>
            <td>Female</td>
            <td>Antibiotics</td>
            <td>
              <button>
                <a href="Prescribepage.html" style="color: white !important">Prescribe</a>
              </button>
            </td>
          </tr>
          <tr>
            <td>Majed Saleh</td>
            <td>35</td>
            <td>Male</td>
            <td>N/A</td>
            <td>
              <button>
                <a href="Prescribepage.html" style="color: white !important">Prescribe</a>
              </button>
            </td>
          </tr> -->
        </tbody>
      </table>
    </section>
    <button style="margin-bottom: 10px">
      <a href="../index.php" style="color: white !important">Sign-out</a>
    </button>
  </main>

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
            <img src="../assets/images/social/facebook-icon.jpg" alt="Facebook" />
          </a>
          <a href="https://instagram.com" target="_blank">
            <img src="../assets/images/social/instagram-icon.jpg" alt="Instagram" />
          </a>
          <a href="https://twitter.com" target="_blank">
            <img src="../assets/images/social/twitter-icon.jpg" alt="Twitter" />
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