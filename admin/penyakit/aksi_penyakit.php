<?php
    session_start();
    if(!isset($_SESSION['admin_username']) && !isset($_SESSION['admin_password'])){
      header('location:../../login.php');
    }
    
    include '../../config/koneksi.php';
    if(isset($_POST['btn_simpan'])){
        $kode_penyakit = $_POST['kode_penyakit'];
        $nama_penyakit = $_POST['nama_penyakit'];
        $penjelasan = $_POST['penjelasan_penyakit'];
        $penanganan = $_POST['penanganan_penyakit'];
        $query = mysqli_query($conn, "SELECT * FROM penyakit_tbl WHERE id_penyakit='$kode_penyakit'");
        $check = mysqli_num_rows($query);

        $rand = rand();
        $ekstensi = array('png','jpg','jpeg');
        $filename = $_FILES['foto_penyakit']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if($check){
            echo "<script>alert('Kode penyakit sudah ada, silahkan masukan kode yang lain');</script>";
            header('location:../dataPenyakit.php');
        }
        else{
            // Jika Ekstensi Gambar salah
            if(!in_array($ext,$ekstensi)){
                echo "<script>alert('Ekstensi file harus png,jpg,jpeg')</script>";
                header('location:../dataPenyakit.php?aksi=create');
            } else { //Jika tidak
                //jika sudah terisi
                if(!empty($kode_penyakit) && !empty($nama_penyakit) && !empty($penjelasan) && !empty($penanganan) && !empty($filename)){
                    $xx = $rand.'_'.$filename;
                    move_uploaded_file($_FILES['foto_penyakit']['tmp_name'], '../../asset/gambar/'.$xx);
                    $input = mysqli_query($conn, "INSERT INTO penyakit_tbl (id_penyakit,penyakit_nama,gambar,penyakit_penjelasan,penaykit_penanganan) 
                    VALUES ('$kode_penyakit','$nama_penyakit','$xx','$penjelasan','$penanganan')");
                    echo "<script>alert('Data Berhasil Disimpan')</script>";
                    header('location:../dataPenyakit.php');
                }
                //jika ada yang belum terisi
                else{
                    echo "<script>alert('Data Berhasil Disimpan')</script>";
                    header('location:../dataPenyakit.php');
                }
            }
        }
    }

    elseif(isset($_POST['btn_update'])){
        $id = $_POST['kode_penyakit'];
        $nama = $_POST['nama_penyakit'];
        $penjelasan = $_POST['penjelasan_Penyakit'];
        $penanganan = $_POST['penanganan_penyakit'];
        $rand = rand();
        $ekstensi = array('png','jpg','jpeg');
        $filename = $_FILES['foto_penyakit']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Jika ekstensi gambar tidak terdaftar
        if(!in_array($ext,$ekstensi)){
            echo "<script>alert('Ekstensi file harus png,jpg,jpeg')</script>";
            header('location:../dataPenyakit.php?aksi=create');
        } else { //jika tidak
            if(!empty($id) && !empty($nama) && !empty($penjelasan) && !empty($penanganan) && !empty($filename)){
                $xx = $rand.'_'.$filename;
                move_uploaded_file($_FILES['foto_penyakit']['tmp_name'], '../../asset/gambar/'.$xx);
                $input = mysqli_query($conn, "UPDATE penyakit_tbl SET id_penyakit='$id', penyakit_nama='$nama',gambar='$xx', penyakit_penjelasan='$penjelasan', penaykit_penanganan='$penanganan' WHERE id_penyakit='$id'");
                echo "<script>window.alert('Data Berhasil Diupdate')</script>";
                header('location:../dataPenyakit.php');
            }
            else{
                echo "<script>window.alert('Data Tidak Berhasil Diupdate')</script>";
                header('location:../dataPenyakit.php');
            }
        }
    }
?>