
<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['latitude']) && isset($data['longitude'])) {
        $latitude = floatval($data['latitude']);
        $longitude = floatval($data['longitude']);

        $query = "UPDATE creation_compte SET latitude = ?, longitude = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ddi", $latitude, $longitude, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Location updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update location']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid location data']);
    }

    $conn->close();
}
?>
