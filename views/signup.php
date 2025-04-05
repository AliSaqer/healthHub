<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            /* مركز المحتوى */
            align-items: flex-start;
            /* محاذاة للأعلى */
            padding: 20px;
        }

        #form-container {
            width: 100%;
            max-width: 400px;
            /* تقليل عرض النموذج */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #form-row {
            display: flex;
            flex-direction: column;
            margin: 10px 0;
        }

        #form-row label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        #form-row input,
        #form-row select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        button[type="submit"] {
            background-color: #4A90E2;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #357ABD;
        }

        .logo img {
            width: 50px;
            /* Adjust size of logo */
            height: auto;
            margin-bottom: 10px;
        }

        header {
            background-color: #1F2B5B;
            color: white;
            padding: 5px;

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
            position: relative;
            bottom: 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .contact-info p,
        .social-media p {
            margin: 5px 10px;
        }

        .social-icons a {
            margin: 0 10px;
        }

        .social-icons img {
            width: 25px;
            height: 25px;
            vertical-align: middle;
        }

        .footer-bottom {
            border-top: 1px solid #fff;
            padding-top: 10px;
            font-size: 12px;
        }

        .radio-group {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .radio-group input[type="radio"] {
            display: none;
            /* إخفاء الزر الافتراضي */
        }

        .radio-group label {
            background-color: #4A90E2;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
        }

        .radio-group input[type="radio"]:checked+label {
            background-color: #357ABD;
            /* تغيير اللون عند التحديد */
        }

        .radio-group label:hover {
            background-color: #357ABD;
            /* تغيير اللون عند المرور */
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="container-header">
            <img src="../assets/images/logo.png" alt="Logo" style="width: 200px; height: 200px;">
        </div>
    </header>
    <main>
        <div id="form-container">
            <h1 style="color:rgb(26, 4, 88); font-size:30px; text-align:center;">Sign-up</h1>
            <hr>
            <div class="radio-group">
                <input type="radio" id="patientRadio" name="role" value="patient" onclick="showForm()">
                <label for="patientRadio">Patient</label>
                <input type="radio" id="doctorRadio" name="role" value="doctor" onclick="showForm()">
                <label for="doctorRadio">Doctor</label>
            </div>
            <hr>
            <?php


            if (isset($_SESSION['message'])) {
                echo "<div style='color: red; text-align: center;'>" . $_SESSION['message'] . "</div>";
                unset($_SESSION['message']);
            }
            ?>

            <!-- patiant signup form -->
            <div id="patientForm" style="display: none;">

                <form method="POST" action="../actions/patient_signup.php">
                    <div id="form-row">
                        <label for="patientFirstName">First Name:</label>
                        <input type="text" id="patientFirstName" name="firstName" required>
                    </div>
                    <div id="form-row">
                        <label for="patientLastName">Last Name:</label>
                        <input type="text" id="patientLastName" name="lastName" required>
                    </div>
                    <div id="form-row">
                        <label for="patientID">ID:</label>
                        <input type="text" id="patientID" name="id" required>
                    </div>
                    <div id="form-row">
                        <label for="patientGender">Gender:</label>
                        <select id="patientGender" name="gender" required>
                            <option value="">-- Select Gender --</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div id="form-row">
                        <label for="patientDOB">Date of Birth:</label>
                        <input type="date" id="patientDOB" name="dob" required>
                    </div>
                    <div id="form-row">
                        <label for="patientEmail">Email:</label>
                        <input type="email" id="patientEmail" name="email" required>
                    </div>
                    <div id="form-row">
                        <label for="patientPassword">Password:</label>
                        <input type="password" id="patientPassword" name="password" required>
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>

            <!-- doctor signup form -->
            <div id="doctorForm" style="display: none;">
                <form action="../actions/doctor_signup.php" method="POST" enctype="multipart/form-data">

                    <div id="form-row">
                        <label for="doctorFirstName">First Name:</label>
                        <input type="text" id="doctorFirstName" name="firstName" required>
                    </div>
                    <div id="form-row">
                        <label for="doctorLastName">Last Name:</label>
                        <input type="text" id="doctorLastName" name="lastName" required>
                    </div>
                    <div id="form-row">
                        <label for="doctorID">ID:</label>
                        <input type="text" id="doctorID" name="id" required>
                    </div>
                    <div id="form-row">
                        <label for="doctorPhoto">Photo:</label>
                        <input type="file" id="doctorPhoto" name="photo" accept="image/*" required>
                    </div>
                    <div id="form-row">
                        <label for="doctorSpeciality">Speciality:</label>
                        <select id="doctorSpeciality" name="SpecialityID" required>
                            <option value="">-- Select Speciality --</option>
                            <option value="1">General Practitioner</option>
                            <option value="2">Emergency Medicine</option>
                            <option value="3">Dermatology</option>
                            <option value="4">Dentistry</option>
                        </select>
                    </div>
                    <div id="form-row">
                        <label for="doctorEmail">Email:</label>
                        <input type="email" id="doctorEmail" name="email" required>
                    </div>
                    <div id="form-row">
                        <label for="doctorPassword">Password:</label>
                        <input type="password" id="doctorPassword" name="password" required>
                    </div>
                    <button type="submit">Submit</button>
                </form>






            </div>
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

    <script>
        function showForm() {
            const role = document.querySelector('input[name="role"]:checked').value;
            document.getElementById('patientForm').style.display = 'none';
            document.getElementById('doctorForm').style.display = 'none';

            if (role === 'patient') {
                document.getElementById('patientForm').style.display = 'block';
            } else if (role === 'doctor') {
                document.getElementById('doctorForm').style.display = 'block';
            }
        }
    </script>
</body>

</html>