<?php
session_start();
if (empty($_SESSION['username'])) {
    header('location:../login-siswa');
    exit();
}

include "../conn.php"; // Ensure this file uses mysqli or PDO
require('../fpdf17/fpdf.php');

$siswa = $_SESSION['kode'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT siswa.kode_siswa, siswa.nis, siswa.nama_siswa,
                                nilai.semester, pelajaran.nama_pelajaran,
                                nilai.nilai_tugas1, nilai.nilai_tugas2,
                                nilai.nilai_uts, nilai.nilai_uas, nilai.keterangan,
                                kelas_siswa.jurusan
                        FROM siswa
                        JOIN nilai ON siswa.kode_siswa = nilai.kode_siswa
                        JOIN pelajaran ON nilai.kode_pelajaran = pelajaran.kode_pelajaran
                        JOIN kelas_siswa ON kelas_siswa.kode_siswa = siswa.kode_siswa
                        WHERE siswa.kode_siswa = ?");
$stmt->bind_param("s", $siswa);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

// Create a new PDF file
$pdf = new FPDF('P', 'mm', array(210, 297)); // L For Landscape / P For Portrait
$pdf->AddPage();
$pdf->Image('../foto/logo.png', 10, 10, -175);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(80);
$pdf->Cell(30, 10, 'DATA HASIL BELAJAR SISWA', 0, 0, 'C');
$pdf->Ln();
$pdf->Cell(80);
$pdf->Cell(30, 10, 'Raport Siswa', 0, 0, 'C');
$pdf->Ln();

// Check if data is available
if (!empty($data)) {
    $row = $data[0]; // Assuming all rows have the same student info
    $kode_siswa = $row["kode_siswa"];
    $nis = $row["nis"];
    $nama = $row["nama_siswa"];
    $jurusan = $row["jurusan"];
    $semester = $row["semester"];

    // Display student info
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(40, 8, 'Kode Siswa: ' . $kode_siswa, 0, 1);
    $pdf->Cell(40, 8, 'NIS: ' . $nis, 0, 1);
    $pdf->Cell(40, 8, 'Nama Siswa: ' . $nama, 0, 1);
    $pdf->Cell(40, 8, 'Jurusan: ' . $jurusan, 0, 1);
    $pdf->Cell(40, 8, 'Semester: ' . $semester, 0, 1);
    $pdf->Ln();

    // Table header
    $pdf->SetFillColor(110, 180, 230);
    $pdf->Cell(40, 8, 'Mata Pelajaran', 1, 0, 'C', 1);
    $pdf->Cell(20, 8, 'Tugas 1', 1, 0, 'C', 1);
    $pdf->Cell(20, 8, 'Tugas 2', 1, 0, 'C', 1);
    $pdf->Cell(20, 8, 'UTS', 1, 0, 'C', 1);
    $pdf->Cell(20, 8, 'UAS', 1, 0, 'C', 1);
    $pdf->Cell(40, 8, 'Total Nilai', 1, 0, 'C', 1);
    $pdf->Cell(40, 8, 'Nilai Rata Rata', 1, 0, 'C', 1);
    $pdf->Ln();

    // Table data
    $pdf->SetFont('Arial', '', 10);
    foreach ($data as $row) {
        $nilai = $row["nilai_tugas1"] + $row["nilai_tugas2"] + $row["nilai_uts"] + $row["nilai_uas"];
        $rata = $nilai / 4;

        $pdf->Cell(40, 8, $row["nama_pelajaran"], 1);
        $pdf->Cell(20, 8, $row["nilai_tugas1"], 1);
        $pdf->Cell(20, 8, $row["nilai_tugas2"], 1);
        $pdf->Cell(20, 8, $row["nilai_uts"], 1);
        $pdf->Cell(20, 8, $row["nilai_uas"], 1);
        $pdf->Cell(40, 8, $nilai, 1);
        $pdf->Cell(40, 8, $rata, 1);
        $pdf->Ln();
    }

    // Footer
    $pdf->SetY(-40);
    $pdf->Cell(40, 8, 'Keterangan', 1);
    $pdf->Cell(160, 8, $row['keterangan'], 1);
    $pdf->Ln(10);
    $pdf->Cell(40, 8, 'Kepala Sekolah,', 0, 0, 'L');
    $pdf->Cell(160, 8, '', 0, 0, 'R');
    $pdf->Ln(10);
    $pdf->Cell(40, 8, 'Hakko Bio Richard, A.Md Kom', 0, 0, 'L');
    $pdf->Output();
} else {
    $pdf->Cell(0, 10, 'No data available', 0, 1, 'C');
    $pdf->Output();
}
?>
