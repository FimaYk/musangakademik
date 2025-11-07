<?php
session_start();
if (empty($_SESSION['username'])) {
    header('location:../index.php');    
} else {
    include "../conn.php";
    require('../fpdf17/fpdf.php');

    // Select the students to show in your PDF file
    $result = $conn->query("SELECT * FROM siswa ORDER BY kode_siswa") or die($conn->error);

    // Initialize the columns
    $column_kode_siswa = "";
    $column_nis = "";
    $column_nama = "";
    $column_kelamin = "";
    $column_agama = "";
    $column_tempat = "";
    $column_tanggal = "";
    $column_alamat = "";
    $column_telepon = "";
    $column_tahun = "";
    $column_status = "";

    // For each row, add the field to the corresponding column
    while ($row = $result->fetch_assoc()) {
        $kode = $row["kode_siswa"];
        $nis = $row["nis"];
        $nama = $row["nama_siswa"];
        $kelamin = $row["kelamin"];
        $agama = $row["agama"];
        $tempat = $row["tempat_lahir"];
        $tanggal = $row["tanggal_lahir"];
        $alamat = $row["alamat"];
        $telepon = $row["no_telepon"];
        $tahun = $row["tahun_angkatan"];
        $status = $row["status"];

        $column_kode_siswa .= $kode . "\n";
        $column_nis .= $nis . "\n";
        $column_nama .= $nama . "\n";
        $column_kelamin .= $kelamin . "\n";
        $column_agama .= $agama . "\n";
        $column_tempat .= $tempat . "\n";
        $column_tanggal .= $tanggal . "\n";
        $column_alamat .= $alamat . "\n";
        $column_telepon .= $telepon . "\n";
        $column_tahun .= $tahun . "\n";
        $column_status .= $status . "\n";
    }

    // Create a new PDF file
    $pdf = new FPDF('L', 'mm', array(350, 210)); // L For Landscape / P For Portrait
    $pdf->AddPage();
    $pdf->Image('../foto/logo.png', 10, 10, -175);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(150);
    $pdf->Cell(30, 10, 'DATA SISWA', 0, 0, 'C');
    $pdf->Ln();
    $pdf->Cell(150);
    $pdf->Cell(30, 10, 'Sistem Informasi Akademik', 0, 0, 'C');
    $pdf->Ln();

    // Fields Name position
    $Y_Fields_Name_position = 30;

    // First create each Field Name
    // Gray color filling each Field Name box
    $pdf->SetFillColor(110, 180, 230);
    // Bold Font for Field Name
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetY($Y_Fields_Name_position);
    $pdf->SetX(5);
    $pdf->Cell(20, 8, 'Kode', 1, 0, 'C', 1);
    $pdf->SetX(25);
    $pdf->Cell(25, 8, 'NIS', 1, 0, 'C', 1);
    $pdf->SetX(50);
    $pdf->Cell(40, 8, 'Nama', 1, 0, 'C', 1);
    $pdf->SetX(90);
    $pdf->Cell(25, 8, 'Kelamin', 1, 0, 'C', 1);
    $pdf->SetX(115);
    $pdf->Cell(25, 8, 'Agama', 1, 0, 'C', 1);
    $pdf->SetX(140);
    $pdf->Cell(25, 8, 'Tempat Lahir', 1, 0, 'C', 1);
    $pdf->SetX(165);
    $pdf->Cell(25, 8, 'Tanggal Lahir', 1, 0, 'C', 1);
    $pdf->SetX(190);
    $pdf->Cell(100, 8, 'Alamat', 1, 0, 'C', 1);
    $pdf->SetX(290);
    $pdf->Cell(20, 8, 'T.A', 1, 0, 'C', 1);
    $pdf->SetX(310);
    $pdf->Cell(35, 8, 'Status', 1, 0, 'C', 1);
    $pdf->Ln();

    // Table position, under Fields Name
    $Y_Table_Position = 38;

    // Now show the columns
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(5);
    $pdf->MultiCell(20, 6, $column_kode_siswa, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(25);
    $pdf->MultiCell(25, 6, $column_nis, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(50);
    $pdf->MultiCell(40, 6, $column_nama, 1, 'L');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(90);
    $pdf->MultiCell(25, 6, $column_kelamin, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(115);
    $pdf->MultiCell(25, 6, $column_agama, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(140);
    $pdf->MultiCell(25, 6, $column_tempat, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(165);
    $pdf->MultiCell(25, 6, $column_tanggal, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(190);
    $pdf->MultiCell(100, 6, $column_alamat, 1, 'L');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(290);
    $pdf->MultiCell(20, 6, $column_tahun, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(310);
    $pdf->MultiCell(35, 6, $column_status, 1, 'C');

    // Output the PDF
    $pdf->Output();
}
?>
