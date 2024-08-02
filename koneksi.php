<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "panic_button";

    date_default_timezone_set('Asia/Jakarta');

    $koneksi = new mysqli($host, $username, $password, $database);

    if ($koneksi->connect_error) {
        die("Koneksi ke database gagal: " . $koneksi->connect_error);
    }
?>
