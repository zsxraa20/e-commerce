<?php

include "db.php";

if(!isset($_POST['email']) || !isset($_POST['password'])){
    echo "error";
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if($result->num_rows > 0){

    $user = $result->fetch_assoc();

    if($user['role'] == "admin"){
        echo "admin";
    }else{
        echo "user";
    }

}else{
    echo "error";
}

?>