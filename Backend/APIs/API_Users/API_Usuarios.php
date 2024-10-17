<?php
require_once "../Config.php";
require_once "User.php";
require_once "Perfil.php";
$User_obj = new User($conex); // creamos un objeto Usuario y le damos la conexion a la BD
$Perfil_obj = new Perfil($conex); // creamos un objeto Perfil y le damos conexion a la bd
$method = $_SERVER['REQUEST_METHOD']; // el metodo http que recibe, default es GET
$endpoint = $_SERVER['PATH_INFO'];    // la URL, pero toma la parte final, lo que no sea ruta
header('Content-Type: application/json'); // para que la pagina sepa que se esta usando json

switch ($method) {
    case 'GET':
        if ($endpoint == '/Users') {     // si el enpdoint es "/Usuarios"
            $usuarios = $User_obj->GetUsers();  // llama el metodo getusers y guarda los usuarios en una variable
            echo json_encode($usuarios);
        } elseif (preg_match('/^\/User\/(\d+)$/', $endpoint, $matches)) {  // Verifica si en endpoint termina en un numero.
            $id = $matches[1];                                              // hagara el numero
            $Usuario = $User_obj->GetUserbyID($id);                            // llama al metodo indicado
            echo json_encode($Usuario);
        } elseif (preg_match('/^\/User\/([a-zA-Z0-9_]+)$/', $endpoint, $matches)) {  // verifica si el enpoint tiene mayusculas,minusculas,numeros o "_" (el nombre de usuario)
            $name = $matches[1];                                              // hagara el nombre
            $Usuario_n = $User_obj->GetUserbyName($name);                         // llama al metodo indicado
            echo json_encode($Usuario_n);
        } elseif ($endpoint == '/Perfiles') {
            $Perfiles = $Perfil_obj->getperfils();
            echo json_encode($Perfiles);
        } elseif (preg_match('/^\/Perfil\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];                                              // hagara el numero
            $Perfil = $Perfil_obj->GetPerfilByUserID($id);                            // llama al metodo indicado
            echo json_encode($Perfil);
        }elseif (preg_match('/^\/Seguidos\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];                                              // hagara el numero
            $Seguidos = $Perfil_obj->GetSeguidos($id);                            // llama al metodo indicado
            echo json_encode($Seguidos);
        } elseif (preg_match('/^\/Seguidores\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];                                              // hagara el numero
            $Seguidores = $Perfil_obj->GetSeguidores($id);                            // llama al metodo indicado
            echo json_encode($Seguidores);
        }  else {                                                       // si no encuentra el endpoint(esta vacio o no es uno de los anteriores), da error.
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);  // manda la solitud al curl para pedirle los parametros                                   
        if ($endpoint == '/Usuario') {
            if (Validar_Data_User($data)) {
                $Result = $User_obj->AddUser($data);                  // llama al metodo y resultado user  
               $Perfil_obj->AddPerfil($Result['last_ID']);
                echo json_encode(['success' => $Result["resultr"]]); // guarda true o false, depende si se logro la incercion
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacio o mal formado']);
            }
        } elseif ($endpoint == '/User/Verify') {                  // verifica si el usuario y la contraseña son correctas.
            $Resul = $User_obj->VerifyUser($data);
            if ($Resul) {
                $Usuario = $User_obj->GetUserbyName($data['UserName']);  // Obtiene los datos del usuario usando el nombre de usuario
                echo json_encode([
                    'success' => $Resul,
                    'ID_usuario' => $Usuario['ID_usuario'] ]);   // Devuelve el ID del usuario junto con el éxito
            } else {
                echo json_encode(['success' => $Resul]);
            }
        }elseif ($endpoint == '/Perfil') {
            if (Validar_Data_Perfil($data)) {
                $Result = $Perfil_obj->ModifyPerfil($data);               
                echo json_encode(['success' => $Result]);     
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacio o mal formado']);
            }
        }elseif($endpoint == '/Seguidor'){
            $Result = $Perfil_obj->AddSeguidor($data);
            echo json_encode(['success' => $Result]);
        }else {                                                                 // si los atributos estan mal o no existen, da error
            http_response_code(400);
            echo json_encode(['error' => 'JSON vacío o mal formado']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($endpoint == 'User') {
            $Resul = $User_obj->DeleteUser($data);
            echo json_encode(['success' => $Resul]);
        } elseif($endpoint == '/seguidor'){
            $Result = $Perfil_obj->DeleteSeguidor($data);
            echo json_encode(['success' => $Result]);
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

function Validar_Data_Perfil ($data){
 if (empty($data)) {
    return false;
} elseif (
    !isset($data['User_ID']) || empty($data['User_ID']) ||  
    !isset($data['profile_picture']) || empty($data['profile_picture']) ||  
    !isset($data['bio']) || empty($data['bio'])  
) {
    return false;
} else {
    return true;
}

}


