<?php
$namafolder = "gambar_guru/"; // Tempat menyimpan file
include "../conn.php"; // Ensure this file establishes a mysqli connection

if (!empty($_FILES["nama_file"]["tmp_name"])) {
    $jenis_gambar = $_FILES['nama_file']['type'];
    $kode_guru = $_POST['kode_guru'];
    $nip = $_POST['nip'];
    $nama_guru = $_POST['nama_guru'];
    $kelamin = $_POST['kelamin'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $status_aktif = $_POST['status_aktif'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($jenis_gambar == "image/jpeg" || $jenis_gambar == "image/jpg" || $jenis_gambar == "image/gif" || $jenis_gambar == "image/x-png") {
        $gambar = $namafolder . basename($_FILES['nama_file']['name']);
        
        if (move_uploaded_file($_FILES['nama_file']['tmp_name'], $gambar)) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO guru (kode_guru, nip, nama_guru, kelamin, alamat, no_telepon, status_aktif, username, password, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $kode_guru, $nip, $nama_guru, $kelamin, $alamat, $no_telepon, $status_aktif, $username, $password, $gambar);

            if ($stmt->execute()) {
                echo "Gambar berhasil dikirim ke direktori " . htmlspecialchars($gambar);
                echo "<h3>Gambar Berhasil Di Input <a href='input-guru.php'> Input Lagi</a></h3>";
            } else {
                echo "<p>Data gagal dimasukkan ke database.</p>";
            }

            $stmt->close(); // Close the statement
        } else {
            echo "<p>Gambar gagal dikirim</p>";
        }
    } else {
        echo "Jenis gambar yang anda kirim salah. Harus .jpg .gif .png";
    }
} else {
    echo "Anda belum memilih gambar";
}

$conn->close(); // Close the database connection
?>
