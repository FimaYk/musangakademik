<?php
include "../conn.php";

// Get the POST data
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$password = $_POST['password'];
$fullname = $_POST['fullname'];
$gambar_admin = $_POST['gambar_admin'];

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE user SET username=?, password=?, fullname=? WHERE user_id=?");
$stmt->bind_param("sssi", $username, $password, $fullname, $user_id);

// Execute the statement
if ($stmt->execute()) {
    header('location:admin');
} else {
    echo "Update failed: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
