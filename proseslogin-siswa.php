<?php
include("conn.php");
date_default_timezone_set('Asia/Jakarta');

session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from POST request
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) && empty($password)) {
        header('location:login.html?error=1'); // Both fields are empty
        exit();
    } else if (empty($username)) {
        header('location:login.html?error=2'); // Username is empty
        exit();
    } else if (empty($password)) {
        header('location:login.html?error=3'); // Password is empty
        exit();
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("SELECT * FROM siswa WHERE nis = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify the password
       if ($password == $row['password']) {

            // Password is correct, set session variables
            $_SESSION['username'] = $username;
            $_SESSION['nama'] = $row['nama_siswa'];
            $_SESSION['kode'] = $row['kode_siswa'];
            $_SESSION['gambar'] = $row['gambar'];

            // Regenerate session ID for security
            session_regenerate_id(true);
            header('location:siswa/index.php');
            exit();
        } else {
            header('location:login-siswa.php?error=4'); // Incorrect password
            exit();
        }
    } else {
        header('location:login-siswa.php?error=5'); // User not found
        exit();
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

