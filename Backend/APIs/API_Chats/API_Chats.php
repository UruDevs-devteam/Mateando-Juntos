<?php
use \Firebase\JWT\JWT;
require '/var/www/html/vendor/autoload.php';
require_once "../Config.php";
require_once "Chats.php";
$SecretKey = getenv('JWT_SECRET_KEY');
$chatsObj = new Chats($conex); // creamos un objeto Group y le damos la conexion a la BD
$method = $_SERVER['REQUEST_METHOD']; // el metodo http que recibe, default es GET
$endpoint = $_SERVER['PATH_INFO'];    // la URL, pero toma la parte final, lo que no sea ruta
header('Content-Type: application/json'); // para que  la pagina sepa que se esta usando json
// Endpoints que no requieren verificación de token
$endpointsPublicos = [];

if (!in_array($endpoint, $endpointsPublicos)) {
    // Obtener el token del encabezado Authorization
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Token no proporcionado']);
        exit();
    }
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $userData = verificarToken($token); // Si el token es válido, devuelve el payload
}

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

// Función para verificar el token JWT
function verificarToken($token) {
    global $SecretKey;
    try {
        $decoded = JWT::decode($token, $SecretKey, ['HS256']);
        return (array) $decoded;  // Retorna el contenido del token si es válido
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido o expirado']);
        exit();
    }
}