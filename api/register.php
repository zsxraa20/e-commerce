<?php

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['username']) || !isset($data['email']) || !isset($data['password'])){
    echo "error";
    exit();
}

$username = $data['username'];
$email = $data['email'];
$password = $data['password'];

$sql = "INSERT INTO users (username,email,password,role)
VALUES ('$username','$email','$password','user')";

if($conn->query($sql)){
    echo "success";
}else{
    echo "error";
}

?>