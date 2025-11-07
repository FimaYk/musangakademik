<?php 
session_start();
if (empty($_SESSION['username'])) {
    header('location:../dashboard');    
    exit();
} else {
    include "../conn.php"; // Ensure this uses MySQLi or PDO
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Laporan Nilai Siswa | Si Akademik</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Laode Muh ZulFardinsyah">

    <link rel="icon" href="../assets/images/favicon.png" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/themify-icons/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../assets/icon/icofont/css/icofont.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/pages/menu-search/css/component.css">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/pnotify/css/pnotify.css">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/pnotify/css/pnotify.brighttheme.css">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/pnotify/css/pnotify.buttons.css">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/pnotify/css/pnotify.history.css">
    <link rel="stylesheet" type="text/css" href="../assets/bower_components/pnotify/css/pnotify.mobile.css">
    <link rel="stylesheet" type="text/css" href="../assets/pages/pnotify/notify.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/jquery.mCustomScrollbar.css">
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
                    exit();
                }
            }
            $_SESSION['start_time'] = time();
            ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'menu.php'; ?>

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="card">
                                    <?php
                                    $kodeku = $_GET['kd'];
                                    $tampil = $conn->query("SELECT nilai.*, pelajaran.nama_pelajaran, siswa.nama_siswa, siswa.nis, kelas_siswa.jurusan FROM nilai
                                        LEFT JOIN pelajaran ON nilai.kode_pelajaran = pelajaran.kode_pelajaran
                                        LEFT JOIN siswa ON nilai.kode_siswa = siswa.kode_siswa
                                        LEFT JOIN kelas_siswa ON nilai.kode_siswa = kelas_siswa.kode_siswa
                                        WHERE nilai.id = $kodeku ORDER BY semester, kode_pelajaran ASC");
                                    ?>
                                    <div class="card-header">
                                        <h2>Cetak Laporan Nilai Siswa</h2>
                                        <div class="text-right">
                                            <a class="btn btn-sm btn-danger tooltips" data-placement="bottom" data-original-title="Print Nilai" href="nilai_pdf.php?hal=edit-admin&kd=<?php echo $kodeku;?>"><i class="icofont-print"></i> Cetak</a>
                                        </div>
                                    </div>
                                    <div class="card-block table-border-style">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped">
                                                <?php while($data = $tampil->fetch_assoc()) { ?>
                                                    <tr>
                                                        <td>Kode Siswa</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['kode_siswa']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>NIS</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['nis']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nama Siswa</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['nama_siswa']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jurusan</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['jurusan']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Semester</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['semester']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mata Pelajaran</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['nama_pelajaran']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nilai Tugas 1</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['nilai_tugas1']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nilai Tugas 2</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['nilai_tugas2']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nilai UTS</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['nilai_uts']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nilai UAS</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['nilai_uas']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Nilai</td>
                                                        <td>:</td>
                                                        <td><?php 
                                                            $total = $data['nilai_tugas1'] + $data['nilai_tugas2'] + $data['nilai_uts'] + $data['nilai_uas'];
                                                            echo $total;
                                                        ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Nilai Rata Rata</td>
                                                        <td>:</td>
                                                        <td><?php 
                                                            $rata2 = $total / 4;
                                                            echo $rata2; 
                                                        ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Keterangan</td>
                                                        <td>:</td>
                                                        <td><?php echo $data['keterangan']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                            <div class="text-left">
                                                <p>Kepala Sekolah,</p>
                                                <p>HERMANSYAH, S.Kom, M.Kom</p>
                                                <p>Diterima Oleh, <?php echo $data['nama_siswa']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="styleSelector"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remove outdated IE warning -->
        <script type="text/javascript" src="../assets/bower_components/jquery/js/jquery.min.js"></script>
        <script type="text/javascript" src="../assets/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../assets/bower_components/popper.js/js/popper.min.js"></script>
        <script type="text/javascript" src="../assets/bower_components/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../assets/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
        <script type="text/javascript" src="../assets/bower_components/modernizr/js/modernizr.js"></script>

        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.desktop.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.buttons.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.confirm.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.callbacks.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.animate.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.history.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.mobile.js"></script>
        <script type="text/javascript" src="../assets/bower_components/pnotify/js/pnotify.nonblock.js"></script>

        <script type="text/javascript" src="../assets/js/SmoothScroll.js"></script>
        <script src="../assets/js/pcoded.min.js"></script>
        <script src="../assets/js/demo-12.js"></script>
        <script src="../assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
        <script type="text/javascript" src="../assets/js/script.min.js"></script>
        <script type="text/javascript" src="../assets/js/date.format.js"></script>
        <script type="text/javascript" src="../assets/js/numberformat.js"></script>

    </body>
</html>
<?php } ?>
