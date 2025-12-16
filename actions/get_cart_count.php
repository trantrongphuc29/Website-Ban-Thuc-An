<?php
require_once '../config/config.php';

if (empty($_SESSION['user'])) {
    echo json_encode(['count' => 0]);
    exit;
}

$userId = $_SESSION['user']['user_id'];

$conn = getDBConnection();

$stmt = $conn->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$count = $row['total'] ?? 0;

$stmt->close();
$conn->close();

echo json_encode(['count' => (int)$count]);
?>