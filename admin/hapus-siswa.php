<?php
include "../conn.php";

// Get the kode_siswa from the URL
$kode_siswa = $_GET['kd'] ?? '';

// Check if kode_siswa is valid
if (!$kode_siswa) {
    echo "<script>alert('Kode siswa tidak valid!'); window.location = 'siswa'</script>";
    exit;
}

// Prepare statement to delete siswa by kode_siswa
$stmt = $conn->prepare("DELETE FROM siswa WHERE kode_siswa = ?");
$stmt->bind_param("s", $kode_siswa);

// Execute the statement
if ($stmt->execute()) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Data Berhasil dihapus!'); window.location = 'siswa'</script>";
    } else {
        echo "<script>alert('Data tidak ditemukan atau sudah dihapus sebelumnya!'); window.location = 'siswa'</script>";
    }
} else {
    echo "<script>alert('Data Gagal dihapus! Error: " . $stmt->error . "'); window.location = 'siswa'</script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
