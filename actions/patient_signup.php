<?php
session_start();

require_once "../includes/db_connection.php";

if (isset($_SESSION['message'])) {
    echo "<div style='color: red; text-align: center;'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $id = $_POST['id'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = $_POST['password'];


    $query = "SELECT * FROM Patient WHERE emailAddress = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $_SESSION['message'] = "Email already exists!";
        header("Location: ../views/signup.php");
        exit();
    }


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $query = "INSERT INTO Patient (id, firstName, lastName, Gender, DoB, emailAddress, password) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $id, $firstName, $lastName, $gender, $dob, $email, $hashedPassword);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_type'] = 'patient';
        header("Location: ../views/patient_homepage.php");
    } else {
        $_SESSION['message'] = "Error occurred, please try again.";
        header("Location: patient_signup.php");
    }
}
