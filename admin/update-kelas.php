<?php
include "../conn.php";
session_start();

if (empty($_SESSION['username'])) {
    header('Location: ../dashboard');
    exit();
}

// Get the POST data and sanitize it
$kode_kelas   = isset($_POST['kode_kelas']) ? trim($_POST['kode_kelas']) : '';
$tahun_ajar   = isset($_POST['tahun_ajar']) ? trim($_POST['tahun_ajar']) : '';
$kelas        = isset($_POST['kelas']) ? trim($_POST['kelas']) : '';
$nama_kelas   = isset($_POST['nama_kelas']) ? trim($_POST['nama_kelas']) : '';
$kode_guru    = isset($_POST['kode_guru']) ? trim($_POST['kode_guru']) : '';
$status_aktif = isset($_POST['status_aktif']) ? trim($_POST['status_aktif']) : '';

// Fetch existing values for comparison
$existing_query = $conn->prepare("SELECT * FROM kelas WHERE kode_kelas=?");
$existing_query->bind_param("s", $kode_kelas);
$existing_query->execute();
$existing_data = $existing_query->get_result()->fetch_assoc();

// Debugging output (comment out in production)
echo '<pre>';
echo "Existing Data: ";
print_r($existing_data);
echo "Submitted Data: ";
print_r($_POST);
echo '</pre>';

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE kelas SET tahun_ajar=?, kelas=?, nama_kelas=?, kode_guru=?, status_aktif=? WHERE kode_kelas=?");
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

// Bind parameters
$stmt->bind_param("ssssss", $tahun_ajar, $kelas, $nama_kelas, $kode_guru, $status_aktif, $kode_kelas);

// Execute the statement
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        header('Location: kelas');
        exit();
    } else {
        echo "No records updated. Please check if the data is different from the existing record.";
    }
} else {
    echo "Update failed: " . htmlspecialchars($stmt->error);
}

// Close the statement and connection
$stmt->close();
$existing_query->close();
$conn->close();
?>
