<?php
session_start();

if (isset($_SESSION['ID_usuario'])) {
    echo json_encode([
        'ID_usuario' => $_SESSION['ID_usuario'],
        'Nombre_usuario' => $_SESSION['username']
    ]);
} else {
    echo json_encode(['error' => 'No hay usuario logueado']);
}

