<?php
include "../conn.php"; // Ensure this file establishes a mysqli connection

$kode_kelas = $_POST['kode_kelas'];
$kode_siswa = $_POST['kode_siswa'];
$jurusan = $_POST['jurusan'];

// Prepare the SQL statement to check for existing entries
$sqlCek = "SELECT * FROM kelas_siswa WHERE kode_kelas = ? AND kode_siswa = ?";
$stmtCek = $conn->prepare($sqlCek);
$stmtCek->bind_param("ss", $kode_kelas, $kode_siswa);
$stmtCek->execute();
$qryCek = $stmtCek->get_result();

if ($qryCek->num_rows >= 1) {
    echo "<script>alert('Maaf, Kode Kelas <b>$kode_kelas</b> dengan <b>Kode Siswa</b> yang sama sudah dibuat'); window.location = 'kelas-siswa.php'</script>";
} else {
    // Prepare the SQL statement to insert new entry
    $query = "INSERT INTO kelas_siswa (kode_kelas, kode_siswa, jurusan) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $kode_kelas, $kode_siswa, $jurusan);

    if ($stmt->execute()) {
        echo "<script>alert('Data Berhasil dimasukan!'); window.location = 'kelas-siswa.php'</script>";    
    } else {
        echo "<script>alert('Data Gagal dimasukan!'); window.location = 'kelas-siswa.php'</script>";    
    }

    $stmt->close(); // Close the statement
}

$stmtCek->close(); // Close the check statement
$conn->close(); // Close the database connection
?>
