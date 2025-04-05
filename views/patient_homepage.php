<?php
session_start();

require_once "../includes/db_connection.php";
require_once "../includes/auth.php";
checkRole('patient'); // Only patients can access


$patient_id = $_SESSION['user_id'];

// Fetch patient details
$patient_query = "SELECT firstName, lastName, emailAddress, gender , DoB FROM patient WHERE id = ?";
$stmt = $conn->prepare($patient_query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient_result = $stmt->get_result();
$patient = $patient_result->fetch_assoc();


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Patient Homepage</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="col">
  <header class="header">
    <div class="container-header">
      <img
        src="../assets/images/logo.png"
        alt="Logo"
        style="width: 200px; height: 200px" />
      <h1>Welcome, <?= htmlspecialchars($patient['firstName']) . " " . htmlspecialchars($patient['lastName']); ?></h1>

    </div>
  </header>

  <section class="e-width">
    <h2>Patient Information</h2>
    <p>Name: <?= $patient['firstName'] . ' ' . $patient['lastName'] ?></p>

    <p>Email: <?= $patient['emailAddress'] ?></p>


  </section>

  <section class="e-width">
    <h2>Appointments</h2>
    <a href="appointmentBooking.php" style="float: right; margin-bottom: 10px">Book Appointment</a>
    <table>
      <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Doctor</th>
        <th>Doctor's Photo</th>
        <th>Status</th>
        <th>Action</th>
      </tr>

      <?php
      // Fetch patient appointments
      $app_query = "SELECT a.id, a.date, a.time, a.status, d.firstName AS doctorFirstName,
       d.lastName AS doctorLastName, d.uniqueFileName 
        FROM appointment a
        JOIN doctor d ON a.DoctorID = d.id
        WHERE a.PatientID = ?
        ORDER BY a.date, a.time";
      $stmt = $conn->prepare($app_query);
      $stmt->bind_param("i", $patient_id);
      $stmt->execute();
      $app_result = $stmt->get_result();

      while ($row = $app_result->fetch_assoc()) {
        if ($row['status'] == 'Done') {
          continue;
        }

        echo "<tr>
            <td>{$row['date']}</td>
            <td>{$row['time']}</td>
            <td>{$row['doctorFirstName']} {$row['doctorLastName']}</td>
            <td><img src='{$row['uniqueFileName']}'alt='doctor' style='width: 40px; height: 40px;'/></td>
            <td>{$row['status']}</td>
            <td>";
        if ($row['status'] == 'Pending') {
          echo "<a href='../actions/cancel_appointment.php?id={$row['id']}' class='btn btn-danger'>cancel</a>";
        } elseif ($row['status'] == 'Confirmed') {
          echo "<a href='../actions/cancel_appointment.php?id={$row['id']}' class='btn btn-danger'>cancel</a>";
        } else {
          echo 'nothing to do';
        }

        echo "</td></tr>";
      }
      ?>


      <!-- <tr>
        <td>2025-05-25</td>
        <td>03:00 PM</td>
        <td>Dr. Sara Ahmed</td>
        <td>
          <img
            src="../assets/images/doctor1.png"
            alt="doctor"
            style="width: 40px; height: 40px" />
        </td>
        <td>Pending</td>
        <td><a href="#">Cancel</a></td>
      </tr>
      <tr>
        <td>2025-02-14</td>
        <td>10:00 AM</td>
        <td>Dr. Salah Abdullah</td>
        <td>
          <img
            src="doctor2.png"
            alt="doctor"
            style="width: 40px; height: 40px" />
        </td>
        <td>Confirmed</td>
        <td><a href="#">Cancel</a></td>-->
      </tr>
    </table>
  </section>
  <button style="margin-bottom: 10px">
    <a href="../index.php" style="color: white !important">Sign-out</a>
  </button>
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
            <img
              src="../assets/images/social/facebook-icon.jpg"
              alt="Facebook" />
          </a>
          <a href="https://instagram.com" target="_blank">
            <img
              src="../assets/images/social/instagram-icon.jpg"
              alt="Instagram" />
          </a>
          <a href="https://twitter.com" target="_blank">
            <img
              src="../assets/images/social/twitter-icon.jpg"
              alt="Twitter" />
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