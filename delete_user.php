<?php
include 'db_connect.php';

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Perform the actual deletion in your database
    $delete_query = $conn->query("DELETE FROM users WHERE id = $user_id");

    if ($delete_query) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting user']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
