<?php
include "../conn.php"; // Ensure this uses MySQLi or PDO

// Get user ID from the URL
$user_id = $_GET['kd'];

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM user WHERE user_id = ?");
$stmt->bind_param("s", $user_id); // Assuming user_id is a string, change "s" to "i" if it's an integer

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil dihapus!'); window.location = 'admin'</script>";	
} else {
    echo "<script>alert('Data Gagal dihapus!'); window.location = 'admin'</script>";	
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>
