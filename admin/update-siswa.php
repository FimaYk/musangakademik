   <?php
   include "../conn.php"; // Koneksi ke database

   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
       $gambar = $_POST['gambar']; // Jika ada upload gambar

       // Query untuk memperbarui data
       $query = "UPDATE siswa SET 
           nis='$nis', 
           nama_siswa='$nama_siswa', 
           kelamin='$kelamin', 
           agama='$agama', 
           tempat_lahir='$tempat_lahir', 
           tanggal_lahir='$tanggal_lahir', 
           alamat='$alamat', 
           no_telepon='$no_telepon', 
           tahun_angkatan='$tahun_angkatan', 
           status='$status', 
           username='$username', 
           password='$password', 
           gambar='$gambar' 
           WHERE kode_siswa='$kode_siswa'";

       if ($conn->query($query) === TRUE) {
           echo "<script>alert('Data berhasil diperbarui!'); window.location = 'siswa.php';</script>";
       } else {
           echo "Error: " . $query . "<br>" . $conn->error;
       }
   }
   ?>
   