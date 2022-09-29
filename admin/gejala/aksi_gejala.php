<?php
    session_start();
    if(!isset($_SESSION['admin_username']) && !isset($_SESSION['admin_password'])){
        header('location:../login.php');
      }

    include '../../config/koneksi.php';
    
    if(isset($_POST['btn_create'])){
        $kode_gajala = $_POST['kode_gejala'];
        $nama_gejala = $_POST['nama_gejala'];
        if(!empty($kode_gajala) && !empty($nama_gejala)){
            $query = mysqli_query($conn, "INSERT INTO gejala_tbl (`id_gejala`,`gejala_nama`) VALUES ('$kode_gajala','$nama_gejala')");
            echo "<script>window.alert('Data Berhasil Ditambah')</script>";
            header('location:../dataGejala.php');
        }
        else{
            echo "<script>window.alert('Data Tidak Berhasil Ditambah')</script>";
            header('location:../dataGejala.php');
        }
    }

    elseif(isset($_POST['btn_update'])){
        $kode_gajala = $_POST['kode_gejala'];
        $gejala_nama = $_POST['nama_gejala'];
        // Jika terisi
        if(!empty($kode_gajala) && !empty($gejala_nama)){
            $update = mysqli_query($conn, "UPDATE gejala_tbl SET gejala_nama='$gejala_nama' WHERE id_gejala='$kode_gajala'")or (mysqli_error());
            header('location: ../dataGejala.php');
            echo "<script>window.alert('Data berhasil diperbarui')</script>";
        }
        else {
            header('location: ../dataGejala.php');
            echo "<script>window.alert('Data tidak berhasil diperbarui')</script>";
        }
    }
?>