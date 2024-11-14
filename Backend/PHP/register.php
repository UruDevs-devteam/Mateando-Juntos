<?php
$BaseURL = 'http://web/Backend/APIs/API_Users/API_Usuarios.php';

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
    $result = curl_exec($ch); 
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
        exit();
    }                                                       // manda la solicitud y devuelve el resultado
    curl_close($ch);                                                                 // cierra el curl
    $response = json_decode($result, true);                                         // decodifica el resultado JSON
    var_dump($result); 
    if (isset($response['success']) && $response['success'] == true) { // usa $response en lugar de $result

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
        alert("Usuario o correo ya registrados");
        window.location = "../../Frontend/HTML/index.html";
        </script>
           ';  
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