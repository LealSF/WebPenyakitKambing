<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Hasil Diagnosis</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../css/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
</head>
<body class="container-fluid">
    <!-- header -->
    <?php include('headerdas.php'); ?>
    <div class="row no-gutters mt-5">
        <div class="col-md-2 bg-dark mt-2 pr-3 pt-4">
          <!-- sidebar -->
            <?php include('sidebardas.php') ?>
        </div>
        
        <div class="col-md-10 p-5 pt-10">
            <?php  
              if(isset($_GET['aksi'])){
                switch($_GET['aksi']){
                    case 'detail' :
                        detail($conn);
                        break;
                    default:
                        show_data($conn);
                }
            }
            else{
                show_data($conn);
            }
            ?>
        </div>
    </div>
    <!-- function show -->
    <?php function show_data($conn){ ?>
      <h3><i class="fa-solid fa-comment-medical m-lg-2"></i>Data Riwayat Diagnosis<hr></h3>
            <div class="card bg-info m-lg-3" style="width: 18rem;">
                <div class="card-body">
                  <div class="div card-body-icon">
                    <i class="fa-solid fa-square-virus"></i>
                  </div>
                  <h5 class="card-title">Total Riwayat Diagnosis</h5>
                  <div class="display-4">
                    <?php  
                      $query = mysqli_query($conn, "SELECT * FROM log_hasil");
                      $jumlah = mysqli_num_rows($query);
                      if($jumlah > 0){
                        echo $jumlah;
                      }
                      else{
                        echo "0";
                      }
                    ?>
                  </div>
                </div>
            </div>
            <a href="grafik.php"><button type="button" class="btn btn-primary mt-2 mb-3">Melihat Grafik</button></a>
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th scope="col" class="text-center" width=5%>No</th>
                        <th scope="col" class="text-center">Nama Peternak</th>
                        <th scope="col" class="text-center">Lokasi</th>
                        <th scope="col" class="text-center">Penyakit</th>
                        <th scope="col" class="text-center" width=10%>Nilai CF</th>
                        <th scope="col" class="text-center" width=20%>Tanggal</th>
                        <th scope="col" class="text-center">Fungsi</th>
                    </tr>
                </thead>
                <tbody>
                  <?php 
                    if($jumlah == 0){
                      echo "<tr>
                          <td class='text-center' colspan='7'>Tidak ada data yang tersimpan</td> 
                        </tr>";
                    }else {
                      $no = 0;
                      $query = mysqli_query($conn, "SELECT * FROM log_hasil INNER JOIN penyakit_tbl ON penyakit_tbl.id_penyakit = log_hasil.hasil_id");
                      while($data = mysqli_fetch_array($query)){ $no++; ?>
                        <tr>
                          <td ><?= $no; ?></td>
                          <td width=17%><?= $data['nama_peternak']; ?></td>
                          <td><?= $data['provinsi'].', '.$data['kabupaten'] ?></td>
                          <td><?= $data['penyakit_nama']; ?></td>
                          <td><?= $data['nilai']; ?> %</td>
                          <td><?= $data['tanggal']; ?></td>
                          <td width=5%><a href="dataLog.php?aksi=detail&id=<?= $data['id_log']; ?>"><button type="button" class="btn btn-success">Detail</button></a></td>
                        </tr>
                   <?php }} ?>
                </tbody>
            </table> 
    <?php } ?>
    <!-- function detail -->
    <?php function detail($conn){ 
      $id = $_GET['id'];
      $query = mysqli_query($conn, "SELECT * FROM log_hasil INNER JOIN penyakit_tbl ON penyakit_tbl.id_penyakit = log_hasil.hasil_id WHERE id_log='$id'");
      $data = mysqli_fetch_array($query);
      $penyakit = unserialize($data['penyakit']);
      $gejala = unserialize($data['gejala']);
      $no = 1;
      ?>
      <table class="table table-bordered">
          <thead>
              <tr>
                <th scope="col" class="text-center bg-info bg-opacity-50" colspan="4">Data Hasil Diagnosis</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td width="20%">Nama Peternak</td>
                  <td colspan="3"><?= $data['nama_peternak']; ?></td>
              </tr>
              <tr>
                  <td width="20%">Lokasi</td>
                  <td colspan="3"><?php echo $data['provinsi'].', '.$data['kabupaten'] ?></td>
              </tr>
              <tr>
                  <td width="20%">Tanggal</td>
                  <td colspan="3"><?= $data['tanggal']; ?></td>
              </tr>
              <tr>
                  <td scope="col" class="text-center bg-primary bg-opacity-50" colspan="4">Hasil Diagnosis Terakhir</td>
              </tr>
              <tr>
                  <td width="20%">Penyakit</td>
                  <td colspan="3"><?= $data['penyakit_nama']; ?></td>
              </tr>
              <tr>
                  <td width="20%">Gejala Yang Dimasukan</td>
                  <td colspan="3">
                    <?php
                      for($i=0; $i<count($gejala); $i++){
                        $id = $gejala[$i]['id'];
                        $query_gejala = mysqli_query($conn, "SELECT * FROM gejala_tbl WHERE id_gejala='$id'");
                        $dataGe = mysqli_fetch_array($query_gejala);
                        echo $no.". ".$dataGe['gejala_nama']. "<br>";
                        $no++;
                      }
                    ?>
                  </td>
              </tr>
              <tr>
                  <td width="20%">Keterangan Penyakit</td>
                  <td colspan="3"><?= $data['penyakit_penjelasan']; ?> </td>
              </tr>
              <tr>
                  <td width="20%">Solusi Yang Ditawakan Kepada Peternak</td>
                  <td colspan="3"><?= $data['penaykit_penanganan']; ?></td>
              </tr>
              <tr>
                  <td width="20%">Kemunkinan Penyakit Lain</td>
                  <td colspan="3"><?php 
                    for($i=1; $i<count($penyakit); $i++){
                      $id = $penyakit[$i]['id_penyakit'];
                      $nilai = $penyakit[$i]['nilai_cf'];
                      $query_penyakit = mysqli_query($conn, "SELECT * FROM penyakit_tbl WHERE id_penyakit='$id'");
                      $dataPe = mysqli_fetch_array($query_penyakit);
                      echo $i.". ".$dataPe['penyakit_nama']." ($nilai %)<br>";
                    }
                  ?></td>
              </tr>
            </tbody>
      </table>
      <a href="dataLog.php"><button type="button" class="btn btn-link mt-3">Kembali</button></a>
    <?php } ?>

    <!-- Footer -->
    <div class="row bg-primary flex-column">
        <div class="py-3 text-center">
          <p>&copy; Labay EL Sulthan Fatta</p>
          </div>
      </div>
</body>
</html>