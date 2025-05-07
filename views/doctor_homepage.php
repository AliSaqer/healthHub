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
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

            echo "<tr id='appointment_{$row['id']}'>
            <td>{$row['date']}</td>
            <td>{$row['time']}</td>
            <td>{$row['firstName']} {$row['lastName']}</td>
            <td>{$age}</td>
            <td>{$row['gender']}</td>
            <td>{$row['reason']}</td>
            <td class='status'>{$row['status']}</td>
            <td>";

            if ($row['status'] == 'Pending') {
              echo "<a href='#' class='confirm-btn btn btn-success' data-id='{$row['id']}'>Confirm</a>";
            } elseif ($row['status'] == 'Confirmed') {
              echo "<button class='btn btn-primary prescribe-btn' 
              data-bs-toggle='modal'
              data-bs-target='#prescriptionModal'
              data-appointment='{$row['id']}'
              data-patient='{$row['patient_id']}'>Prescribe</button>";
            }

            echo "</td></tr>";
          }
          ?>


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

        </tbody>
      </table>
    </section>
    <div style="text-align: center;" class="m-4">
      <button class="btn btn-danger mt-3" style="width: 100%; max-width: 200px; transition: transform 0.5s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <a href="../index.php" style="color: white !important; text-decoration: none;">Sign-out</a>
      </button>

    </div>
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
        <p style="font-size: medium;" class="mb-3">Follow us on social media</p>
        <div class="social-icons">
          <a href="https://facebook.com" target="_blank">
            <!-- <img src="../assets/images/social/facebook-icon.jpg" alt="Facebook"> -->
            <i class="fab fa-facebook fa-lg"> facebook</i>
          </a>
          <a href="https://instagram.com" target="_blank">
            <!-- <img src="../assets/images/social/instagram-icon.jpg" alt="Instagram"> -->
            <i class="fab fa-instagram fa-lg"> instagram</i>
          </a>
          <a href="https://twitter.com" target="_blank">
            <!-- <img src="../assets/images/social/twitter-icon.jpg" alt="Twitter"> -->
            <i class="fab fa-twitter fa-lg"> twitter</i>
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 All Rights Reserved.</p>
    </div>
  </footer>
  //prescripe modal
  <div class="modal fade" id="prescribeModal" tabindex="-1" aria-labelledby="prescribeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Larger width -->
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="prescribeModalLabel">Prescribe Medication</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="prescribeModalBody">
          <!-- Form will be loaded here via AJAX -->
          Loading...
        </div>
      </div>
    </div>
  </div>
  //end of modal
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
  <script src="../assets/js/script.js"></script>
  <script>
    $(document).on("click", ".prescribe-btn", function() {
      var appointmentId = $(this).data("appointment");
      var patientId = $(this).data("patient");

      $("#prescribeModalBody").load(
        "../modals/prescribe_form.php", // Copy form content from prescribe_page here
        {
          appointment_id: appointmentId,
          patient_id: patientId
        },
        function() {
          // Initialize the modal after loading the content
          $("#prescribeModal").modal("show");
        }
      );
    });
  </script>
</body>

</html>