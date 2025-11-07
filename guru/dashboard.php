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
        <title>Dashboard | Si Akademik</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="Laode Muh ZulFardinsyah">

        <link rel="icon" href="https://cekmutasi.co.id/favicon.png" type="image/png">
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
            <?php } ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'menu.php'; ?>

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper" style="padding:1rem">
                                    <div class="alert alert-info background-info">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="icofont icofont-close-line-circled text-white"></i>
                                        </button>
                                        <strong>INFORMASI:</strong>
                                        <p>Selamat Datang Di Sistem Informasi Akademik Sekolah Musang</p>
                                    </div>
                                    <div class="page-body">
                                        <div class="row">

                                            <div class="col-md-6 col-xl-3">
                                                <?php 
                                                $tampil = $conn->query("SELECT * FROM guru ORDER BY kode_guru DESC");
                                                $total = $tampil->num_rows;
                                                ?>
                                                <div class="card widget-card-1" style="margin-top:10px">
                                                    <div class="card-block-small">
                                                        <i class="icofont-wallet bg-c-blue card1-icon"></i>
                                                        <span class="text-c-blue f-w-600">Guru</span>
                                                        <h4><?php echo $total; ?></h4>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-blue f-16 icofont icofont-info-circle m-r-10"></i>Jumlah Guru 
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <?php 
                                                $tampil = $conn->query("SELECT * FROM siswa ORDER BY kode_siswa DESC");
                                                $total_siswa = $tampil->num_rows;
                                                ?>
                                                <div class="card widget-card-1" style="margin-top:10px">
                                                    <div class="card-block-small">
                                                        <i class="icofont icofont-package bg-c-pink card1-icon"></i>
                                                        <span class="text-c-pink f-w-600">Siswa</span>
                                                        <h4><?php echo $total_siswa; ?></h4>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-pink f-16 icofont icofont-info-circle m-r-10"></i>Jumlah Siswa
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <?php 
                                                $tampil = $conn->query("SELECT * FROM pelajaran ORDER BY kode_pelajaran DESC");
                                                $total_pelajaran = $tampil->num_rows;
                                                ?>
                                                <div class="card widget-card-1" style="margin-top:10px">
                                                    <div class="card-block-small">
                                                        <i class="icofont icofont-tack-pin bg-c-green card1-icon"></i>
                                                        <span class="text-c-green f-w-600">Mata Pelajaran</span>
                                                        <h4><?php echo $total_pelajaran; ?></h4>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-green f-16 icofont icofont-info-circle m-r-10"></i>Jumlah Mata Pelajaran
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-xl-3">
                                                <?php 
                                                $tampil = $conn->query("SELECT * FROM kelas ORDER BY kode_kelas DESC");
                                                $total_kelas = $tampil->num_rows;
                                                ?>
                                                <div class="card widget-card-1" style="margin-top:10px">
                                                    <div class="card-block-small">
                                                        <i class="icofont icofont-database bg-c-yellow card1-icon"></i>
                                                        <span class="text-c-yellow f-w-600">Kelas</span>
                                                        <h4><?php echo $total_kelas; ?></h4>
                                                        <div>
                                                            <span class="f-left m-t-10 text-muted">
                                                                <i class="text-c-yellow f-16 icofont icofont-info-circle m-r-10"></i>Jumlah Kelas
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
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
    </div>

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
