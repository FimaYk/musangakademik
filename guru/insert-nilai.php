<?php
include "../conn.php"; // Ensure this uses MySQLi or PDO

$semester      = $_POST['semester'];
$kode_pelajaran = $_POST['kode_pelajaran'];
$kode_guru      = $_POST['kode_guru'];
$kode_kelas     = $_POST['kode_kelas'];
$kode_siswa     = $_POST['kode_siswa'];
$nilai_tugas1   = $_POST['nilai_tugas1'];
$nilai_tugas2   = $_POST['nilai_tugas2'];
$nilai_uts      = $_POST['nilai_uts'];
$nilai_uas      = $_POST['nilai_uas'];
$keterangan     = $_POST['keterangan'];

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO nilai (semester, kode_pelajaran, kode_guru, kode_kelas, kode_siswa, nilai_tugas1, nilai_tugas2, nilai_uts, nilai_uas, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisiiiddss", $semester, $kode_pelajaran, $kode_guru, $kode_kelas, $kode_siswa, $nilai_tugas1, $nilai_tugas2, $nilai_uts, $nilai_uas, $keterangan);

if ($stmt->execute()) {
    echo "<script>alert('Data Berhasil dimasukan!'); window.location = 'nilai'</script>";	
} else {
    echo "<script>alert('Data Gagal dimasukan!'); window.location = 'nilai'</script>";	
}

$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>
