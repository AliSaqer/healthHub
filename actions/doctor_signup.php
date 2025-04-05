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
    $SpecialityID = $_POST['SpecialityID'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $photo = $_FILES['photo']['name'];


    $query = "SELECT * FROM Doctor WHERE emailAddress = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $_SESSION['message'] = "Email already exists!";
        header("Location: signup.html"); // 
        exit();
    }


    $targetDir = "../assets/images/";
    $targetFile = $targetDir . basename($photo);
    move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile);


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $query = "INSERT INTO Doctor (id, firstName, lastName, uniqueFileName, SpecialityID, emailAddress, password) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssss", $id, $firstName, $lastName, $targetFile, $SpecialityID, $email, $hashedPassword);

    if ($stmt->execute()) {

        $_SESSION['user_id'] = $id;
        $_SESSION['user_type'] = 'doctor';
        header("Location: ../views/doctor_homepage.php");
    } else {
        $_SESSION['message'] = "Error occurred, please try again.";
        header("Location: doctor_signup.php");
    }
}
