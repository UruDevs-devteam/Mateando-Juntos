<?php
require_once "../Config.php";
require_once "User.php";
$User_obj = new User($conex); // creamos un objeto Post y le damos la conexion a la BD
$method = $_SERVER['REQUEST_METHOD']; // el metodo http que recibe, default es GET
$endpoint = $_SERVER['PATH_INFO'];    // la URL, pero toma la parte final, lo que no sea ruta
header('Content-Type: application/json'); // para que la pagina sepa que se esta usando json

switch ($method) {
    case 'GET':
        if ($endpoint == '/Users') {     // si el enpdoint es "/Usuarios"
            $usuarios = $user_obj->GetUsers();  // llama el metodo getusers y guarda los usuarios en una variable
            echo json_encode($User);
        } elseif (preg_match('/^\/User\/(\d+)$/', $endpoint, $matches)) {  // Verifica si en endpoint termina en un numero.
            $id = $matches[1];                                              // hagara el numero
            $Usuario = $User_obj->GetUserbyID($id);                            // llama al metodo indicado
            echo json_encode($Usuario);
        } elseif (preg_match('/^\/User\/([a-zA-Z0-9_]+)$/', $endpoint, $matches)) {  // verifica si el enpoint tiene mayusculas,minusculas,numeros o "_" (el nombre de usuario)
            $name = $matches[1];                                              // hagara el nombre
            $Usuario = $User_obj->GetUserbyName($name);                         // llama al metodo indicado
            echo json_encode($Usuario);
        } else {                                                       // si no encuentra el endpoint(esta vacio o no es uno de los anteriores), da error.
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);  // manda la solitud al curl para pedirle los parametros                                   
        if ($endpoint == '/Usuario') {
            if (Validar_Data_User($data)) {
                $Resul = $User_obj->AddUser($data);                  // llama al metodo  
                echo json_encode(['success' => $Resul]);             // guarda true o false, depende si se logro la incercion
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacio o mal formado']);
            }
        } elseif ($endpoint == '/User/Verify') {                  // verifica si el usuario y la contraseña son correctas.
            $Resul = $User_obj->VerifyUser($data);
            echo json_encode(['success' => $Resul]);
        } else {                                                      // si los atributos estan mal o no existen, da error
            http_response_code(400);
            echo json_encode(['error' => 'JSON vacío o mal formado']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($endppoint == 'User') {
            $Resul = $User_obj->DeleteUser($data);
            echo json_encode(['success' => $Resul]);
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

//////////////////////////////////////////////// metodos de validacion //////////////////////////////////////////////////////////////////

function Validar_Data_User($data)
{
    if (empty($data)) {             // Verificar que el array de datos no esté vacío
        return false;
    } elseif (                      // verifica que cada parametro exista y no este vacio.
        !isset($data['Full_name']) || empty($data['Full_name']) ||
        !isset($data['Username']) || empty($data['Username']) ||
        !isset($data['Email']) || empty($data['Email']) ||
        !isset($data['Pass']) || empty($data['Pass'])
    ) {
        return false;
    } else {
        return true;
    }
}


