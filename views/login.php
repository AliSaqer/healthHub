<?php
session_start();
require_once "../includes/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']); // doctor أو patient

    if (empty($email) || empty($password) || empty($role)) {
        $_SESSION['message'] = "Please fill in all fields!";
    } else {
        $table = ($role === 'doctor') ? 'Doctor' : 'Patient';


        $query = "SELECT id, password FROM $table WHERE emailAddress = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();


                if (password_verify($password, $row['password']) || $password === $row['password']) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_type'] = $role;


                    $redirectPage = ($role === 'doctor') ? 'doctor_homepage.php' : 'patient_homepage.php';
                    header("Location: $redirectPage");
                    exit();
                } else {
                    $_SESSION['message'] = "incorrect password or email!";
                }
            } else {
                $_SESSION['message'] = "invalid Email !";
            }
        } else {
            $_SESSION['message'] = "خطأ في الاستعلام: " . $conn->error;
        }
    }
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Clinic Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="div-cont" style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
        <header class="header">
            <div class="container-header">
                <img src="../assets/images/logo.png" alt="Logo" style="width: 200px; height: 200px;">
            </div>
        </header>

        <h1 style="color: #007BFF; margin-top: 10px;">Login</h1>

        <main>
            <!-- عرض رسالة الخطأ -->
            <?php if (isset($_SESSION['message'])): ?>
                <p style="color: red;"><?php echo $_SESSION['message'];
                                        unset($_SESSION['message']); ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="email" id="email" name="email" placeholder="Email Address" required><br>
                <input type="password" id="password" name="password" placeholder="Password" required><br>

                <label for="role">Select Role:</label><br>
                <div class="dispaly-row" style="display: flex; justify-content: space-around; width: 100%;">
                    <input type="radio" id="doctor" name="role" value="doctor">
                    <label for="doctor">Doctor</label>
                    <input type="radio" id="patient" name="role" value="patient" checked>
                    <label for="patient">Patient</label><br>
                </div>

                <button type="submit" class="submit-btn">Log In</button>
            </form>

            <p>New User? <a href="signup.php">Sign Up</a></p>
        </main>
    </div>

    <footer>
        <div class="footer-content">
            <div class="contact-info">
                <p>Contact us via:</p>
                <p>Email: healthhubcenter@gmail.com</p>
                <p>Telephone: +966559392734</p>
                <p>X: @heathhub</p>
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