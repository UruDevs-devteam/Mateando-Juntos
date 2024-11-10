<?php
$Nombre_Host = 'mysql_master';  // Nombre del servicio de MySQL en docker-compose.yml
$Nombre_Usuario = 'root';
$contra = getenv('MYSQL_ROOT_PASSWORD');    // Contraseña definida en docker-compose.yml
$Base_de_datos = 'Mateando_Juntos';

$conex = mysqli_connect($Nombre_Host, $Nombre_Usuario, $contra, $Base_de_datos);


