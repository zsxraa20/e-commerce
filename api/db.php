<?php

$host = "localhost";
$user = "ephoneuser";
$pass = "123456";
$db = "ephone";

$conn = new mysqli($host,$user,$pass,$db);

if ($conn->connect_error) {
    die("Database gagal konek");
}

?>