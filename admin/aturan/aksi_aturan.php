<?php 
    session_start();
    if(!isset($_SESSION['admin_username']) && !isset($_SESSION['admin_password'])){
        header('location:../../login.php');
    }
    
    include '../../config/koneksi.php';

    if(isset($_POST['btn_save'])){
        $kode_aturan = $_POST['kode_aturan'];
        $nama_penyakit = $_POST['nama_penyakit'];
        $gejala = $_POST['nama_gejala'];
        $nilai_mb = $_POST['nilai_mb'];
        $nilai_md = $_POST['nilai_md'];
        // Jika terisi
        if(!empty($kode_aturan) && !empty($nama_penyakit) && !empty($gejala) && !empty($nilai_mb) && !empty($nilai_md)){
            $insert = "INSERT INTO aturan_tbl VALUES ('$kode_aturan','$nama_penyakit','$gejala','$nilai_mb','$nilai_md')";
            $query = mysqli_query($conn,$insert);
            echo "<script>window.alert('Data Berhasil Ditambah')</script>";
            header('location:../dataAturan.php');
        }
        // Jika tidak
        else{
            echo "<script>window.alert('Data Tidak Berhasil Ditambah')</script>";
            header('location:../dataAturan.php');
        }
    }
    elseif(isset($_POST['btn_update'])){
        $kode_aturan = $_POST['kode_aturan'];
        $nama_penyakit = $_POST['nama_penyakit'];
        $gejala = $_POST['nama_gejala'];
        $nilai_mb = $_POST['nilai_mb'];
        $nilai_md = $_POST['nilai_md'];
        // JIka terisi
        if(!empty($kode_aturan)&&!empty($nama_penyakit)&&!empty($gejala)&&!empty($nilai_mb)&&!empty($nilai_md)){
            $update = mysqli_query($conn,"UPDATE aturan_tbl SET id_penyakit='$nama_penyakit',id_gejala='$gejala',mb_aturan='$nilai_mb',md_aturan='$nilai_md' WHERE id_aturan='$kode_aturan'");
            // Jika berhasil diupdate
            if($update){
                echo "<script>window.alert('Data Berhasil Diupdate')</script>";
                header('location:../dataAturan.php');
            }
            else{
                echo "<script>window.alert('Data Tidak Berhasil Diupdate')</script>";
                header('location:../dataAturan.php');
            }
        }
    }
?>