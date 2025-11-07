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
    <title>Data Kelas | Si Akademik</title>
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
                        <form action='kelas' method="POST">
                            <div class="card total-request-card">
                                <div class="card-block">
                                    <div class="row" style="margin-bottom:20px">
                                        <div class="col-xs-12 col-sm-6">
                                            <input type='text' class="form-control" style="margin-bottom: 4px;" name='qcari' placeholder='Cari berdasarkan Kode Kelas atau Kelas' required /> 
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-10">
                                                    <input type='submit' value='Cari Data' class="btn btn-sm btn-primary" /> 
                                                    <a href='kelas' class="btn btn-sm btn-success">Refresh</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="card">
                            <div class="card-header">
                                <h5>Data Kelas</h5>
                            </div>
                            <div class="card-block table-border-style">
                                <div class="button-panel" style="padding: 0px 20px 10px 20px; float:left">
                                    <a class="btn btn-primary btn-bordered btn-mini pull-right" href="input-kelas">
                                        <i class="icofont icofont-plus-circle"></i>Tambah
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <?php
                                    // Prepare the SQL statement
                                    $query1 = "SELECT * FROM kelas";

                                    if (isset($_POST['qcari'])) {
                                        $qcari = $_POST['qcari'];
                                        $query1 = "SELECT * FROM kelas 
                                                   WHERE kode_kelas LIKE ? OR kelas LIKE ?";
                                    }

                                    // Prepare and execute the statement
                                    $stmt = $conn->prepare($query1);
                                    if (isset($qcari)) {
                                        $searchTerm = "%$qcari%";
                                        $stmt->bind_param("ss", $searchTerm, $searchTerm);
                                    }
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    ?>
                                    <table class="table table-hover table-striped" style="font-size: 13px;">
                                        <thead>
                                            <tr>
                                                <th>Kode Kelas <i class="icofont-sort"></i></th>
                                                <th>Tahun Ajaran <i class="icofont-sort"></i></th>
                                                <th>Kelas <i class="icofont-sort"></i></th>
                                                <th>Nama Kelas <i class="icofont-sort"></i></th>
                                                <th>Kode Guru <i class="icofont-sort"></i></th>
                                                <th>Status <i class="icofont-sort"></i></th>
                                                <th>Tools</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($data = $result->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php echo $data['kode_kelas']; ?></td>
                                                    <td><?php echo $data['tahun_ajar']; ?></td>
                                                    <td><?php echo $data['kelas']; ?></td>
                                                    <td><?php echo $data['nama_kelas']; ?></td>
                                                    <td><?php echo $data['kode_guru']; ?></td>
                                                    <td><?php echo $data['status_aktif']; ?></td>
                                                    <td>
                                                        <center>
                                                            <a class="btn btn-mini btn-primary tooltips" data-placement="bottom" data-original-title="Edit Kelas" href="edit-kelas.php?hal=edit-admin&kd=<?php echo $data['kode_kelas'];?>">
                                                                <span class="icofont-edit"></span>
                                                            </a>
                                                            <hr />
                                                            <a class="btn btn-mini btn-danger tooltips" data-placement="bottom" data-original-title="Hapus Kelas" href="hapus-kelas.php?hal=hapus&kd=<?php echo $data['kode_kelas'];?>" onclick="return confirm('Apakah anda akan menghapus <?php echo $data['kode_kelas'];?> ?');">
                                                                <span class="icofont-trash"></span>
                                                            </a>
                                                        </center>
                                                    </td>
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
