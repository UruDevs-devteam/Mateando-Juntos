<?php
use \Firebase\JWT\JWT;
require '/var/www/html/vendor/autoload.php';
require_once "../Config.php";
require_once "Group.php";
$SecretKey = getenv('JWT_SECRET_KEY');
$Group_obj = new Group($conex); // creamos un objeto Group y le damos la conexion a la BD
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
        if ($endpoint == '/Groups') {
            $Groups = $Group_obj->getGroups();
            echo json_encode($Groups);
        } elseif (preg_match('/^\/Group\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $Group = $Group_obj->getGroupByID($id);
            echo json_encode($Group);
        } elseif (preg_match('/^\/Group\/([a-zA-Z0-9_]+)$/', $endpoint, $matches)) {
            $name = $matches[1];
            $Group_n = $Group_obj->getGroupByName($name);
            echo json_encode($Group_n);
        } elseif (preg_match('/^\/Users\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $Users = $Group_obj->GetUsers($id);
            echo json_encode($Users);
        } elseif (preg_match('/^\/Posts\/(\d+)$/', $endpoint, $matches)) { // Obtener posts en grupo
            $id = $matches[1];
            $posts = $Group_obj->GetPostsInGroup($id);
            echo json_encode($posts);
        } elseif (preg_match('/^\/Events\/(\d+)$/', $endpoint, $matches)) { // Obtener eventos en grupo
            $id = $matches[1];
            $events = $Group_obj->GetEventsInGroup($id);
            echo json_encode($events);
        } elseif (preg_match('/^\/GroupByUser\/(\d+)$/', $endpoint, $matches)) { // Obtener eventos en grupo
            $id = $matches[1];
            $events = $Group_obj->getCommunitiesByUserId($id);
            echo json_encode($events);
        }elseif ($endpoint == '/TopLikedPosts') { // Nuevo endpoint para posts populares
            $topLikedPosts = $Group_obj->getTopLikedPosts();
            echo json_encode($topLikedPosts);
        }  else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($endpoint == '/Group') {
            if (Valid_Data_Group($data)) {
                $Resul = $Group_obj->AddGroup($data);
                echo json_encode(['success' => $Resul]);

            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacio o mal formado']);
            }
        } elseif ($endpoint == '/UserG') {
            if (Valid_Data_User($data)) {
                $Resul = $Group_obj->AddUserToGroup($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacio o mal formado']);
            }
        } elseif ($endpoint == '/AddPostToGroup') { // Agregar un post a una comunidad
            if (Valid_Data_PostGroup($data)) {
                $Resul = $Group_obj->AddPostToGroup($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacio o mal formado']);
            }
        } elseif ($endpoint == '/AddEventToGroup') { // Agregar un evento a una comunidad
            if (Valid_Data_EventGroup($data)) {
                $Resul = $Group_obj->AddEventToGroup($data);
                echo json_encode(['success' => $Resul]);
            }  else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }}
        break;

    case 'DELETE':
        if ($endpoint == '/Group') {
            $Resul = $Group_obj->DeleteGroup($data);
            echo json_encode(['success' => $Resul]);
        } elseif ($endpoint == '/UserG') {
            if (Valid_Data_User($data)) {
                $Resul = $Group_obj->DeleteUserFromGroup($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacio o mal formado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método HTTP no permitido']);
        break;

}


function Valid_Data_Group($data)
{

    if (empty($data)) {
        return false;
    } elseif (
        !isset($data['communityName']) || empty($data['communityName']) ||
        !isset($data['profile_picture']) || empty($data['profile_picture']) ||
        !isset($data['communityDescription']) || empty($data['communityDescription']) ||
        !isset($data['User_creator']) || empty($data['User_creator'])
    ) {
        return false;
    } else {
        return true;
    }
}

function Valid_Data_User($data)
{

    if (empty($data)) {
        return false;
    } elseif (
        !isset($data['ID_usuario']) || empty($data['ID_usuario']) ||
        !isset($data['ID_comunidad']) || empty($data['ID_comunidad'])
    ) {
        return false;
    } else {
        return true;
    }
}

function Valid_Data_PostGroup($data) {
    return !(empty($data) ||
        !isset($data['ID_comunidad']) || empty($data['ID_comunidad']) ||
        !isset($data['ID_post']) || empty($data['ID_post']));
}

// Validar datos para agregar evento a grupo
function Valid_Data_EventGroup($data) {
    return !(empty($data) ||
        !isset($data['ID_comunidad']) || empty($data['ID_comunidad']) ||
        !isset($data['ID_evento']) || empty($data['ID_evento']));
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