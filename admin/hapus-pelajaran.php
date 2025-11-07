<?php
include "../conn.php";

$kode_pelajaran = $_GET['kd'] ?? '';

if (!$kode_pelajaran) {
    echo "<script>alert('Kode pelajaran tidak valid!'); window.location = 'pelajaran'</script>";
    exit;
}

// Prepare statement to delete pelajaran by kode_pelajaran
$stmt = $conn->prepare("DELETE FROM pelajaran WHERE kode_pelajaran = ?");
$stmt->bind_param("s", $kode_pelajaran);

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil dihapus!'); window.location = 'pelajaran'</script>";
} else {
    echo "<script>alert('Data Gagal dihapus!'); window.location = 'pelajaran'</script>";
}

$stmt->close();
$conn->close();
?>

