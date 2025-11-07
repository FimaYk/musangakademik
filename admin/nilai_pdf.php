<?php
session_start();
if (empty($_SESSION['username'])) {
    header('location:../index.php');    
} else {
    include "../conn.php";
    require('../fpdf17/fpdf.php');

    // Get the student ID from the URL
    $kodesaya = $_GET['kd'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT nilai.*, pelajaran.nama_pelajaran, siswa.nama_siswa, siswa.nis, kelas_siswa.jurusan FROM nilai
                             LEFT JOIN pelajaran ON nilai.kode_pelajaran = pelajaran.kode_pelajaran
                             LEFT JOIN siswa ON nilai.kode_siswa = siswa.kode_siswa
                             LEFT JOIN kelas_siswa ON nilai.kode_siswa = kelas_siswa.kode_siswa
                             WHERE nilai.id = ? ORDER BY semester, kode_pelajaran ASC");
    $stmt->bind_param("i", $kodesaya);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize the columns
    $column_date = "";
    $column_time = "";
    $column_standmeter = "";
    $column_factor = "";
    $column_total = "";
    $column_nilai = "";
    $column_rata = "";

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

    // Fields Name position
    $Y_Fields_Name_position = 40;
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetY($Y_Fields_Name_position);

    // Fetch data and populate PDF
    while ($row = $result->fetch_assoc()) {
        $kode_siswa = $row["kode_siswa"];
        $nis = $row["nis"];
        $nama = $row["nama_siswa"];
        $jurusan = $row["jurusan"];
        $semester = $row["semester"];
        $date = $row["nama_pelajaran"];
        $time = $row["nilai_tugas1"];
        $standmeter = $row["nilai_tugas2"];
        $factor = $row["nilai_uts"];
        $total = $row["nilai_uas"];
        $ket = $row["keterangan"];
        $nilai = $row["nilai_tugas1"] + $row["nilai_tugas2"] + $row["nilai_uts"] + $row["nilai_uas"];
        $rata = $nilai / 4;

        // Populate columns
        $column_date .= $date . "\n";
        $column_time .= $time . "\n";
        $column_standmeter .= $standmeter . "\n";
        $column_factor .= $factor . "\n";
        $column_total .= $total . "\n";
        $column_nilai .= $nilai . "\n";
        $column_rata .= $rata . "\n";
    }

    // Create table headers
    $Y_Fields_Name_position = 71;
    $pdf->SetFillColor(110, 180, 230);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetY($Y_Fields_Name_position);
    $pdf->SetX(5);
    $pdf->Cell(40, 8, 'Mata Pelajaran', 1, 0, 'C', 1);
    $pdf->SetX(45);
    $pdf->Cell(20, 8, 'Tugas 1', 1, 0, 'C', 1);
    $pdf->SetX(65);
    $pdf->Cell(20, 8, 'Tugas 2', 1, 0, 'C', 1);
    $pdf->SetX(85);
    $pdf->Cell(20, 8, 'UTS', 1, 0, 'C', 1);
    $pdf->SetX(105);
    $pdf->Cell(20, 8, 'UAS', 1, 0, 'C', 1);
    $pdf->SetX(125);
    $pdf->Cell(40, 8, 'Total Nilai', 1, 0, 'C', 1);
    $pdf->SetX(165);
    $pdf->Cell(40, 8, 'Nilai Rata Rata', 1, 0, 'C', 1);
    $pdf->Ln();

    // Populate table data
    $Y_Table_Position = 79;
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(5);
    $pdf->MultiCell(40, 6, $column_date, 1, 'C');
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(45);
    $pdf->MultiCell(20, 6, $column_time, 1, 'C');
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(65);
    $pdf->MultiCell(20, 6, $column_standmeter, 1, 'C');
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(85);
    $pdf->MultiCell(20, 6, $column_factor, 1, 'C');
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(105);
    $pdf->MultiCell(20, 6, $column_total, 1, 'C');
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(125);
    $pdf->MultiCell(40, 6, $column_nilai, 1, 'C');
    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(165);
    $pdf->MultiCell(40, 6, $column_rata, 1, 'C');

    // Footer Table
    $pdf->SetFillColor(110, 180, 230);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetX(5);
    $pdf->Cell(40, 8, 'Keterangan', 1, 0, 'C', 1);
    $pdf->SetX(45);
    $pdf->Cell(160, 8, $ket, 1, 0, 'R', 1);
    $pdf->Ln();
    $pdf->SetFillColor(110, 180, 230);
    $pdf->Ln(10);

    // After Footer
    $Y_Fields_Name_position = 150;
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetY($Y_Fields_Name_position);
    $pdf->SetX(5);
    $pdf->Cell(40, 8, 'Kepala Sekolah,', 0, 0, 'L', 1);
    $pdf->SetX(160);
    $pdf->Cell(40, 8, '', 0, 0, 'L', 1);
    $pdf->SetX(85);
    $pdf->Cell(50, 8, '', 0, 0, 'C', 1);
    $pdf->SetX(135);
    $pdf->Cell(25, 8, '', 0, 0, 'C', 1);
    $pdf->Ln();

    $Y_Fields_Name_position = 170;
    $pdf->SetFillColor(255, 255, 255);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetY($Y_Fields_Name_position);
    $pdf->SetX(5);
    $pdf->Cell(40, 8, 'Hakko Bio Richard, A.Md Kom', 0, 0, 'L', 1);
    $pdf->SetX(160);
    $pdf->Cell(40, 8, '', 0, 0, 'L', 1);
    $pdf->SetX(85);
    $pdf->Cell(50, 8, '', 0, 0, 'C', 1);
    $pdf->SetX(135);
    $pdf->Cell(25, 8, '', 0, 0, 'C', 1);
    $pdf->Ln();

    // Output the PDF
    $pdf->Output();
}
?>
