<?php
require_once "../Config.php";
require_once "Group.php";
$Group_obj = new Group($conex); // creamos un objeto Group y le damos la conexion a la BD
$method = $_SERVER['REQUEST_METHOD']; // el metodo http que recibe, default es GET
$endpoint = $_SERVER['PATH_INFO'];    // la URL, pero toma la parte final, lo que no sea ruta
header('Content-Type: application/json'); // para que  la pagina sepa que se esta usando json

switch ($method) {
    case 'GET':
        if ($endpoint == '/Groups') {
            $Groups = $Group_obj->getGroups();
            echo json_encode($Groups);
        } elseif (preg_match('/^\/Group\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $Group = $Group_obj->getGropupByID($id);
            echo json_encode($Group);
        } elseif (preg_match('/^\/Group\/([a-zA-Z0-9_]+)$/', $endpoint, $matches)) {
            $name = $matches[1];
            $Group_n = $Group_obj->getGropupByName($name);
            echo json_encode($Group_n);
        } elseif (preg_match('/^\/Users\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $Group = $Group_obj->GetUsers($id);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($endpoint == '/Group') {
            if (Valid_Data($data)) {
                $Group_obj->AddGroup($data);
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

    case 'DELETE':
        if($endpoint == '/Group'){
            $Group_obj->DeleteGroup($data);
            echo json_encode(['success' => $Resul]);
        }else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método HTTP no permitido']);
        break;

}


function Valid_Data($data)
{

    if (empty($data)) {
        return false;
    } elseif (
        !isset($data['Group_name']) || empty($data['Group_name']) ||
        !isset($data['Photo']) || empty($data['Photo']) ||
        !isset($data['Descrip']) || empty($data['Descrip']) ||
        !isset($data['Creation_date']) || empty($data['Creation_date'])
    ) {
        return false;
    } else {
        return true;
    }
}