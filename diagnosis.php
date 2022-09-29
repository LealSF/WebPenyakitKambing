<?php 
ini_set('display_errors',0);
include ('config/koneksi.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Diagnosis</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
    <script src="css/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
</head>
<body class="container-fluid">
    <!-- header -->
    <?php include('header.php') ?>
    <div class="row no-gutters mt-5">
        <?php include('sidebar.php') ?>
        <!-- Menu Utama -->
        <div class="col-md-10 p-5 pt-10">
            <!-- mengatur tampilan -->
            <?php 
                if(isset($_GET['aksi'])){
                    switch($_GET['aksi']){
                        case 'hasil':
                            hasil($conn);
                            break;
                        default :
                            show($conn);
                            break;
                    }
                }
                else{
                    show($conn);
                }
            ?>
        </div>
    </div>

    <!-- menampilkan data awal diagnosa -->
    <?php function show($conn){ ?>
        <div class="gambar text-center">
                <img class="img-fluid rounded " src="asset/diagnosis.jpg" alt="">
        </div>
        <h3><i class="fa-solid fa-chart-line m-lg-2"></i>Analisis Penyakit<hr></h3>
        <div class="alert alert-warning" role="alert">
            Pilihlah pernyataan sesuai dengan kondisi kambing. Pilihlah gejala lebih dari 1. Isilah sesuai dengan keadaan keadaan kambing. 
            Apabila gejala tidak muncul pada kambing, harap tidak diisi.  
        </div>
        <form method="POST" action="diagnosis.php?aksi=hasil">
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Masukan Nama</label>
                <div class="col-sm-6">
                    <input type="text" name="nama_peternak" class="form-control" id="staticEmail" placeholder="Masukan Nama Saudara...." required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6 col-sm-3">
                    <label class="col-sm-9 col-form-label">Pilih Provinsi</label>
                    <select name="nama_province" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                        <option selected>--- Pilih Provinsi ---</option>
                    </select>
                </div>
                <div class="col-6 col-sm-3">
                    <label class="col-sm-9 col-form-label">Pilih Kabupaten</label>
                    <select name="nama_districs" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                        <option selected>--- Pilih Kabupaten ---</option>
                    </select>
                </div>
            </div>
            <!-- menampilkan Pertanyaan -->
            <?php  
            $query = mysqli_query($conn, "SELECT * FROM gejala_tbl");
            $hitung = mysqli_num_rows($query);
            $num = 0;
            if($hitung == 0){
                echo "<h1 class='display-6 text center'>Tidak ada data</h1>";
            }
            else {
                while($data = mysqli_fetch_array($query)){
                $num++;
            ?>
            <div class="row mb-3">
                <label class="col-sm-1 col-form-label"><?= $num ?></label>
                <label class="col-sm-9 col-form-label">Apakah <?= $data['gejala_nama'] ?></label>
                <div class="col-2">
                    <select class="form-select form-select-sm" name="kondisi[]" aria-label=".form-select-sm example">
                        <option value="0" selected>Tidak</option>
                        <?php if ($data['id_gejala'] == 'G021' || $data['id_gejala'] == 'G006' || $data['id_gejala'] == 'G020') { ?>
                            <option value="1.0<?= '_'.$data['id_gejala'] ?>">Pasti</option>
                        <?php } else{ ?>
                            <option value="0.25<?= '_'.$data['id_gejala'] ?>">Mungkin</option>
                            <option value="0.5<?= '_'.$data['id_gejala'] ?>">Kadang - Kadang</option>
                            <option value="0.75<?= '_'.$data['id_gejala'] ?>">Hampir Sering</option>
                            <option value="1.0<?= '_'.$data['id_gejala'] ?>">Ya</option>
                        <?php } ?>
                        </select>
                </div>
            </div>
            <?php }} ?>
            <div class="col mt-4">
                <button class="btn btn-success" type="submit" name="btn_diagnosis">Diagnosis</button>
            </div>
        </form>
    <?php } ?>
    <!-- proses hitung  -->
    <?php 
    function hasil($conn){  
        if(isset($_POST['btn_diagnosis'])){
            // mengambil nilai dari kondisi
            $nama_peternak = $_POST['nama_peternak'];
            $tanggal = date('Y-m-d H:i:s');
            $data_gejala = $_POST['kondisi'];
            $provinsi = $_POST['nama_province'];
            $distrik = $_POST['nama_districs'];
            foreach ($data_gejala as $key => $gejala){
                $dataG = explode('_', $gejala);
                $gejala_nilai = $dataG[0];
                $gejala_id = $dataG[1];
                if($gejala_nilai != 0 ){
                    $gejala_selected[] = array('id'=>$gejala_id, 'nilai'=>$gejala_nilai);
                }
            }
            // print_r($gejala_selected);
            $hitung = count($gejala_selected);
            if($hitung<2){
                echo "<script>window.alert('Pilih gejala lebih dari 1')</script>";
                header('location:diagnosis.php');
            }
            // memberikan id_penyakit ke dalam array
            else{
                foreach($gejala_selected as $gejala_select){
                    $id = $gejala_select['id'];
                    $nilai = $gejala_select['nilai'];
                    $query = mysqli_query($conn,"SELECT * FROM aturan_tbl WHERE id_gejala='$id'");
                    foreach($query as $kunci){
                        $kunci += ["cfuser"=>$nilai]; // kunci = kunci + ["cfuser"=>$nilai]
                        $hasil[$kunci['id_penyakit']][] = $kunci;
                    }
                }
                // echo "<br />";
                // print_r($hasil);
                
                // perhitungan CFcombine
                foreach ($hasil as $kunci2 => $kunci3){
                    $jumlah = count($kunci3);
                    $cf_lama = $kunci3[0]['cfuser'] * ($kunci3[0]['mb_aturan'] - $kunci3[0]['md_aturan']);
                    $id_penyakit = $kunci3[0]['id_penyakit'];
                    for ($i = 1; $i < $jumlah; $i++){
                        $id_penyakit = $kunci3[$i]['id_penyakit'];
                        $cf_baru = $kunci3[$i]['cfuser'] * ($kunci3[$i]['mb_aturan'] - $kunci3[$i]['md_aturan']);
                        $cf_lama = $cf_lama + $cf_baru * (1 - $cf_lama);
                    }
                    $hasil = $cf_lama * 100;
                    $nilai_cf[] = array('nilai_cf'=>$hasil, 'id_penyakit'=>$id_penyakit);
                }
                rsort($nilai_cf);
                // print_r($nilai_cf);

                $arrGejala = serialize($gejala_selected);
                $arrPenyakit = serialize($nilai_cf);

                $id_penyakit = $nilai_cf[0]['id_penyakit'];
                $cf_penyakit = $nilai_cf[0]['nilai_cf'];
                
                if(!empty($nama_peternak) && !empty($tanggal) && !empty($arrPenyakit) && !empty($arrGejala) && !empty($provinsi) && !empty($distrik)) {
                    $input = mysqli_query($conn, "INSERT INTO log_hasil(nama_peternak,tanggal,penyakit,gejala,provinsi,kabupaten,hasil_id,nilai)
                            VALUES('$nama_peternak','$tanggal','$arrPenyakit','$arrGejala','$provinsi','$distrik','$id_penyakit','$cf_penyakit')");
                    if($input){
                        echo "<script>console.log('Data Tersimpan')</script>";
                    } else {
                        echo "<script>console.log('Data Tidak Tersimpan')</script>";
                    }
                }

                $sql_penyakit = mysqli_query($conn, "SELECT * FROM penyakit_tbl WHERE id_penyakit='$id_penyakit'");
                $penyakit = mysqli_fetch_array($sql_penyakit);
        
        } ?>
        <div class="card">
            <div class="card-header">
                Hasil Diagnosa Penyakit Kambing
            </div>
            <div class="card-body">
                <h5 class="card-title">
                    Kemungkinan Penyakit yang di derita oleh kambing <?php echo $penyakit['penyakit_nama']. " ($cf_penyakit %)" ;?>
                </h5>
                <p class="card-text">Gejala - gejala yang dipilih : </p>
                <?php $no = 1;
                foreach($gejala_selected as $gejala){
                    $id_gejala = $gejala['id'];
                    $sql_gejala = mysqli_query($conn, "SELECT * FROM gejala_tbl WHERE id_gejala='$id_gejala'");
                    $nama_gejala = mysqli_fetch_array($sql_gejala);
                ?>
                <ul>
                    <li><?=$no?>. <?= $nama_gejala['gejala_nama'] ?></li>
                </ul><?php $no++; } ?>
            </div>
        </div>
            <div class="card mt-3">
                <div class="card-header">
                    Penjelasan Penyakit
                </div>
                <div class="card-body">
                  <?= $penyakit['penyakit_penjelasan'] ?>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    Solusi Penyakit
                </div>
                <div class="card-body">
                  <?= $penyakit['penaykit_penanganan'] ?>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    Kemungkinan Lain
                </div>
                <div class="card-body">
                  <ul>
                      <?php  
                        for($i=1; $i<count($nilai_cf); $i++){
                            $id_penyakit = $nilai_cf[$i]['id_penyakit'];
                            $nilai = $nilai_cf[$i]['nilai_cf'];
                            $sql_penyakit = mysqli_query($conn, "SELECT * FROM penyakit_tbl WHERE id_penyakit='$id_penyakit'");
                            $penyakit = mysqli_fetch_array($sql_penyakit);
                      ?>
                      <li class="mb-2"><?= $penyakit['penyakit_nama']. " ($nilai %)"  ?></li><?php } ?>
                  </ul>
                </div>
            </div>
            <a href="diagnosis.php"><button type="button" class="btn btn-link mt-3">Kembali diagnosa</button></a>
    <?php }}?>
    <!-- footer -->
    <?php include('footer.php'); ?>
    <script src="css/jquery.js"></script>
    <script>
        $(document).ready(function(){
            $.ajax({
                type:'post',
                url:'config/dataProvince.php',
                success:function(hasil_province){
                    $("select[name=nama_province").html(hasil_province);
                    // console.log(hasil);     
                }
            });

            $("select[name=nama_province]").on("change", function(){
                var id_province_terpilih = $("option:selected",this).attr("id_province");
                // alert(id_province_terpilih);
                $.ajax({
                    type:'post',
                    url:'config/dataDistrict.php',
                    data:'id_province='+id_province_terpilih,
                    success:function(hasil_districs){
                        // console.log(hasil_districs);
                        $("select[name=nama_districs]").html(hasil_districs);
                    }
                })
            });
        });
    </script>
</body>
</html>