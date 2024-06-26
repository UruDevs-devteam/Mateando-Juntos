<?php
session_start();
require_once "../BackOffice/Controler.php";

if (isset($_POST["username"], $_POST["password"])) {
$data = [
    'UserName' =>  $_POST["username"],
    'password' =>  $_POST["password"],

];


if (VerifyUser($data)){
    header("Location:  ../../Frontend/HTML/home.html");
    exit();
}else{
    header("Location:  ../../Frontend/HTML/login.html");
    exit();
}

}