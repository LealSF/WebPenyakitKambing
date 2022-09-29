<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Grafik</title>
    <link rel="stylesheet" href="../css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../css/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/de6a8fd242.js" crossorigin="anonymous"></script>
</head>
<body class="container-fluid">
    <!-- navbar -->
    <?php include('headerdas.php'); ?>
    <div class="row no-gutters mt-5">
        <div class="col-md-2 bg-dark mt-2 pr-3 pt-4">
            <!-- sidebar -->
            <?php include('sidebardas.php'); ?>
        </div>
        <div class="col-md-10 p-5 pt-10">
            <h3><i class="fa-solid fa-chart-pie m-lg-3"></i>Grafik Data Penyakit Kambing</h3><hr>
            <div class="row">
                <div class="col">
                    <canvas id="myChart" class="m-auto" style="height:27vh; width:27vw;"></canvas>
                </div>
                <div class="col">
                    <table class="table table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Penyakit</th>
                                <th scope="col" class="text-center">Jumlah</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $dt1 = mysqli_query($conn, "SELECT hasil_id,penyakit_nama,COUNT(*) total FROM log_hasil
                            INNER JOIN penyakit_tbl ON penyakit_tbl.id_penyakit = log_hasil.hasil_id GROUP BY hasil_id,penyakit_nama");
                                $queryLog = mysqli_query($conn, "SELECT * FROM log_hasil"); 
                                if (mysqli_num_rows($queryLog) < 0 ){
                                    echo "<tr><td class='text-center' colspan='3'>Tidak Ada Data</td></tr>";
                                } else{
                                    $no = 1;
                                    while($data = mysqli_fetch_array($dt1)){
                            ?>
                            <tr>
                                <td class="text-center"><?=$no;?></td>
                                <td><?= $data['penyakit_nama']; ?></td>
                                <td><?= $data['total']; ?></td>
                            </tr>
                            <?php $no++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Unutk menampilkan data tiap provinsi -->
            <div class="row">
                <?php
                    $sql = "SELECT GROUP_CONCAT(
                        DISTINCT CONCAT('COUNT(IF(hasil_id=''', hasil_id,''', nilai,NULL)) AS `py_',penyakit_tbl.penyakit_nama,'`'))
                        FROM (SELECT penyakit_tbl.penyakit_nama, log_hasil.hasil_id, penyakit_tbl.id_penyakit FROM log_hasil JOIN penyakit_tbl ON log_hasil.hasil_id = penyakit_tbl.id_penyakit) penyakit_tbl";
                    $result = mysqli_query($conn,$sql);
                    $result_row = mysqli_fetch_row($result);
                    // print_r($result_row); 
                    $pivot_columns = $result_row[0];
                    if(!$pivot_columns){
                        $pivot_columns = 'NULL';
                    }
                    // print_r($pivot_columns);
                    $sql = "SELECT provinsi,$pivot_columns FROM log_hasil INNER JOIN penyakit_tbl ON log_hasil.hasil_id = penyakit_tbl.id_penyakit GROUP BY provinsi";
                    $pivot_result = mysqli_query($conn,$sql);
                    $pivot = mysqli_fetch_all($pivot_result,MYSQLI_ASSOC);
                    // print_r($pivot);
                    $py = mysqli_query($conn, "SELECT * FROM penyakit_tbl");
                    
                ?>
                <h3 class="mt-3"><i class="fa-solid fa-map m-lg-3"></i> Data Penyebaran Panyakit Berdasarkan Porvinsi<hr></h3>
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <td scope="col" class="text-center">No</td>
                            <td scope="col" class="text-center">Provinsi</td>
                            <?php
                            //    buat nama coloum
                            foreach($pivot[0] as $key => $value){
                                if(strpos($key,'py_') !== false){
                                    $nama = explode('_',$key);
                                    echo "<td scope='col' class='text-center'>".$nama[1]."</td>";
                                }
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1;
                            count($pivot);
                            foreach($pivot as $row){
                        ?>
                        <tr>
                            <td><?= $no; ?></td>
                            <?php 
                                foreach($row as $key => $value){
                                    echo "<td>$value</td>";
                                }
                            ?>
                        </tr>
                        <?php $no++; } ?>
                    </tbody>                                 
                </table>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="row bg-primary flex-column">
        <div class="py-3 text-center">
          <p>&copy; Labay EL Sulthan Fatta</p>
          </div>
    </div>

    <?php  
        foreach($dt1 as $data){
            $hasil_penyakit[] = array('nama_penyakit'=>$data['penyakit_nama'], 'total'=>$data['total']);
        }
    ?>
    <script src="../css/chart.min.js"></script>
    <script>
        const data = {
            labels: [
                <?php foreach($hasil_penyakit as $nama_penyakit){ echo "'$nama_penyakit[nama_penyakit]',"; } ?>
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [ <?php foreach($hasil_penyakit as $total){ echo $total['total'].","; } ?> ],
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(249, 0, 255)',
                'rgb(63, 255, 0)',
                'rgb(0, 0, 139)',
                'rgb(218, 112, 214)',
                'rgb(63, 255, 128)'
                ],
                hoverOffset: 4
            }]
        };
        const config = {
            type: 'pie',
            data: data,
        };
        var myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
</body>
</html>