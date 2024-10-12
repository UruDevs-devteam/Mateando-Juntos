<?php
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_Users/Api_Usuarios.php';

if (isset($_POST["full_name"], $_POST["username"], $_POST["email"], $_POST["password"])) {
    $data = array(                                                                    // pasa los parametros a un array
        'Full_name' => $_POST["full_name"],
        'Username' => $_POST["username"],
        'Email' => $_POST["email"],
        'Pass' => $_POST["password"]
    );
    $jsondata = json_encode($data);                                                    // codifica el array en JSON
    $ch = curl_init($BaseURL . '/Usuario');
    curl_setopt($ch, CURLOPT_POST, 1);                                                // especifica una solicitud de tipo POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);                                  // manda el array con la info
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));    // Indica que es tipo JSON
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                  /// Devuelve el resultado de la transferencia como cadena en lugar de mostrarlo directamente
    $result = curl_exec($ch);                                                        // manda la solicitud y devuelve el resultado
    curl_close($ch);                                                                 // cierra el curl
    $response = json_decode($result, true);                                         // decodifica el resultado JSON
    if (isset($response['success']) && $response['success'] == true) {

        echo '
        <script>
        alert("Usuario registrado exitosamente");
        window.location = "../../Frontend/HTML/index.html";
        </script>
           ';   
        exit();
    } else {
        echo '
        <script>
        alert("Error en registrar al usuario (error en la api)");
        window.location = "../../Frontend/HTML/index.html";
        </script>
           ';                             
        exit();
    }
} else {
    echo '
        <script>
        alert("Error en registrar al usuario (error en guardar los parametros)");
        window.location = "../../Frontend/HTML/home.html";
        </script>
           ';   
    exit();
}