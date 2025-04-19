<?php
require_once '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['specialty'])) {
    $specialtyId = intval($_POST['specialty']);

    $stmt = $conn->prepare("SELECT id, firstName, lastName FROM Doctor WHERE SpecialityID = ?");
    $stmt->bind_param("i", $specialtyId);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = [
            'id' => $row['id'],
            'name' => $row['firstName'] . ' ' . $row['lastName']
        ];
    }

    echo json_encode($doctors);
}
