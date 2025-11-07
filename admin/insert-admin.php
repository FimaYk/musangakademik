<?php
$namafolder = "gambar_admin/"; // Tempat menyimpan file
include "../conn.php"; // Ensure this file establishes a mysqli connection

if (!empty($_FILES["nama_file"]["tmp_name"])) {
    $jenis_gambar = $_FILES['nama_file']['type'];
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $fullname = $_POST['fullname'];

    // Validate inputs
    if (empty($user_id) || empty($username) || empty($password) || empty($fullname)) {
        echo "Semua field harus diisi.";
        exit();
    }

    if ($jenis_gambar == "image/jpeg" || $jenis_gambar == "image/jpg" || $jenis_gambar == "image/gif" || $jenis_gambar == "image/x-png") {
        $gambar = $namafolder . basename($_FILES['nama_file']['name']);
        
        if (move_uploaded_file($_FILES['nama_file']['tmp_name'], $gambar)) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO user (user_id, username, password, fullname, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $user_id, $username, $password, $fullname, $gambar);

            // Execute the statement and check for errors
            if ($stmt->execute()) {
                echo "Gambar berhasil dikirim ke direktori " . htmlspecialchars($gambar);
                echo "<p>User Id  : " . htmlspecialchars($user_id) . "</p>";
                echo "<p>Username : " . htmlspecialchars($username) . "</p>";
                echo "<p>Password : " . htmlspecialchars($password) . "</p>";
                echo "<p>Fullname : " . htmlspecialchars($fullname) . "</p>";
                echo "<p>Gambar   : " . htmlspecialchars($gambar) . "</p>";
                echo "<h3><a href='input-admin.php'> Input Lagi</a></h3>";
            } else {
                echo "<p>Data gagal dimasukkan ke database: " . htmlspecialchars($stmt->error) . "</p>";
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
