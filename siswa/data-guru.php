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
    <title>Data Guru | Si Akademik</title>
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
                                <form action='data-guru' method="POST">
                                    <div class="card total-request-card">
                                        <div class="card-block">
                                            <div class="row" style="margin-bottom:20px">
                                                <div class="col-xs-12 col-sm-6">
                                                    <input type='text' class="form-control" style="margin-bottom: 4px;" name='qcari' placeholder='Cari berdasarkan User ID dan Username' required /> 
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-10">
                                                            <input type='submit' value='Cari Data' class="btn btn-sm btn-primary" /> 
                                                            <a href='data-guru' class="btn btn-sm btn-success">Refresh</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="card">
                                    <div class="card-header">
                                        <h5>Data Guru</h5>
                                    </div>
                                    <div class="card-block table-border-style">
                                        <div class="table-responsive">
                                            <?php
                                            // Use prepared statements to prevent SQL injection
                                            $query1 = "SELECT * FROM guru";
                                            if (isset($_POST['qcari'])) {
                                                $qcari = $_POST['qcari'];
                                                $query1 = "SELECT * FROM guru WHERE kode_guru LIKE ? OR nama_guru LIKE ?";
                                            }

                                            // Prepare and execute the query
                                            $stmt = $conn->prepare($query1);
                                            if (isset($_POST['qcari'])) {
                                                $searchTerm = "%$qcari%";
                                                $stmt->bind_param("ss", $searchTerm, $searchTerm);
                                            }
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            ?>
                                            <table class="table table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width:80px;white-space: inherit">Kode Guru</th>
                                                        <th style="min-width:80px;white-space: inherit">NIP</th>
                                                        <th style="min-width:80px;white-space: inherit">Nama Guru</th>
                                                        <th style="min-width:80px;white-space: inherit">Kelamin</th>
                                                        <th style="min-width:80px;white-space: inherit">Alamat</th>
                                                        <th style="min-width:80px;white-space: inherit">No Telepon</th>
                                                        <th style="min-width:80px;white-space: inherit">Status Aktif</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($data = $result->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td style="min-width:80px;white-space: inherit"><?php echo htmlspecialchars($data['kode_guru']); ?></td>
                                                            <td style="min-width:80px;white-space: inherit"><?php echo htmlspecialchars($data['nip']); ?></td>
                                                            <td style="min-width:80px;white-space: inherit"><?php echo htmlspecialchars($data['nama_guru']); ?></td>
                                                            <td style="min-width:80px;white-space: inherit"><?php echo htmlspecialchars($data['kelamin']); ?></td>
                                                            <td style="min-width:80px;white-space: inherit"><?php echo htmlspecialchars($data['alamat']); ?></td>
                                                            <td style="min-width:80px;white-space: inherit"><?php echo htmlspecialchars($data['no_telepon']); ?></td>
                                                            <td style="min-width:80px;white-space: inherit"><?php echo htmlspecialchars($data['status_aktif']); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
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
