<?php
include "../conn.php"; // Ensure this file establishes a mysqli connection

$kode_kelas = $_POST['kode_kelas'];
$tahun_ajar = $_POST['tahun_ajar'];
$kelas = $_POST['kelas'];
$nama_kelas = $_POST['nama_kelas'];
$kode_guru = $_POST['kode_guru'];
$status_aktif = $_POST['status_aktif'];

// Prepare the SQL statement to check for existing entries
$sqlCek = "SELECT * FROM kelas WHERE nama_kelas = ? AND tahun_ajar = ?";
$stmtCek = $conn->prepare($sqlCek);
$stmtCek->bind_param("ss", $nama_kelas, $tahun_ajar);
$stmtCek->execute();
$qryCek = $stmtCek->get_result();

if ($qryCek->num_rows >= 1) {
    echo "<script>alert('Maaf, Nama Kelas <b>$nama_kelas</b> dengan <b>Tahun Ajaran</b> yang sama sudah dibuat'); window.location = 'kelas.php'</script>";
} else {
    // Prepare the SQL statement to insert new entry
    $query = "INSERT INTO kelas (kode_kelas, tahun_ajar, kelas, nama_kelas, kode_guru, status_aktif) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $kode_kelas, $tahun_ajar, $kelas, $nama_kelas, $kode_guru, $status_aktif);

    if ($stmt->execute()) {
        echo "<script>alert('Data Berhasil dimasukan!'); window.location = 'kelas.php'</script>";    
    } else {
        echo "<script>alert('Data Gagal dimasukan!'); window.location = 'kelas.php'</script>";    
    }

    $stmt->close(); // Close the statement
}

$stmtCek->close(); // Close the check statement
$conn->close(); // Close the database connection
?>
