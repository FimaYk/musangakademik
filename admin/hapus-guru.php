<?php
include "../conn.php"; // Ensure this uses MySQLi or PDO

// Get the teacher's code from the URL
$kode_guru = $_GET['kd'];

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("DELETE FROM guru WHERE kode_guru = ?");
$stmt->bind_param("s", $kode_guru); // Assuming kode_guru is a string, change "s" to "i" if it's an integer

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil dihapus!'); window.location = 'guru'</script>";	
} else {
    echo "<script>alert('Data Gagal dihapus!'); window.location = 'guru'</script>";	
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();
?>
