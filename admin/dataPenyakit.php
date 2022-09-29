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
    <!-- css for boostrap -->
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
    <!-- css -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- js bosstrap -->
    <script src="../css/bootstrap/js/bootstrap.min.js"></script>
    <!-- js fontawasome  -->
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
    <style>
      .height{
        height: 500px;
      }
    </style>
  </head>
<body class="container-fluid">
    <!-- header -->
    <?php include("headerdas.php"); ?>
    <div class="row no-gutters mt-5">
        <!-- Side Navbar -->
        <div class="col-md-2 bg-dark mt-2 pr-3 pt-4">
          <?php include("sidebardas.php"); ?>
        </div>
        <div class="col-md-10 p-5 pt-10">
            <?php 
                if(isset($_GET['aksi'])){
                    switch($_GET['aksi']){
                        case 'create' :
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
<?php
    function show_data($conn){
?>
    <h3><i class="fa-solid fa-skull-crossbones m-lg-2"></i>Data Penyakit<hr></h3>
    <a href="dataPenyakit.php?aksi=create"><button type="button" class="btn btn-primary mt-2 mb-3">Tambah Penyakit</button></a>
    <table class="table table-striped table-hover">
      <form class="row g-3">
      <?php  
        $query = mysqli_query($conn,"SELECT * From penyakit_tbl");
        $jumlah_data = mysqli_num_rows($query);
      ?>
      <thead class="table">
        <tr>
          <th scope="col">Kode Penyakit</th>
          <th scope="col">Nama Penyakit</th>
          <th scope="col">Gambar</th>
          <th scope="col">#</th>
        </tr>
      </thead>
      <tbody>
        <?php  
          if($jumlah_data==0){
            echo "<tr>
              <td class='text-center' colspan='3'>Tidak ada data yang tersimpan</td> 
            </tr>";
          }
          else{
            while($data = mysqli_fetch_array($query)){
        ?>
        <tr>
            <td><?php echo $data['id_penyakit'] ?></td>
            <td><?php echo $data['penyakit_nama'] ?></td>
            <?php  
              if($data['gambar'] == ''){
                echo "<td>Belum Ada Gambar</td>";
              }
              else{
            ?>
            <td><img src="../asset/gambar/<?= $data['gambar']; ?>" width="130" height="75" alt=""></td> <?php } ?>
            <td class="m-lg-5"><a href="dataPenyakit.php?aksi=update&id=<?= $data['id_penyakit'] ?>"><button type="button" class="btn btn-success">Update</button></a> || 
            <a href="dataPenyakit.php?aksi=delete&id=<?= $data['id_penyakit'] ?>"><button type="button" name="btn_delete" class="btn btn-danger">Hapus</button></a></td>
        </tr>
        <?php }} ?>
      </tbody>
    </table>
    <?php } ?>

    <!-- menampilkan tambah data -->
    <?php function create($conn){ ?>
    <h3><i class="fa-solid fa-skull-crossbones m-lg-2"></i>Tambah Penyakit<hr></h3>
    <form method="POST" action="penyakit/aksi_penyakit.php" enctype='multipart/form-data'>
        <div class="col-12 mt-3">
          <label for="inputAddress" class="form-label">Kode Penyakit</label>
          <input type="text" name="kode_penyakit" class="form-control" placeholder="1234 Main St" required>
        </div>
        <div class="col-12 mt-3">
          <label for="inputAddress2" class="form-label">Nama Penaykit</label>
          <input type="text" name="nama_penyakit" class="form-control" placeholder="Tyapani" required>
        </div>
        <div class="col-12 mt-3">
          <label for="inputAddress2" class="form-label">Upload Foto</label>
          <input type="file" name="foto_penyakit" class="form-control" required>
        </div>
        <div class="col-md-12 mt-3">
          <label for="inputCity" class="form-label">Penjelasan Penyakit</label>
          <textarea type="text" name="penjelasan_penyakit" class="form-control" id="editor" required></textarea>
        </div>
        <div class="col-md-12 mt-3">
            <label for="inputCity" class="form-label">Penanganan</label>
            <textarea type="text" name="penanganan_penyakit" class="form-control" id="editor1" required></textarea>
        </div>
        <div class="col mt-3">
          <buttom  class="btn btn-danger" onclick="goBack()">Cancel</buttom>
          <button type="submit" name="btn_simpan" class="btn btn-success">Save</button>
        </div>
    </form>
    <?php } ?>
    
    <!-- menampilkan edit data -->
    <?php function update($conn){ 
      if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $edit = mysqli_query($conn,"SELECT * FROM penyakit_tbl WHERE id_penyakit='$id'");
        foreach($edit as $hasil) :
    ?>
    <h3><i class="fa-solid fa-skull-crossbones m-lg-2"></i>Update Penaykit<hr></h3>
      <form method="POST" action="penyakit/aksi_penyakit.php" enctype="multipart/form-data">
        <div class="col-12">
          <label for="inputAddress" class="form-label">Kode Penyakit</label>
          <input type="text" name="kode_penyakit" class="form-control" value="<?= $hasil['id_penyakit'] ?>" required>
        </div>
        <div class="col-12 mt-3">
          <label for="inputAddress2" class="form-label">Nama Penaykit</label>
          <input type="text" name="nama_penyakit" class="form-control" value="<?= $hasil['penyakit_nama'] ?>" required>
        </div>
        <div class="col-12 mt-3">
          <?php if($hasil['gambar'] ==''){ ?>
          <label for="inputAddress2" class="form-label">Upload Foto</label>
          <?php echo "<span class='text-danger'>Belum Ada Foto / Gambar</span>"; } else { ?>
          <img src="../asset/gambar/<?= $hasil['gambar']; ?>" width="10%" height="10%" alt=""> <?php } ?>
          <input type="file" name="foto_penyakit" class="form-control" required>
        </div>
        <div class="col-md-12 mt-3">
          <label for="inputCity" class="form-label">Penjelasan Penyakit</label>
          <textarea type="text" name="penjelasan_Penyakit" class="form-control" id="editor" required><?= $hasil['penyakit_penjelasan'] ?></textarea>
        </div>
        <div class="col-md-12 mt-3">
            <label for="inputCity" class="form-label">Penanganan</label>
            <textarea type="text" name="penanganan_penyakit" class="form-control" id="editor1" required><?= $hasil['penaykit_penanganan'] ?></textarea>
        </div>
        <div class="col mt-3">
          <button class="btn btn-danger" onclick="history.back(-1)">Cancel</button>
          <button type="submit" name="btn_update" class="btn btn-success">Save</button>
        </div>
      </form>
    <?php endforeach; }} ?>
    <!-- function delete -->
    <?php 
      function delete($conn){
        if(isset($_GET['id']) && isset($_GET['aksi'])){
          $id = $_GET['id'];
          $delete = mysqli_query($conn, "DELETE FROM penyakit_tbl WHERE id_penyakit='$id'");
          // jika terhapus
          if($delete){
            // jika aksi = delete
            if($_GET['aksi'] == 'delete'){
              header('location:dataPenyakit.php');
              echo "<script>window.alert('Data telah terhapus')</script>";
            }
          }
          // Jika tidak terhapus
          else{
            header('location:dataPenyakit.php');
              echo "<script>window.alert('Data tidak terhapus')</script>";
          }
        }
      }
    ?>
  
    <script src="../css/bootstrap/js/bootstrap.min.js"></script>
    <script src="../asset/ckeditor/ckeditor.js"></script>
    <script src="../asset/ckfinder/ckfinder.js"></script>
    <script src="../css/jquery.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
    <script defer scr="penyakit/aksi_penyakit.php"></script>
    <script>
      var editor1 = CKEDITOR.replace('editor');
      var editor2 = CKEDITOR.replace('editor1');
      CKFinder.setupCKEditor(editor1);
      CKFinder.setupCKEditor(editor2);
      
      function goBack(){
        window.history.back();
      }
    </script>
</body>
</html>