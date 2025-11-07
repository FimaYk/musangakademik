<?php
include "../conn.php";

// Get the POST data
$kode_pelajaran = $_POST['kode_pelajaran'];
$nama_pelajaran = $_POST['nama_pelajaran'];
$keterangan = $_POST['keterangan'];

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE pelajaran SET nama_pelajaran=?, keterangan=? WHERE kode_pelajaran=?");
$stmt->bind_param("sss", $nama_pelajaran, $keterangan, $kode_pelajaran);

// Execute the statement
if ($stmt->execute()) {
    header('location:pelajaran');
} else {
    echo "Update failed: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
