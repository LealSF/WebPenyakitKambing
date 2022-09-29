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
</head>
<body class="container-fluid">
    <!-- header -->
    <?php include("headerdas.php"); ?>
    <div class="row no-gutters mt-5">
        <!-- Side Navbar -->
        <div class="col-md-2 bg-dark mt-2 pr-3 pt-4">
          <!-- sidebar -->
          <?php include("sidebardas.php"); ?>
        </div>
        <div class="col-md-10 p-5 pt-10">
          <!-- fungsi menampilkan tampilan -->
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
                default :
                  show_data($conn);
              }
            }
            else {
              show_data($conn);
            }
          ?>
          <!-- menampilkan data aturan -->
            <?php  
              function show_data($conn){
                $query = mysqli_query($conn, "SELECT * FROM aturan_tbl");
                $hitung = mysqli_num_rows($query);
            ?>
            <h3><i class="fa-solid fa-flask m-lg-2"></i>Data Aturan<hr></h3>
            <a href='dataAturan.php?aksi=create'><button type="button" class="btn btn-primary mt-2 mb-3">Tambah Aturan</button></a>
            <table class="table table-striped table-hover">
              <thead class="table">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Nama Penyakit</th>
                  <th scope="col">Nama Gejala</th>
                  <th scope="col">Nilai MB</th>
                  <th scope="col">Nilai MD</th>
                  <th scope="col">Tools</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if($hitung==0){ 
                  echo "<tr>
                    <td class='text-center' colspan='6'>Tidak ada data yang tersimpan</td>;
                  </tr>
                  ";
                }
                else {
                  $no = 0;
                  while ($data = mysqli_fetch_array($query)){
                    $no++;
                    $sql1 = mysqli_query($conn, "SELECT * FROM penyakit_tbl WHERE id_penyakit = '$data[id_penyakit]'");
                    $penyakit = mysqli_fetch_array($sql1);
                    $sql2 = mysqli_query($conn, "SELECT * FROM gejala_tbl WHERE id_gejala = '$data[id_gejala]'");
                    $gejala = mysqli_fetch_array($sql2);
                ?>
                <tr>
                    <td><?= $no; ?></td>
                    <td><?= $penyakit['penyakit_nama']; ?></td>
                    <td><?= $gejala['gejala_nama']; ?></td>
                    <td><?= $data['mb_aturan']; ?></td>
                    <td><?= $data['md_aturan']; ?></td>
                    <td class="m-lg-5"><a href='dataAturan.php?aksi=update&id=<?= $data['id_aturan']; ?>'><button type="button" class="btn btn-success">Update</button></a> || 
                    <a href="dataAturan.php?aksi=delete&id=<?= $data['id_aturan'] ?>"><button type="button" name="btn_delete" class="btn btn-danger">Delete</button></a></td>
                </tr>
                <?php }} ?>
              </tbody>
            </table>
            <?php } ?>
            <!-- Menampilkan tambah data aturan -->
            <?php function create($conn){ ?>
              <h3><i class="fa-solid fa-flask m-lg-2"></i>Tambah Aturan<hr></h3>
              <form method="POST" class="row g-3" action="aturan/aksi_aturan.php">
                  <div class="col-12">
                    <label for="inputAddress" class="form-label">Kode Aturan</label>
                    <input type="text" name="kode_aturan" class="form-control" id="inputAddress" placeholder="Format Kode A001" required>
                  </div>
                  <div class="col-12">
                      <label for="inputAddress" class="form-label">Nama Penyakit</label>
                      <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="nama_penyakit" placeholder="Pilih Salah Satu" required>
                        <?php  
                          $hasil1 = mysqli_query($conn, "SELECT * FROM penyakit_tbl ORDER BY penyakit_nama");
                          while ($r1 = mysqli_fetch_array($hasil1)){
                          echo "<option value='$r1[id_penyakit]'>$r1[penyakit_nama]</option>";
                          }
                        ?>
                        </select>
                  </div>
                  <div class="col-12">
                      <label for="inputAddress" class="form-label">Nama Gejala</label>
                      <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="nama_gejala" placeholder="Pilih Salah Satu" required>
                        <?php 
                          $hasil2 = mysqli_query($conn, "SELECT * FROM gejala_tbl ORDER BY gejala_nama");
                          while ($r2 = mysqli_fetch_array($hasil2)){
                            echo "<option value='$r2[id_gejala]'>$r2[gejala_nama]</option>";
                          }
                        ?>
                        </select>
                  </div>
                  <div class="col-12">
                    <label for="inputAddress2" class="form-label">Nilai MB (Meansure Of Belief)</label>
                    <input type="text" name="nilai_mb" class="form-control" id="inputAddress2" placeholder="nilai dari 0 - 1" required>
                  </div>
                  <div class="col-12">
                      <label for="inputAddress2" class="form-label">Nilai MD (Meansure Of Disbelief)</label>
                      <input type="text" name="nilai_md" class="form-control" id="inputAddress2" placeholder="nilai dari 0 - 1" required>
                    </div>
                  <div class="col align-self-end">
                    <button class="btn btn-danger" onclick="goBack()">Cancel</button>
                    <button type="submit" name="btn_save" class="btn btn-success">Save</button>
                  </div>
                </form>
              <?php } ?>
              <!-- menampilkan data update aturan -->
              <?php function update($conn){ 
                $update = mysqli_query($conn, "SELECT * FROM aturan_tbl WHERE id_aturan='$_GET[id]'");
                $hasil = mysqli_fetch_array($update);
                ?>
                <h3><i class="fa-solid fa-flask m-lg-2"></i>Update Aturan<hr></h3>
                <form method="POST" class="row g-3" action="aturan/aksi_aturan.php">
                    <div class="col-12">
                      <label for="inputAddress" class="form-label">Kode Aturan</label>
                      <input type="text" name="kode_aturan" class="form-control" id="inputAddress" value="<?= $hasil['id_aturan']; ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">Nama Penyakit</label>
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="nama_penyakit" value="<?= $hasil['id_penyakit'] ?>" required>
                          <?php  
                            $hasil1 = mysqli_query($conn, "SELECT * FROM penyakit_tbl");
                            foreach ($hasil1 as $key) :
                              if($hasil['id_penyakit'] == $key['id_penyakit']){
                                echo "<option value='$key[id_penyakit]' selected>$key[penyakit_nama]</option>";
                              } else {
                                echo "<option value='$key[id_penyakit]'>$key[penyakit_nama]</option>";
                              }
                          endforeach;
                          ?>
                          </select>
                      </div>
                      <div class="col-12">
                        <label for="inputAddress" class="form-label">Nama Gejala</label>
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="nama_gejala" value="<?= $hasil['id_gejala']?>" required>
                          <?php 
                            $hasil2 = mysqli_query($conn, "SELECT * FROM gejala_tbl");
                            foreach ($hasil2 as $key):
                              if($hasil['id_gejala']==$key['id_gejala']){
                                echo "<option value='$key[id_gejala]'>$key[gejala_nama]</option>";
                              } else{
                                echo "<option value='$key[id_gejala]'>$key[gejala_nama]</option>";
                              }
                            endforeach;
                          ?>
                          </select>
                      </div>
                    <div class="col-12">
                      <label for="inputAddress2" class="form-label">Nilai MB (Meansure Of Belief)</label>
                      <input type="text" name="nilai_mb" class="form-control" id="inputAddress2" value="<?= $hasil['mb_aturan']; ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="inputAddress2" class="form-label">Nilai MD (Meansure Of Disbelief)</label>
                        <input type="text" name="nilai_md" class="form-control" id="inputAddress2" value="<?= $hasil['md_aturan']; ?>" required>
                      </div>
                    <div class="col align-self-end">
                      <button class="btn btn-danger" onclick="goBack()">Cancel</button>
                      <button type="submit" name="btn_update" class="btn btn-success">Update</button>
                    </div>
                  </form>
                <?php } ?>
              <!-- function delete -->
              <?php 
                function delete($conn){
                  if(isset($_GET['id']) && isset($_GET['aksi'])){
                    $id = $_GET['id'];
                    $delete = mysqli_query($conn, "DELETE FROM aturan_tbl WHERE id_aturan='$id'");
                    // jika terhapus
                    if($delete){
                      // jika aksi = delete
                      if($_GET['aksi'] == 'delete'){
                        header('location:dataAturan.php');
                        echo "<script>window.alert('Data telah terhapus')</script>";
                      }
                    }
                    // Jika tidak terhapus
                    else{
                      header('location:dataAturan.php');
                        echo "<script>window.alert('Data tidak terhapus')</script>";
                    }
                  }
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
    
    <script src="../css/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
    <script src="../asset/sweetalert.min.js"></script>
    <script defer scr="penyakit/aksi_penyakit.php"></script>
    <script src="../asset/ckeditor/ckeditor.js"></script>
    <script>
      function goBack(){
          window.history.back();
        }
    </script>
</body>
</html>