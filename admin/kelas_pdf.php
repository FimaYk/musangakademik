<?php
session_start();
if (empty($_SESSION['username'])) {
    header('location:../index.php');    
} else {
    include "../conn.php";
    require('../fpdf17/fpdf.php');

    // Select the Products you want to show in your PDF file
    $result = $conn->query("SELECT kelas.*, guru.nama_guru FROM kelas
                LEFT JOIN guru ON kelas.kode_guru = guru.kode_guru
                ORDER BY kode_kelas ASC");

    // Check for query execution
    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    // Initialize the columns
    $column_kode_kelas = "";
    $column_tahun_ajar = "";
    $column_kelas = "";
    $column_nama_kelas = "";
    $column_nama_guru = "";
    $column_status = "";

    // For each row, add the field to the corresponding column
    while ($row = $result->fetch_assoc()) {
        $column_kode_kelas .= $row["kode_kelas"] . "\n";
        $column_tahun_ajar .= $row["tahun_ajar"] . "\n";
        $column_kelas .= $row["kelas"] . "\n";
        $column_nama_kelas .= $row["nama_kelas"] . "\n";
        $column_nama_guru .= $row["nama_guru"] . "\n";
        $column_status .= $row["status_aktif"] . "\n";
    }

    // Create a new PDF file
    $pdf = new FPDF('P', 'mm', array(210, 297)); // L For Landscape / P For Portrait
    $pdf->AddPage();

    $pdf->Image('../foto/logo.png', 10, 10, -175);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, 'DATA KELAS', 0, 0, 'C');
    $pdf->Ln();
    $pdf->Cell(80);
    $pdf->Cell(30, 10, 'Sistem Informasi Nilai Siswa (SiNiS)', 0, 0, 'C');
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
    $pdf->Cell(25, 8, 'Kode Kelas', 1, 0, 'C', 1);
    $pdf->SetX(30);
    $pdf->Cell(25, 8, 'Tahun Ajaran', 1, 0, 'C', 1);
    $pdf->SetX(55);
    $pdf->Cell(20, 8, 'Kelas', 1, 0, 'C', 1);
    $pdf->SetX(75);
    $pdf->Cell(25, 8, 'Nama Kelas', 1, 0, 'C', 1);
    $pdf->SetX(100);
    $pdf->Cell(70, 8, 'Walikelas', 1, 0, 'C', 1);
    $pdf->SetX(170);
    $pdf->Cell(35, 8, 'Status', 1, 0, 'C', 1);
    $pdf->Ln();

    // Table position, under Fields Name
    $Y_Table_Position = 38;

    // Now show the columns
    $pdf->SetFont('Arial', '', 10);

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(5);
    $pdf->MultiCell(25, 6, $column_kode_kelas, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(30);
    $pdf->MultiCell(25, 6, $column_tahun_ajar, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(55);
    $pdf->MultiCell(20, 6, $column_kelas, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(75);
    $pdf->MultiCell(25, 6, $column_nama_kelas, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(100);
    $pdf->MultiCell(70, 6, $column_nama_guru, 1, 'L');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(170);
    $pdf->MultiCell(35, 6, $column_status, 1, 'C');

    $pdf->Output();
}
?>
