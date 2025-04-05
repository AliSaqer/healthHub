<?php
if (isset($_GET['error']) && $_GET['error'] == 'unauthorized') {
  echo "<p style='color: red;'>You must log in to access this page.</p>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Clinic App - Home</title>
  <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body>
  <header class="header">
    <div class="container-header">
      <img
        src="assets/images/logo.png"
        alt="Logo"
        style="width: 200px; height: 200px" />
    </div>
  </header>
  <div class="container">
    <h1 class="app-name">HealthHub</h1>
    <a href="views/login.php" class="login-btn">Log-in</a>
    <p class="signup-text">
      New User? <a href="views/signup.php"><strong>Sign-up</strong></a>
    </p>
  </div>
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
              src="assets/images/social/facebook-icon.jpg"
              alt="Facebook" />
          </a>
          <a href="https://instagram.com" target="_blank">
            <img
              src="assets/images/social/instagram-icon.jpg"
              alt="Instagram" />
          </a>
          <a href="https://twitter.com" target="_blank">
            <img src="assets/images/social/twitter-icon.jpg" alt="Twitter" />
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