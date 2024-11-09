<?php
require_once "../Config.php";
require_once "Chats.php";
$chatsObj = new Chats($conex); // creamos un objeto Group y le damos la conexion a la BD
$method = $_SERVER['REQUEST_METHOD']; // el metodo http que recibe, default es GET
$endpoint = $_SERVER['PATH_INFO'];    // la URL, pero toma la parte final, lo que no sea ruta
header('Content-Type: application/json'); // para que  la pagina sepa que se esta usando json

switch ($method) {
    case 'GET':
         if (preg_match('/^\/Chats\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $chats = $chatsObj->getAllChats($id);
            echo json_encode($chats);
        }elseif(preg_match('/^\/Mensajes\/(\d+)\/(\d+)$/', $endpoint, $matches)){
            $id_c = $matches[1];
            $id_u = $matches[2];
            $Reslut = $chatsObj->getMessages($id_c,$id_u);
            echo json_encode($Reslut);
        }
        break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true); // Obtener datos de la solicitud
            if ($endpoint === '/Mensaje') {
                $result = $chatsObj->sendMessage($data);
                echo json_encode($result);
            }
            break;
    
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true); // Obtener datos de la solicitud
            if ($endpoint === '/MarcarLeidos') {
                $result = $chatsObj->markAsRead($data);
                echo json_encode($result);
            }
            break;

    case 'DELETE':
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
        break;
}