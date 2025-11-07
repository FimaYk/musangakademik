<?php
$namafolder = "gambar_siswa/"; // Tempat menyimpan file
include "../conn.php"; // Ensure this file establishes a mysqli connection

if (!empty($_FILES["nama_file"]["tmp_name"])) {
    $jenis_gambar = $_FILES['nama_file']['type'];
    $kode_siswa = $_POST['kode_siswa'];
    $nis = $_POST['nis'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelamin = $_POST['kelamin'];
    $agama = $_POST['agama'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $tahun_angkatan = $_POST['tahun_angkatan'];
    $status = $_POST['status'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($jenis_gambar == "image/jpeg" || $jenis_gambar == "image/jpg" || $jenis_gambar == "image/gif" || $jenis_gambar == "image/png") {
        $gambar = $namafolder . basename($_FILES['nama_file']['name']);
        
        if (move_uploaded_file($_FILES['nama_file']['tmp_name'], $gambar)) {
            // Prepare the SQL statement
            $sql = "INSERT INTO siswa (kode_siswa, nis, nama_siswa, kelamin, agama, tempat_lahir, tanggal_lahir, alamat, no_telepon, tahun_angkatan, status, username, password, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssisss", $kode_siswa, $nis, $nama_siswa, $kelamin, $agama, $tempat_lahir, $tanggal_lahir, $alamat, $no_telepon, $tahun_angkatan, $status, $username, $password, $gambar);

            if ($stmt->execute()) {
                echo "<script>alert('Gambar Berhasil Di Input'); window.location = 'siswa.php'</script>";   
            } else {
                echo "<script>alert('Data Gagal dimasukan!'); window.location = 'input-siswa.php'</script>";    
            }

            $stmt->close(); // Close the statement
        } else {
            echo "<script>alert('Gambar gagal dikirim'); window.location = 'input-siswa.php'</script>";
        }
    } else {
        echo "<script>alert('Jenis gambar yang anda kirim salah. Harus .jpg .gif .png'); window.location = 'input-siswa.php'</script>";
    }
} else {
    echo "<script>alert('Anda belum memilih gambar'); window.location = 'input-siswa.php'</script>";
}

$conn->close(); // Close the database connection
?>
