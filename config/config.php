<?php
$host = "localhost";
$user = "rizky";
$pass = "generazot";
$db   = "eclat";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
