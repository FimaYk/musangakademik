<?php
include "../conn.php"; // Ensure this uses MySQLi or PDO

// Get the class code from the URL
$kode_kelas = $_GET['kd'];

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM kelas WHERE kode_kelas = ?");
$stmt->bind_param("s", $kode_kelas); // Assuming kode_kelas is a string, change "s" to "i" if it's an integer

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil dihapus!'); window.location = 'kelas'</script>";	
} else {
    echo "<script>alert('Data Gagal dihapus!'); window.location = 'kelas'</script>";	
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>
