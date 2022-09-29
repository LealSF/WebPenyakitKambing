<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webiste Analisi Penyakit Kambing Menggunakan Certainty Factor</title>
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
        <!-- Menu utama -->
        <div class="col-md-10 p-5 pt-10">
            <div class="gambar text-center">
                <img class="img-fluid rounded mb-3" src="asset/banner.jpg" alt="">
            </div>
            <h3><i class="fa-solid fa-newspaper m-lg-2"></i>Daftar Penyakit Kambing<hr></h3> 
            <div class="row row-cols-1 row-cols-md-3 g-4 mt-4">
              <?php 
                $query = mysqli_query($conn, "SELECT * FROM penyakit_tbl");
                $jumlah_data = mysqli_num_rows($query);
                if($jumlah_data==0){
                  echo "<div class='col'>
                    <h1 class='display-3 text-center'>Tidak ada data yang tersimpan</h1>
                </div>
                  ";
                }
                else{
                  while($data = mysqli_fetch_array($query)){
              ?>
                <div class="col">
                  <div class="card">
                    <img src="asset/gambar/<?= $data['gambar'] ?>" class="card-img-top" style="width: 18rem;">
                    <div class="card-body">
                      <h5 class="card-title"><?= $data['penyakit_nama']; ?></h5>
                      <a href="detailPenyakit.php?id=<?= $data['id_penyakit']; ?>"><buttom class="btn btn-primary">Detail</buttom></a>
                    </div>
                  </div>
                </div>
                <?php }} ?>
        </div>
    </div>
    <!-- footer -->
    <?php include('footer.php'); ?>
</body>
</html>