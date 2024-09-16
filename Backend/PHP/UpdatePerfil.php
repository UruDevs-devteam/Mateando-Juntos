<?php
session_start();
$ID_usuario = $_SESSION['ID_usuario'];
$username = $_SESSION['username'];
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_Users/Api_Usuarios.php';

if (isset($_FILES["profile_picture"]) && isset( $_POST["username"], $_POST["bio"])) {
    $data = array(                                                                
        'profile_picture' => $_FILES["profile_picture"],
        'username' => $_POST["username"],
        'bio' => $_POST["bio"],
        'User_ID' => $ID_usuario
    );
    $jsondata = json_encode($data);
    $ch = curl_init($BaseURL . '/Perfil');  
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
    $result = curl_exec($ch);  
    curl_close($ch);  
    $response = json_decode($result, true);


    if (isset($response['success']) && $response['success'] == true) {
        header("Location: ../../Frontend/HTML/home.html");  
        exit();
    } else {
        header("Location: ../../Frontend/HTML/Settings_user.html");  
        exit();
    }
} else {
    header("Location: ../../Frontend/HTML/Settings_user.html");
    exit();
}