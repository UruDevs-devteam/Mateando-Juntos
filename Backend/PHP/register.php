<?php
require_once "../BackOffice/Controler.php";

if (isset($_POST["full_name"], $_POST["username"], $_POST["email"], $_POST["password"])) {
$data = [
    'Full_name' => $_POST["full_name"],
    'Username' => $_POST["username"],
    'Email' => $_POST["email"],
    'Pass' => $_POST["password"]
];
if (AddUser($data)) {
    header("Location: ../../Frontend/HTML/login.html");
    exit();
} else {

    header("Location: ../../Frontend/HTML/index.html" );
    exit();
}

}else{

}