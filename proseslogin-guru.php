<?php
include("conn.php");
date_default_timezone_set('Asia/Jakarta');

session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) && empty($password)) {
        header('location:login-guru.php?error1');
        exit();
    } else if (empty($username)) {
        header('location:login-guru.php?error=2');
        exit();
    } else if (empty($password)) {
        header('location:login-guru.php?error=3');
        exit();
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM guru WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); // "ss" means two string parameters
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['nama'] = $row['nama_guru'];
        $_SESSION['kode'] = $row['kode_guru'];
        $_SESSION['gambar'] = $row['gambar'];

        header('location:guru/nilai');
    } else {
        header('location:login-guru.php?error=4');
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
