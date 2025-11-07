<?php
include "../conn.php"; // Ensure this file establishes a mysqli connection

$kode_pelajaran = $_POST['kode_pelajaran'];
$nama_pelajaran = $_POST['nama_pelajaran'];
$keterangan = $_POST['keterangan'];

// Prepare the SQL statement to insert new entry
$query = "INSERT INTO pelajaran (kode_pelajaran, nama_pelajaran, keterangan) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $kode_pelajaran, $nama_pelajaran, $keterangan);

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil dimasukan!'); window.location = 'pelajaran.php'</script>";    
} else {
    echo "<script>alert('Data Gagal dimasukan!'); window.location = 'pelajaran.php'</script>";    
}

$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>
