<?php
include "../conn.php";

// Get the POST data
$kode_guru = $_POST['kode_guru'];
$nip = $_POST['nip'];
$nama_guru = $_POST['nama_guru'];
$kelamin = $_POST['kelamin'];
$alamat = $_POST['alamat'];
$no_telepon = $_POST['no_telepon'];
$status_aktif = $_POST['status_aktif'];
$username = $_POST['username'];
$password = $_POST['password'];
$gambar = $_POST['gambar'];

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE guru SET nip=?, nama_guru=?, kelamin=?, alamat=?, no_telepon=?, status_aktif=?, username=?, password=?, gambar=? WHERE kode_guru=?");
$stmt->bind_param("ssssssssss", $nip, $nama_guru, $kelamin, $alamat, $no_telepon, $status_aktif, $username, $password, $gambar, $kode_guru);

// Execute the statement
if ($stmt->execute()) {
    header('location:guru');
} else {
    echo "Update failed: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
