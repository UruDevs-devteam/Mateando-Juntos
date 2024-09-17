<?php
session_start();
$ID_usuario = $_SESSION['ID_usuario'];
$username = $_SESSION['username'];
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_Users/Api_Usuarios.php';

if (isset($_FILES["profile_picture"]) && isset($_POST["username"]) && isset($_POST["bio"])) {
    // Leer el contenido de la imagen
    $imgContent = file_get_contents($_FILES["profile_picture"]["tmp_name"]);
    $imgContent = base64_encode($imgContent); // Codificar en base64 para enviar como JSON

    // Preparar los datos para enviar
    $data = array(
        'profile_picture' => $imgContent,
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
    header("Location: ../../Frontend/HTML/login.html");
    exit();
}

