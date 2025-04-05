<?php


// Redirect if user is not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
    header("Location: ../index.php?error=unauthorized"); // Redirect to home page
    exit();
}

// Define role-based access
function checkRole($role)
{
    if ($_SESSION['user_type'] !== $role) {
        header("Location: ../views/unauthorized.php"); // Redirect if the role is incorrect
        exit();
    }
}
