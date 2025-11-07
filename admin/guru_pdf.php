<?php
session_start();
if (empty($_SESSION['username'])) {
    header('location:../index.php');    
    exit();
} else {
    include "../conn.php";
    require('../fpdf17/fpdf.php');
    require('../conn.php');

    // Select the Products you want to show in your PDF file
    $result = $conn->query("SELECT * From guru ORDER BY kode_guru") or die($conn->error);

    // Initialize the 3 columns and the total
    $column_kode_siswa = "";
    $column_nis = "";
    $column_nama = "";
    $column_kelamin = "";
    $column_agama = "";
    $column_tempat = "";
    $column_tanggal = "";

    // For each row, add the field to the corresponding column
    while($row = $result->fetch_assoc()) {
        $kode = $row["kode_guru"];
        $nis = $row["nip"];
        $nama = $row["nama_guru"];
        $kelamin = $row["kelamin"];
        $agama = $row["alamat"];
        $tempat = $row["no_telepon"];
        $tanggal = $row["status_aktif"];

        $column_kode_siswa = $column_kode_siswa.$kode."\n";
        $column_nis = $column_nis.$nis."\n";
        $column_nama = $column_nama.$nama."\n";
        $column_kelamin = $column_kelamin.$kelamin."\n";
        $column_agama = $column_agama.$agama."\n";
        $column_tempat = $column_tempat.$tempat."\n";
        $column_tanggal = $column_tanggal.$tanggal."\n";
    }

    // Create a new PDF file
    $pdf = new FPDF('L','mm',array(297,210)); // L For Landscape / P For Portrait
    $pdf->AddPage();

    $pdf->Image('../foto/logo.png',10,10,-175);
    $pdf->SetFont('Arial','B',13);
    $pdf->Cell(125);
    $pdf->Cell(30,10,'DATA GURU PENGAJAR',0,0,'C');
    $pdf->Ln();
    $pdf->Cell(125);
    $pdf->Cell(30,10,'Sistem Informasi Nilai Siswa (SiNiS)',0,0,'C');
    $pdf->Ln();

    // Fields Name position
    $Y_Fields_Name_position = 30;

    // First create each Field Name
    // Gray color filling each Field Name box
    $pdf->SetFillColor(110,180,230);
    // Bold Font for Field Name
    $pdf->SetFont('Arial','B',10);
    $pdf->SetY($Y_Fields_Name_position);
    $pdf->SetX(5);
    $pdf->Cell(20,8,'Kode',1,0,'C',1);
    $pdf->SetX(25);
    $pdf->Cell(25,8,'NIP',1,0,'C',1);
    $pdf->SetX(50);
    $pdf->Cell(40,8,'Nama',1,0,'C',1);
    $pdf->SetX(90);
    $pdf->Cell(25,8,'Kelamin',1,0,'C',1);
    $pdf->SetX(115);
    $pdf->Cell(110,8,'Alamat',1,0,'C',1);
    $pdf->SetX(225);
    $pdf->Cell(40,8,'No Telepon',1,0,'C',1);
    $pdf->SetX(265);
    $pdf->Cell(27,8,'Status',1,0,'C',1);
    $pdf->Ln();

    // Table position, under Fields Name
    $Y_Table_Position = 38;

    // Now show the columns
    $pdf->SetFont('Arial','',10);

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(5);
    $pdf->MultiCell(20,6,$column_kode_siswa,1,'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(25);
    $pdf->MultiCell(25,6,$column_nis,1,'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(50);
    $pdf->MultiCell(40,6,$column_nama,1,'L');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(90);
    $pdf->MultiCell(25,6,$column_kelamin,1,'C');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(115);
    $pdf->MultiCell(110,6,$column_agama,1,'L');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(225);
    $pdf->MultiCell(40,6,$column_tempat,1,'L');

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(265);
    $pdf->MultiCell(27,6,$column_tanggal,1,'C');

    $pdf->Output();
}
?>
