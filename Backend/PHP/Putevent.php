
<?php
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php';
session_start();

if (isset($_POST["Titulo"], $_POST["Descripcion"], $_POST["Lugar"], $_POST["Fecha"], $_POST["Start"], $_POST["Fin"], $_POST["Latitud"], $_POST["Longitud"]) &&
    !empty($_POST["Titulo"]) && !empty($_POST["Descripcion"]) && !empty($_POST["Lugar"]) && !empty($_POST["Fecha"]) && 
    !empty($_POST["Start"]) && !empty($_POST["Fin"]) && !empty($_POST["Latitud"]) && !empty($_POST["Longitud"])) {
    $data = array(

        'ID_usuario' => $_SESSION['ID_usuario'],
        'Titulo' => $_POST["Titulo"],
        'Descripcion' => $_POST["Descripcion"],
        'Lugar' => $_POST["Lugar"],
        'Fecha_encuentro' => $_POST["Fecha"],
        'Hora_inicio' => $_POST["Start"],
        'Hora_fin' => $_POST["Fin"],
        'Latitud' => $_POST["Latitud"],
        'Longitud' => $_POST["Longitud"]
    );
    $jsondata = json_encode($data);                                                    // codifica el array en JSON
    $ch = curl_init($BaseURL . '/Event');
    curl_setopt($ch, CURLOPT_POST, 1);                                                // especifica una solicitud de tipo POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);                                  // manda el array con la info
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));    // Indica que es tipo JSON
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                  /// Devuelve el resultado de la transferencia como cadena en lugar de mostrarlo directamente
    $result = curl_exec($ch);                                                        // manda la solicitud y devuelve el resultado
    curl_close($ch);                                                                 // cierra el curl
    $response = json_decode($result, true);                                         // decodifica el resultado JSON
    if (isset($response) && $response) {
        header("Location: ../../Frontend/HTML/home.html");
        exit();
    } else {
      
    }
} else {
    header("Location: ../../Frontend/HTML/index.html");
    exit();
}