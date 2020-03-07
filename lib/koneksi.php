<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "dbpangkat";

// Koneksi dan memilih database di server
$con = mysqli_connect($server, $username, $password) or die("Koneksi gagal");
mysqli_select_db($con, $database) or die("Database tidak bisa dibuka");


function pr($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    exit();
}

function vd($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    exit();
}
