<?php 
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <script src="css/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
</head>
<body class="container-fluid">
    <!-- header -->
    <?php include("header.php") ?>
    <div class="info-data" data-infodata="<?php if(isset($_SESSION['info'])){ echo $_SESSION['info'];} ?>"></div>
    <div class="row no-gutters mt-5">
        <!-- sidebar -->
        <?php include("sidebar.php") ?>
        <div class="col-md-10 p-5 pt-10">
            <div class="card top-50 start-50 translate-middle layout-login">
                <div class="card-body">
                    <?php  
                        if(isset($_GET['aksi'])){
                            switch(isset($_GET['aksi'])){
                                case 'create':
                                    create($conn);
                                    break;
                                default :
                                    login($conn);
                            }
                        }
                        else {
                            login($conn);
                        }
                    ?>

                    <!-- function tampil login -->
                    <?php function login($conn){ ?>
                    <h1 class="display-7 text-center">Login <hr></h1>
                    <div class="mt-5">
                        <form class="row g-3 needs-validation" id="form_login" method="POST" action="admin/validasi_login/validasi.php">
                            <div class="mb-3 mx-3">
                                <label class="form-label">Username</label>
                                <input name="username" class="form-control" id="exampleFormControlInput1" placeholder="Masukan Username" required>
                                <div class="valid-feedback" id="username">
                                    Looks good!
                                </div>
                            </div>
                            <div class="mb-3 mx-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="exampleFormControlInput1" placeholder="Masukan Password" required>
                                <div class="valid-feedback" id="password">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col mx-3">
                                <a href ="login.php?aksi=create"><buttom class="btn btn-danger">Create</button></a>
                                <button type="submit" name="btn_login" id="btn_login" class="btn btn-success">Login</button>
                            </div>
                        </form>
                    </div>
                    <?php } ?>
                    
                    <!-- function tampil create user -->
                    <?php function create($conn){ ?>
                    <div class="mt-5">
                        <h3><i class="fa-solid fa-user m-lg-2"></i>Buat Akun<hr></h3>
                        <form id="form_create" name="text_form" method="POST" action="admin/validasi_login/validasi.php">
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Nama Dokter</label>
                                <input type="text" name="nama_dokter" class="form-control" placeholder="Masukan Nama Dokter" required>
                                <div class="valid-feedback" id="nama_dokter">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Masukan Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Masukan Username" required>
                                <div class="valid-feedback" id="username">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Masukan Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukan Kata Password" required>
                                <div class="valid-feedback" id="password">
                                    Looks good!
                                </div>
                            </div>
                            <div class="col mt-4">
                                <button class="btn btn-danger" onclick="goBack()">Cancel</button>
                                <button class="btn btn-success" type="submit" name="btn_create">Create</button>
                            </div>
                        </form>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php include ('footer.php'); ?>
    
    <script src="asset/sweetalert2.all.min.js"></script>
    <script>
      function goBack(){
          window.history.back();
        }
    
    </script>
</body>
</html>