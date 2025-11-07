<?php
include "../conn.php";

// Get the POST data
$semester = $_POST['semester'];
$kode_pelajaran = $_POST['kode_pelajaran'];
$kode_guru = $_POST['kode_guru'];
$kode_kelas = $_POST['kode_kelas'];
$kode_siswa = $_POST['kode_siswa'];
$nilai_tugas1 = $_POST['nilai_tugas1'];
$nilai_tugas2 = $_POST['nilai_tugas2'];
$nilai_uts = $_POST['nilai_uts'];
$nilai_uas = $_POST['nilai_uas'];
$keterangan = $_POST['keterangan'];

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE nilai SET semester=?, kode_pelajaran=?, kode_guru=?, kode_kelas=?, nilai_tugas1=?, nilai_tugas2=?, nilai_uts=?, nilai_uas=?, keterangan=? WHERE kode_siswa=?");
$stmt->bind_param("ssssssssss", $semester, $kode_pelajaran, $kode_guru, $kode_kelas, $nilai_tugas1, $nilai_tugas2, $nilai_uts, $nilai_uas, $keterangan, $kode_siswa);

// Execute the statement
if ($stmt->execute()) {
    header('location:nilai');
} else {
    echo "Update failed: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
