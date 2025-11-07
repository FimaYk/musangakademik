<?php
include "../conn.php"; // Ensure this file establishes a mysqli connection

$id = $_GET['kd'];

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM nilai WHERE id = ?");
$stmt->bind_param("i", $id); // Assuming 'id' is an integer

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil dihapus!'); window.location = 'nilai'</script>";    
} else {
    echo "<script>alert('Data Gagal dihapus!'); window.location = 'nilai'</script>";    
}

$stmt->close(); // Close the statement
$conn->close(); // Close the connection
?>
