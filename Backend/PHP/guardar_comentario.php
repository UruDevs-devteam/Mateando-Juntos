<?php
$BaseURL = 'http://localhost/Mateando-Juntos/Backend/APIs/API_PO_EV/API_Post_Events.php';

$data = [
    'Contenido' => $_POST["contenido"] ?? null, // El contenido del comentario
    'ID_perfil' => $_POST["ID_usuario"] ?? null, // ID del perfil o usuario
    'ID_post' => $_POST["ID_post"] ?? null // ID del post
];

if ($data['Contenido'] && $data['ID_perfil'] && $data['ID_post']) {
    $ch = curl_init($BaseURL . '/Comentario');
    $jsondata = json_encode($data); 
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $response = curl_exec($ch); 
    curl_close($ch); 
    $responseData = json_decode($response, true);

    // Mostrar respuesta
    print_r($responseData);
} else {
    echo "Datos faltantes: ";
    print_r($data); // Esto mostrará qué datos no están llegando correctamente
}
