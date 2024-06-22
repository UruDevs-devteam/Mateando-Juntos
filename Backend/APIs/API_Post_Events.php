<?php
require_once "Config.php";
require_once "Post.php";
require_once "Event.php";
$Post_obj = new Post($conex); // creamos un objeto Post y le damos la conexion a la BD
$Event_obj = new Event($conex);// creamos un objeto Evento y le damos la conexion a la BD
$method = $_SERVER['REQUEST_METHOD']; // el metodo http que recibe, default es GET
$endpoint = $_SERVER['PATH_INFO'];    // la URL, pero toma la parte final, lo que no sea ruta
header('Content-Type: application/json'); // para que la pagina sepa que se esta usando json

switch ($method) {
    case 'GET': //select
        if ($endpoint == '/Posts') {     // si el endpoint es /Posts
            $Posts = $Post_obj->GetPosts();  // llama el metodo get post y guarda los post en una variable
            echo json_encode($Posts);         // imprime los post codificados en json
        } elseif (preg_match('/^\/Post\/(\d+)$/', $endpoint, $matches)) {  // Verifica si en endpoint termina en un numero.
            $id = $matches[1];                                              // hagara el numero
            $Post = $Post_obj->GetPostByID($id);                            // llama al metodo indicado
            echo json_encode($Post);
        } elseif ($endpoint == '/Events') {
            $Evento = $Event_obj->GetEvents();
            echo json_encode($Evento);
        } elseif (preg_match('/^\/Event\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $Event = $Event_obj->GetEventByID($id);
            echo json_encode($Event);

        } else {                                                       // si no encuentra el endpoint, da error.
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;

    case 'POST': // insert
        $data = json_decode(file_get_contents('php://input'), true);  // manda la solitud al curl para pedirle los parametros
        if ($endpoint == '/Post') {
            $Resul = $Post_obj->AddPost($data);                           //llama al metodo y guarda si logró hacer la incerción
            echo json_encode(['success' => $Resul]);
        } elseif ($endpoint == '/Multi') {
            $Resul = $Post_obj->AddMulti($data);
            echo json_encode(['success' => $Resul]);
        } elseif ($endpoint == '/Event') {
            $Resul = $Event_obj->AddEvent($data);
            echo json_encode(['success' => $Resul]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;

    case 'DELETE':// delete
        $data = json_decode(file_get_contents('php://input'), true);
        if ($endpoint == '/Post') {
            $Resul = $Post_obj->DeletePost($data);
            echo json_encode(['success' => $Resul]);
        } else if ($endpoint == '/Event') {
            $Resul = $Event_obj->DeleteEvent($data);
            echo json_encode(['success' => $Resul]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;
    default: // Metodo no permitido
        http_response_code(405);
        echo json_encode(['error' => 'Método HTTP no permitido']);
        break;
}


