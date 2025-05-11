<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "game_store";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

if (!isset($_GET['order_id'])) {
    echo json_encode(['success' => false, 'error' => 'Order ID not provided']);
    exit;
}

$orderId = intval($_GET['order_id']);
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Order not found']);
    exit;
}

$order = $result->fetch_assoc();
echo json_encode(['success' => true, 'order' => $order]);

$conn->close();
?>