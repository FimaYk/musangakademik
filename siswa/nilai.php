<?php 
session_start();
if (empty($_SESSION['username'])) {
    header('location:../dashboard');    
    exit();
}

include "../conn.php"; // Ensure this file uses mysqli or PDO

// Session timeout logic
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

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Nilai Siswa | Si Akademik</title>
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
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <!-- Repeat for other rings -->
            </div>
        </div>
    </div>

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php include 'menu2.php'; ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'menu.php'; ?>

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Data Nilai Raport Siswa</h4>
                                    </div>
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><i class="icofont-user"></i> Nilai Raport Siswa: <?php echo $_SESSION['nama']; ?></h5> 
                                        <br>
                                        <div class="card-block table-border-style">
                                            <center><h4>DATA NILAI SEMESTER SISWA</h4></center>
                                            <center><h4>Nilai Raport</h4></center>
                                            <?php
                                            $siswa = $_SESSION['kode'];

                                            // Use prepared statements to prevent SQL injection
                                            $stmt = $conn->prepare("SELECT siswa.kode_siswa, siswa.nis, siswa.nama_siswa,
                                                nilai.semester, pelajaran.nama_pelajaran,
                                                nilai.nilai_tugas1, nilai.nilai_tugas2,
                                                nilai.nilai_uts, nilai.nilai_uas, nilai.keterangan, kelas_siswa.jurusan
                                                FROM siswa
                                                JOIN nilai ON siswa.kode_siswa = nilai.kode_siswa
                                                JOIN pelajaran ON nilai.kode_pelajaran = pelajaran.kode_pelajaran
                                                JOIN kelas_siswa ON kelas_siswa.kode_siswa = siswa.kode_siswa
                                                WHERE siswa.kode_siswa = ?");
                                            $stmt->bind_param("s", $siswa);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            while ($data = $result->fetch_assoc()) { ?>
                                                <table width="500">
                                                    <tr>
                                                        <td width="100">Kode Siswa</td> <td>:</td> <td><?php echo htmlspecialchars($data['kode_siswa']); ?></td> 
                                                    </tr>
                                                    <tr>
                                                        <td width="100">NIS</td> <td>:</td> <td><?php echo htmlspecialchars($data['nis']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100">Nama Siswa</td> <td>:</td> <td><?php echo htmlspecialchars($data['nama_siswa']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100">Jurusan</td> <td>:</td> <td><?php echo htmlspecialchars($data['jurusan']); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="100">Semester</td> <td>:</td> <td><?php echo htmlspecialchars($data['semester']); ?></td>
                                                    </tr>
                                                </table><br />
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th><center>Mata Pelajaran</center></th>
                                                                <th><center>Tugas 1</center></th>
                                                                <th><center>Tugas 2</center></th>
                                                                <th><center>UTS</center></th>
                                                                <th><center>UAS</center></th>
                                                                <th><center>Total Nilai</center></th>
                                                                <th><center>Nilai Rata - Rata</center></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($data['nama_pelajaran']); ?></td>
                                                                <td><center><?php echo htmlspecialchars($data['nilai_tugas1']); ?></center></td>
                                                                <td><center><?php echo htmlspecialchars($data['nilai_tugas2']); ?></center></td>
                                                                <td><center><?php echo htmlspecialchars($data['nilai_uts']); ?></center></td>
                                                                <td><center><?php echo htmlspecialchars($data['nilai_uas']); ?></center></td>
                                                                <td><center>
                                                                    <?php 
                                                                    $total = $data['nilai_tugas1'] + $data['nilai_tugas2'] + $data['nilai_uts'] + $data['nilai_uas'];
                                                                    echo htmlspecialchars($total); 
                                                                    ?>
                                                                </center></td>
                                                                <td><center><?php echo htmlspecialchars($total / 4); ?></center></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Keterangan</td>
                                                                <td colspan="6"><strong><?php echo htmlspecialchars($data['keterangan']); ?></strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <div class="text-right">
                                                        <a class="btn btn-sm btn-warning tooltips" data-placement="bottom" data-original-title="Print Nilai" href="cetak.php?hal=edit-admin&kd=<?php echo $siswa; ?>"><span class="icofont-print"></span> Cetak</a>
                                                    </div>
                                                </div>
                                            <?php } ?>
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

    <!-- IE Warning -->
    <!--[if lt IE 10]>
    <div class="ie-warning">
        <h1>Warning!!</h1>
        <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
        <div class="iew-container">
            <ul class="iew-download">
                <li>
                    <a href="http://www.google.com/chrome/">
                        <img src="https://cekmutasi.co.id/assets/images/browser/chrome.png" alt="Chrome">
                        <div>Chrome</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.mozilla.org/en-US/firefox/new/">
                        <img src="https://cekmutasi.co.id/assets/images/browser/firefox.png" alt="Firefox">
                        <div>Firefox</div>
                    </a>
                </li>
                <li>
                    <a href="http://www.opera.com">
                        <img src="https://cekmutasi.co.id/assets/images/browser/opera.png" alt="Opera">
                        <div>Opera</div>
                    </a>
                </li>
                <li>
                    <a href="https://www.apple.com/safari/">
                        <img src="https://cekmutasi.co.id/assets/images/browser/safari.png" alt="Safari">
                        <div>Safari</div>
                    </a>
                </li>
                <li>
                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="https://cekmutasi.co.id/assets/images/browser/ie.png" alt="">
                        <div>IE (9 & above)</div>
                    </a>
                </li>
            </ul>
        </div>
        <p>Sorry for the inconvenience!</p>
    </div>
    <![endif]-->

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
