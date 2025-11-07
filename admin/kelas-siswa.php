<?php 
session_start();
if (empty($_SESSION['username'])) {
    header('location:../dashboard');    
} else {
    include "../conn.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Kelas Siswa | Si Akademik</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="author" content="Laode Muh ZulFardinsyah">
    <link rel="icon" href="https://cekmutasi.co.id/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/icofont/css/icofont.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/pages/menu-search/css/component.css">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/pnotify/css/pnotify.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/custom.css">
</head>
<body>
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
            </div>
        </div>
    </div>

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php include 'menu2.php'; ?>

            <?php
            $timeout = 10; // Set timeout minutes
            $logout_redirect_url = "../index"; // Set logout URL

            $timeout = $timeout * 60; // Converts minutes to seconds
            if (isset($_SESSION['start_time'])) {
                $elapsed_time = time() - $_SESSION['start_time'];
                if ($elapsed_time >= $timeout) {
                    session_destroy();
                    echo "<script>alert('Session Anda Telah Habis!'); window.location = '$logout_redirect_url'</script>";
                }
            }
            $_SESSION['start_time'] = time();
            ?>
        </div>
    </div>

    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <?php include 'menu.php'; ?>

            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="card">
                            <div class="card-header">
                                <h5>Data Kelas Siswa</h5>
                            </div>
                            <div class="card-block table-border-style">
                                <div class="button-panel" style="padding: 0px 20px 10px 20px; float:left">
                                    <a class="btn btn-primary btn-bordered btn-mini pull-right" href="input-kelas-siswa">
                                        <i class="icofont icofont-plus-circle"></i>Tambah
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <?php
                                    // Prepare the SQL statement
                                    $query1 = "SELECT kelas_siswa.id, kelas_siswa.kode_kelas, kelas_siswa.jurusan, kelas.tahun_ajar, kelas.kelas, kelas.nama_kelas, guru.nama_guru,
                                    siswa.nis, siswa.nama_siswa, siswa.tahun_angkatan, siswa.status
                                    FROM kelas_siswa
                                    JOIN kelas ON kelas_siswa.kode_kelas = kelas.kode_kelas
                                    JOIN guru ON kelas.kode_guru = guru.kode_guru
                                    JOIN siswa ON siswa.kode_siswa = kelas_siswa.kode_siswa";

                                    $tampil = $conn->query($query1);

                                    if (!$tampil) {
                                        die("Query failed: " . $conn->error);
                                    }
                                    ?>
                                    <table class="table table-hover table-striped" style="font-size: 13px;">
                                        <thead>
                                            <tr>
                                                <th>Id <i class="icofont-sort"></i></th>
                                                <th>Kode Kelas <i class="icofont-sort"></i></th>
                                                <th>Jurusan <i class="icofont-sort"></i></th>
                                                <th>Tahun Ajar <i class="icofont-sort"></i></th>
                                                <th>Kelas <i class="icofont-sort"></i></th>
                                                <th>Nama Kelas <i class="icofont-sort"></i></th>
                                                <th>Nama WaliKelas <i class="icofont-sort"></i></th>
                                                <th>Nis <i class="icofont-sort"></i></th>
                                                <th>Nama Siswa <i class="icofont-sort"></i></th>
                                                <th>Tahun Angkatan <i class="icofont-sort"></i></th>
                                                <th>Status Siswa <i class="icofont-sort"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($data = $tampil->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php echo $data['id']; ?></td>
                                                    <td><?php echo $data['kode_kelas']; ?></td>
                                                    <td><?php echo $data['jurusan']; ?></td>
                                                    <td><?php echo $data['tahun_ajar']; ?></td>
                                                    <td><?php echo $data['kelas']; ?></td>
                                                    <td><?php echo $data['nama_kelas']; ?></td>
                                                    <td><?php echo $data['nama_guru']; ?></td>
                                                    <td><?php echo $data['nis']; ?></td>
                                                    <td><?php echo $data['nama_siswa']; ?></td>
                                                    <td><?php echo $data['tahun_angkatan']; ?></td>
                                                    <td><?php echo $data['status']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="styleSelector"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="../assets/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="../assets/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <script type="text/javascript" src="../assets/js/pcoded.min.js"></script>
    <script type="text/javascript" src="../assets/js/script.min.js"></script>
</body>
</html>

<?php 
}
?>
