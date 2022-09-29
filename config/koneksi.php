<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "penyakit_kambing";

    $conn = mysqli_connect($server,$username,$password,$database);

    if(!$conn){
        die("Koneksi gagal : " .mysqli_connect_error());
    }
?>