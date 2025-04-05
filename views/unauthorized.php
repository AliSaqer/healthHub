<?php
session_start();
// var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="text-danger">Access Denied</h1>
        <p>You do not have permission to view this page.</p>
        <a href="../index.php" class="btn btn-primary">Go to Home</a>
    </div>
</body>

</html>