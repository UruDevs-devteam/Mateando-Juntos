<?php
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php';
$BaseURL2 = 'http://localhost/Mateando-Juntos/Backend/APIs/API_Groups/API_Groups.php';
session_start();

if (
    isset($_POST["Titulo"], $_POST["Descripcion"], $_POST["Lugar"], $_POST["Fecha"], $_POST["Start"], $_POST["Fin"], $_POST["Latitud"], $_POST["Longitud"]) &&
    !empty($_POST["Titulo"]) && !empty($_POST["Descripcion"]) && !empty($_POST["Lugar"]) && !empty($_POST["Fecha"]) &&
    !empty($_POST["Start"]) && !empty($_POST["Fin"]) && !empty($_POST["Latitud"]) && !empty($_POST["Longitud"])
) {
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
    $jsondata = json_encode($data);

    $ch = curl_init($BaseURL . '/Event');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);

    if (isset($response["result"]) && $response["result"]) {
        if (isset($_POST["Community-Id"]) && !empty($_POST["Community-Id"])) {
            $data = array(
                'ID_comunidad' => $_POST["Community-Id"],
                'ID_evento' => $response["eventId"]
            );
            $jsondata = json_encode($data);

            $ch = curl_init($BaseURL2 . '/AddEventToGroup');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);

            $response = json_decode($result, true);
            if (isset($response["success"]) && $response["success"]) {
                echo '
                <script>
                alert("Evento subido exitosamente a la comunidad");
                window.location = "../../Frontend/HTML/home_comunidades.html";
                </script>';
            } else {
                echo '
            <script>
            alert("Evento no subido a la comunidad ");
            window.location = "../../Frontend/HTML/home_comunidades.html";
            </script>';
            }
        } else {
            echo '
            <script>
            alert("Evento subido exitosamente");
            window.location = "../../Frontend/HTML/home.html";
            </script>';
        }
    } else {
        echo '
        <script>
        alert("Error al subir evento");
        window.location = "../../Frontend/HTML/home.html";
        </script>';
    }
} else {
    echo '
    <script>
    alert("Datos incorrectos, inserte nuevamente");
    window.location = "../../Frontend/HTML/home.html";
    </script>';
}