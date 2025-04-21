    <?php
    session_start();  // Start the session to access session variables like patient ID
    require_once '../includes/db_connection.php';
    include '../includes/auth.php';
    checkRole('patient');

    // Fetch all specialities
    $sqlSpeciality = "SELECT * FROM Speciality";
    $resultSpeciality = $conn->query($sqlSpeciality);



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
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Playwrite+GB+S:ital,wght@0,100..400;1,100..400&display=swap');

            h1,
            p {
                font-family: 'Playwrite GB S', sans-serif;
            }
        </style>

    </head>

    <body>

        <!-- Header Section -->
        <header class="header">
            <div class="container-header">
                <img src="../assets/images/logo.png" alt="Logo" style="width: 200px; height: 200px;">
                <h1 style="color: white; font-size: 3rem;
                font-weight: bold; margin: 0; text-align: center;
                ">BOOKING BAGE</h1>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container-fluid m-5 w-50 mx-auto">
            <section>
                <h1 style="color:black" class="m-3">Book an Appointment</h1>
                <div class="mb-5">
                    <!-- First Form: Select Specialty -->
                    <label for="specialty">Select Specialty:</label>
                    <select name="specialty" id="specialty" required>
                        <option value="">-- Select Specialty --</option>
                        <?php while ($row = $resultSpeciality->fetch_assoc()): ?>
                            <option value="<?= $row['id']; ?>">
                                <?= $row['speciality']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </section>
            <!-- Second Form: Select Doctor -->
            <section id="doctor-section" class="d-none justify-content-center">
                <form method="POST" action="">
                    <input type="hidden" name="specialty" id="selectedSpecialtyInput" />

                    <label for="doctor">Select Doctor:</label>
                    <select name="doctor" id="doctor" required>
                        <option value="">-- Select Doctor --</option>
                        <!-- Options will be filled via AJAX -->
                    </select>

                    <label for="date">Select Date:</label>
                    <input type="date" name="date" id="date" required>

                    <label for="time">Select Time:</label>
                    <input type="time" name="time" id="time" required>

                    <label for="reason">Reason for Visit:</label>
                    <textarea name="reason" id="reason" rows="4" required></textarea>

                    <button type="submit" class="mt-5">Submit Booking</button>
                </form>
            </section>

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
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../assets/js/script.js"></script>
    </body>

    </html>