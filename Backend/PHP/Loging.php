<?php
session_start();
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_Users/Api_Usuarios.php';

if (isset($_POST["username"], $_POST["password"])) {
    $data = [
        'UserName' =>  $_POST["username"],
        'Password' =>  $_POST["password"],
    ];
    $ch = curl_init($BaseURL . '/User/Verify');                                   // URL para verificar el usuario
    $jsondata = json_encode($data); 
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $response = curl_exec($ch);                                                        // manda la solicitud y devuelve el resultado
    curl_close($ch); 
    $responseData = json_decode($response, true);
    if (isset($responseData['success']) && $responseData['success']) {
        $_SESSION['username'] = $_POST["username"];     
        echo '
        <script>
        alert("Sesion iniciada redirigiendo al Inicio de la pagina");
        window.location = "../../Frontend/HTML/home.html";
        </script>
           ';                            
        exit();
    } else {
        echo '
        <script>
        alert("Sesion no iniciada");
        window.location = "../../Frontend/HTML/login.html";
        </script>
           ';                                 // Verificación fallida, redirige a la página de login
        exit();
    }


}else {
    header("Location: ../../Frontend/HTML/login.html");
    exit();
}

