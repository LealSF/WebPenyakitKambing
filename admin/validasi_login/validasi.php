<?php 
    session_start();
    include '../../config/koneksi.php';
    $logout = $_GET['log'];
    
    if(isset($_POST['btn_login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $check = mysqli_query($conn,"SELECT * FROM admin_tbl WHERE admin_username='$username' AND admin_password='$password'");
        $hasil = mysqli_num_rows($check);
        // Jika berhasil Login
        if($hasil != 0){
            $rows = mysqli_fetch_assoc($check);
            $_SESSION['admin_username'] = $rows['admin_username'];
            $_SESSION['admin_password'] = $rows['admin_password	'];
            $_SESSION['admin_nama'] = $rows['admin_nama'];
            echo "<script>
            alert('Berhasil Login');
            </script>";
            header('location:../dashboard.php');
        // Jika gagal login
        } else{
            $_SESSION['field'] = "Maaf Username atau Password Tidak Terdaftar";
            echo 
            "<script type='text/javascript'>
            alert('Login Gagal');
            window.location.assign('../../login.php');
            </script>"
            ;
        }
    }

    elseif(isset($_POST['btn_create'])){
        $name_dokter = $_POST['nama_dokter'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        // isi user
        if(!empty($name_dokter)&&!empty($username)&&!empty($password)){
            $create = mysqli_query($conn, "INSERT INTO admin_tbl (admin_nama,admin_username,admin_password) VALUES ('$name_dokter','$username','$password')");
            echo 
            "<script type='text/javascript'>
                alert('Data User Berhasil Dibuat');
            </script>";
            header('location:../../login.php');
        }
        // Jika tidak berhasil dibuat
        else {
            echo 
            "<script type='text/javascript'>
                alert('Data User Tidak Berhasil Dibuat');
            </script>";
            header("location:../../login.php");
        }
    } else{
        if($logout == 'logout'){
            session_start();
            session_destroy();
            header('location:../../index.php');
        }
    }
?>
