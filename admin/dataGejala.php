<?php  
  session_start();
  if(!isset($_SESSION['admin_username']) && !isset($_SESSION['admin_password'])){
    header('location:../login.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penyakit</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../css/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
</head>
<body class="container-fluid">
    <!-- header -->
    <?php include("headerdas.php") ?>
    <div class="row no-gutters mt-5">
        <!-- Side Navbar -->
        <div class="col-md-2 bg-dark mt-2 pr-3 pt-4">
          <?php include("sidebardas.php") ?>
        </div>
        <div class="col-md-10 p-5 pt-10">
            <!-- menampilkan funtion tampilan -->
            <?php  
                if(isset($_GET['aksi'])){
                    switch($_GET['aksi']){
                        case 'create':
                            create($conn);
                            break;
                        case 'update':
                            update($conn);
                            break;
                        case 'delete':
                          delete($conn);
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
    <!-- Footer -->
    <div class="row bg-primary flex-column">
        <div class="py-3 text-center">
          <p>&copy; Labay EL Sulthan Fatta</p>
          </div>
      </div>
    <!-- Fungsi -->
    <!-- menampilkan data -->
    <?php function show_data($conn){ ?>
            <h3><i class="fa-solid fa-temperature-full m-lg-2"></i>Data Gejala<hr></h3>
            <a href="dataGejala.php?aksi=create"><button type="button" class="btn btn-primary mt-2 mb-3">Tambah Gejala</button></a>
            <table class="table table-striped table-hover">
                <?php  
                $query = mysqli_query($conn,"SELECT * From gejala_tbl");
                $hasil = mysqli_num_rows($query);
              ?>
                <thead class="table">
                    <th scope="col">Kode Gejala</th>
                    <th scope="col">Detail Gejala</th>
                    <th scope="col">#</th>
                </thead>
                <tbody>
                <?php 
                    if($hasil==0){
                        echo "<tr>
                          <td class='text-center' colspan='3'>Tidak ada data yang tersimpan</td> 
                        </tr>";
                      }
                    else{
                        $query = mysqli_query($conn,"SELECT * FROM gejala_tbl ");
                        while($data = mysqli_fetch_array($query)){
                ?>
                    <tr>
                        <td><?php echo $data['id_gejala']; ?></td>
                        <td><?php echo $data['gejala_nama']; ?></td>
                        <td class="m-lg-5"><a href="dataGejala.php?aksi=update&id=<?= $data['id_gejala'] ?>"><button type="button" class="btn btn-success">Update</button></a> || 
                        <a href="dataGejala.php?aksi=delete&id=<?=$data['id_gejala'] ?>"><button type="button" class="btn btn-danger" name="btn_delete">Delete</button></a></td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>
            <!-- menampilkan tambah data -->
            <?php } 
              function create($conn){
            ?>
            <h3><i class="fa-solid fa-temperature-full m-lg-2"></i>Tambah Gejala<hr></h3>
            <form method="POST" class="row g-3" action="gejala/aksi_gejala.php">
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Kode Gejala</label>
                  <input type="text" name="kode_gejala" class="form-control" id="inputAddress" placeholder="Masukan kode seperti ini = G001" required>
                </div>
                <div class="col-12">
                  <label for="inputAddress2" class="form-label">Keterangan Gejala</label>
                  <input type="text" name="nama_gejala" class="form-control" id="inputAddress2" placeholder="Masukan data gejala" required>
                </div>
                <div class="col align-self-end">
                  <button class="btn btn-danger" onclick="goBack()">Cancel</button>
                  <button name="btn_create" type="submit" class="btn btn-success">Save</button>
                </div>
              </form>
            <!-- menampilkan update data -->
            <?php }
                function update($conn){
                  if(isset($_GET['id'])){
                    $id = $_GET['id'];
                    $edit = mysqli_query($conn, "SELECT * FROM gejala_tbl WHERE id_gejala='$id'");
                    foreach ($edit as $data) :
            ?>
            <h3><i class="fa-solid fa-temperature-full m-lg-2"></i>Update Gejala<hr></h3>
            <form method="POST" class="row g-3" action="gejala/aksi_gejala.php">
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Kode Gejala</label>
                  <input type="text" name="kode_gejala" class="form-control" id="inputAddress" value="<?=$data['id_gejala']?>" required>
                </div>
                <div class="col-12">
                  <label for="inputAddress2" class="form-label">Keterangan Gejala</label>
                  <input type="text" name="nama_gejala" class="form-control" id="inputAddress2" value="<?= $data['gejala_nama'] ?>" required>
                </div>
                <div class="col align-self-end">
                  <button class="btn btn-danger" onclick="goBack()">Cancel</button>
                  <button name="btn_update" type="submit" value="" class="btn btn-success">Update</button>
                </div>
              </form>
            <?php endforeach; }} ?>
            <!-- function delete -->
            <?php 
              function delete($conn){
                if(isset($_GET['id']) && isset($_GET['aksi'])){
                  $id = $_GET['id'];
                  $delete = mysqli_query($conn, "DELETE FROM gejala_tbl WHERE id_gejala='$id'");
                  // jika terhapus
                  if($delete){
                    // jika aksi = delete
                    if($_GET['aksi'] == 'delete'){
                      header('location:dataGejala.php');
                      echo "<script>window.alert('Data telah terhapus')</script>";
                    }
                  }
                  // Jika tidak terhapus
                  else{
                    header('location:dataGejala.php');
                      echo "<script>window.alert('Data tidak terhapus')</script>";
                  }
                }
              }
            ?>
    <script>
      function goBack(){
        window.history.back();
      }
    </script>
</body>
</html>