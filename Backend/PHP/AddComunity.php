<?php
$BaseURL = 'http://web/Backend/APIs/API_Groups/API_Groups.php';
session_start();


if (
    isset($_POST["communityName"], $_POST["communityDescription"]) &&
    !empty($_POST["communityName"]) && !empty($_POST["communityDescription"]) && isset($_FILES["profile_picture"]) ) {
        
    $imgContent = file_get_contents($_FILES["profile_picture"]["tmp_name"]);
    $imgContent = base64_encode($imgContent); // Codificar en base64 para enviar como JSON
    $token = $_POST["token"];

    $data = array(

        'communityName' => $_POST['communityName'],
        'communityDescription' => $_POST["communityDescription"],
        'profile_picture' => $imgContent,
        'User_creator' => $_SESSION['ID_usuario']
    );
   

    $jsondata = json_encode($data);                                                    // codifica el array en JSON
    $ch = curl_init($BaseURL . '/Group');
    curl_setopt($ch, CURLOPT_POST, 1);                                                // especifica una solicitud de tipo POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);                                  // manda el array con la info
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(   'Content-Type: application/json',"Authorization: Bearer $token"));    // Indica que es tipo JSON
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                  /// Devuelve el resultado de la transferencia como cadena en lugar de mostrarlo directamente
    $result = curl_exec($ch);                                                        // manda la solicitud y devuelve el resultado
    curl_close($ch);                                                                 // cierra el curl
    $response = json_decode($result, true);                                         // decodifica el resultado JSON
    if (isset($response) && $response) {
        header("Location: ../../Frontend/HTML/home_comunidades.html");
        exit();
    } else {

        echo '<script>alert("error al crear comunidad");
    window.location = "../../Frontend/HTML/home.html";</script>';
    }
} else {
    echo '
    <script>
    alert("Datos incorrectos, inserte nuevamente");
    window.location = "../../Frontend/HTML/home.html";
    </script>
       ';
    exit();
}