<?php
session_start();
if (empty($_SESSION['username'])) {
    header('location:../index.php');    
} else {
    include "../conn.php";
    require('../fpdf17/fpdf.php');

    // Select the subjects to show in your PDF file
    $result = $conn->query("SELECT * FROM pelajaran ORDER BY kode_pelajaran ASC");

    // Initialize the columns
    $column_kode_pelajaran = "";
    $column_nama_pelajaran = "";
    $column_keterangan = "";

    // For each row, add the field to the corresponding column
    while ($row = $result->fetch_assoc()) {
        $kode = $row["kode_pelajaran"];
        $nama = $row["nama_pelajaran"];
        $keterangan = $row["keterangan"];

        $column_kode_pelajaran .= $kode . "\n";
        $column_nama_pelajaran .= $nama . "\n";
        $column_keterangan .= $keterangan . "\n";
    }

    // Create a new PDF file
    $pdf = new FPDF('P', 'mm', array(210, 297)); // L For Landscape / P For Portrait
    $pdf->AddPage();
    $pdf->Image('../foto/logo.png', 10, 10, -175);
    $pdf->SetFont('Arial', 'B', 13);
    $pdf->Cell(80);
    $pdf->Cell(30, 10, 'DATA PELAJARAN', 0, 0, 'C');
    $pdf->Ln();
    $pdf->Cell(80);
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
    $pdf->SetX(10);
    $pdf->Cell(40, 8, 'Kode Pelajaran', 1, 0, 'C', 1);
    $pdf->SetX(50);
    $pdf->Cell(110, 8, 'Nama Pelajaran', 1, 0, 'C', 1);
    $pdf->SetX(160);
    $pdf->Cell(40, 8, 'Keterangan', 1, 0, 'C', 1);
    $pdf->Ln();

    // Table position, under Fields Name
    $Y_Table_Position = 38;

    // Now show the columns
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(10);
    $pdf->MultiCell(40, 6, $column_kode_pelajaran, 1, 'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(50);
    $pdf->MultiCell(110, 6, $column_nama_pelajaran, 1, 'L');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(160);
    $pdf->MultiCell(40, 6, $column_keterangan, 1, 'C');

    // Output the PDF
    $pdf->Output();
}
?>
