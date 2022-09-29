<?php 
  include ('config/koneksi.php');
  
  $id = $_GET['id'];
  $sql = mysqli_query($conn, "SELECT * FROM penyakit_tbl WHERE id_penyakit='$id'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <script src="css/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
</head>
<body class="container-fluid">
    <!-- header -->
    <?php include('header.php') ?>
    <div class="row no-gutters mt-5">
        <!-- sidebar -->
        <?php include('sidebar.php') ?>
        <div class="col-md-10 p-5 pt-10">
          <?php foreach($sql as $data) : ?>
          <h1><?= $data['penyakit_nama'] ?><hr></h1>
          <div class="container">
            <?= $data['penyakit_penjelasan'] ?>
            <?= $data['penaykit_penanganan'] ?>
          </div>
          <?php endforeach; ?>
        </div>
    </div>
    <!-- footer -->
    <?php include('footer.php'); ?>
</body>
</html>