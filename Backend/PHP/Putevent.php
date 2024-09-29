
<?php
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php';
session_start();

if (isset($_POST["Titulo"], $_POST["Descripcion"], $_POST["Lugar"], $_POST["Start"], $_POST["Fin"])) {
    $data = array(

        'ID_usuario' => $_SESSION['ID_usuario'],
        'Titulo' => $_POST["Titulo"],
        'Descripcion' => $_POST["Descripcion"],
        'Lugar' => $_POST["Lugar"],
        'Fecha_encuentro' => $_POST["Fecha"],
        'Hora_inicio' => $_POST["Start"]. ':00',
        'Hora_fin' => $_POST["Fin"].':00'
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
      
        echo '
        <script>
        alert("error al subir evento");
        window.location = "../../Frontend/HTML/home.html";
        </script>
           ';   
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