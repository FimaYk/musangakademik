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
    <title>Edit Kelas | Si Akademik</title>
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
                                <div class="page-wrapper" style="padding:1rem">
                                    <div class="card total-request-card">
                                        <div class="card-header">
                                            <h4>Edit Data Kelas</h4>
                                            <span>Halaman ini untuk mengedit data kelas</span>
                                        </div>

                                        <div class="card-block">
                                            <?php
                                            $query = $conn->query("SELECT * FROM kelas WHERE kode_kelas='$_GET[kd]'");
                                            $data  = $query->fetch_assoc();
                                            ?>

                                            <form class="form-horizontal style-form" action="update-kelas.php" method="post" name="form1" id="form1">
                                                <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label">Kode Kelas</label>
                                                    <div class="col-sm-10">
                                                        <input name="kode_kelas" type="text" id="kode_kelas" class="form-control" value="<?php echo $data['kode_kelas'];?>" autofocus="on" required />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label">Tahun Ajaran</label>
                                                    <div class="col-sm-10">
                                                        <select name="tahun_ajar" id="tahun_ajar" class="form-control" required>
                                                            <option><?php echo $data['tahun_ajar'];?> </option>
                                                            <option value="2015/2016">2019/2020</option>
                                                            <option value="2016/2017">2020/2021</option>
                                                            <option value="2017/2018">2021/2022</option>
                                                            <option value="2018/2019">2022/2023</option>
                                                            <option value="2019/2020">2023/2024</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label">Kelas</label>
                                                    <div class="col-sm-10">
                                                        <select name="kelas" id="kelas" class="form-control" required>
                                                            <option><?php echo $data['kelas'];?></option>
                                                            <option value="X">X</option>
                                                            <option value="XI">XI</option>
                                                            <option value="XII">XII</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label">Nama Kelas</label>
                                                    <div class="col-sm-10">
                                                        <select name="nama_kelas" id="nama_kelas" class="form-control" required>
                                                            <option><?php echo $data['nama_kelas'];?></option>
                                                            <option value="Kelas A">Kelas A</option>
                                                            <option value="Kelas B">Kelas B</option>
                                                            <option value="Kelas C">Kelas C</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label">Nama Walikelas</label>
                                                    <div class="col-sm-10">
                                                        <select name="kode_guru" id="kode_guru" class="form-control" required>
                                                            <option><?php echo $data['kode_guru'];?></option>
                                                            <?php
                                                            $sql = $conn->query("SELECT * FROM guru ORDER BY kode_guru ASC");
                                                            while ($guru = $sql->fetch_assoc()) {
                                                                echo '<option value="'.$guru['kode_guru'].'">'.$guru['nama_guru'].'</option>'; 
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>       
                                                <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label">Status Aktif</label>
                                                    <div class="col-sm-10">
                                                        <select class="form-control" name="status_aktif" id="status_aktif" required>
                                                            <option value="Aktif" <?php if($data['status_aktif'] == 'Aktif'){echo 'selected';}?>>Aktif</option>
                                                            <option value="Tidak Aktif" <?php if($data['status_aktif'] == 'Tidak Aktif'){echo 'selected';}?>>Tidak Aktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 col-sm-2 control-label"></label>
                                                    <div class="col-sm-10">
                                                        <input type="submit" value="Simpan" class="btn btn-sm btn-primary" />&nbsp;
                                                        <a href="kelas" class="btn btn-sm btn-danger">Batal </a>
                                                    </div>
                                                </div>
                                            </form>
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
