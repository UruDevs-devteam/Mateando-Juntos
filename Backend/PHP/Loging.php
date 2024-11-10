<?php
session_start();
$BaseURL = 'http://web/Backend/APIs/API_Users/API_Usuarios.php';

if (isset($_POST["username"], $_POST["password"])) {
    $data = [
        'UserName' =>  $_POST["username"],
        'Password' =>  $_POST["password"],
    ];
    $ch = curl_init($BaseURL . '/User/Verify'); // URL para verificar el usuario
    $jsondata = json_encode($data);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch); // manda la solicitud y devuelve el resultado
    curl_close($ch);
    $responseData = json_decode($response, true);

    if (isset($responseData['success']) && $responseData['success']) {
        $_SESSION['username'] = $_POST["username"];
        $_SESSION['ID_usuario'] = $responseData['ID_usuario'];  
        // En lugar de establecer la sesión, envía el token y los datos al frontend
        echo '
        <script>
            // Almacena el token JWT en localStorage
            localStorage.setItem("jwtToken", ' . json_encode($responseData['token']) . ');
            localStorage.setItem("sessionData", JSON.stringify({
                ID_usuario: ' . json_encode($responseData['ID_usuario']) . ',
                Nombre_usuario: ' . json_encode($_POST["username"]) . '
            }));
            alert("Inicio de sesión exitoso. Redirigiendo a la página de inicio...");
            window.location = "../../Frontend/HTML/home.html";
        </script>';
        exit();
    } else {
        echo '
        <script>
        alert("Usuario o contraseña incorrectas");
        window.location = "../../Frontend/HTML/index.html";
        </script>
           ';   
       
    }
} else {
    echo '
        <script>
        alert("Por favor ingrese datos validos");
        window.location = "../../Frontend/HTML/index.html";
        </script>
           '; 
}
