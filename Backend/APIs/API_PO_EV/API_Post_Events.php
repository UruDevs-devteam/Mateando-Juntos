<?php
require_once "../Config.php";
require_once "Post.php";
require_once "Event.php";

$Post_obj = new Post($conex); // creamos un objeto Post y le damos la conexion a la BD
$Event_obj = new Event($conex);// creamos un objeto Evento y le damos la conexion a la BD

$method = $_SERVER['REQUEST_METHOD']; // el metodo http que recibe, default es GET
$endpoint = $_SERVER['PATH_INFO'];    // la URL, pero toma la parte final, lo que no sea ruta
header('Content-Type: application/json'); // para que  la pagina sepa que se esta usando json

switch ($method) {
    case 'GET': //select
        if ($endpoint == '/Posts') {     // si el enpdoint es "/Posts"
            $Posts = $Post_obj->GetPosts();  // llama el metodo getposts y guarda los post en una variable
            echo json_encode($Posts);         // imprime los post codificados en json
        } elseif (preg_match('/^\/Post\/(\d+)$/', $endpoint, $matches)) {  // Verifica si en endpoint termina en un numero.
            $id = $matches[1];                                              // hagara el numero
            $Post = $Post_obj->GetPostByID($id);                            // llama al metodo indicado
            echo json_encode($Post);            //imprime el post codificado en json
        } elseif ($endpoint == '/Events') {
            $Evento = $Event_obj->GetEvents();
            echo json_encode($Evento);
        } elseif (preg_match('/^\/Event\/(\d+)$/', $endpoint, $matches)) {
            $id = $matches[1];
            $Event = $Event_obj->GetEventByID($id);
            echo json_encode($Event);
        } elseif ((preg_match('/^\/Multi\/(\d+)$/', $endpoint, $matches))) {
            $id = $matches[1];
            $mul = $Post_obj->GetMulyiByID($id);
            echo json_encode($mul);
        } elseif ((preg_match('/^\/Like\/(\d+)$/', $endpoint, $matches))) {
            $id = $matches[1];
            $likes = $Post_obj->GetLikesbypost($id);
            echo json_encode($likes);
        } elseif (preg_match('/^\/CheckLike\/(\d+)\/(\d+)$/', $endpoint, $matches)) { // Nuevo endpoint para verificar el like de un usuario en un post
            $id_user = $matches[1];
            $id_post = $matches[2];
            $hasLiked = $Post_obj->GetkUserLike($id_user, $id_post); // Llama al método que verifica si existe el like
            echo json_encode(['hasLiked' => $hasLiked]); // Devuelve si el usuario ha dado like o no

        } elseif (preg_match('/^\/Coments\/(\d+)$/', $endpoint, $matches)) {
            $id_post = $matches[1];
            $Comentarios = $Post_obj->GetComentariosByPost($id_post);  // Obtener comentarios por ID de post
            echo json_encode($Comentarios);

        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no valido']);
        }
        break;

    case 'POST': // insert
        $data = json_decode(file_get_contents('php://input'), true);  // Obtiene los parámetros del cuerpo de la solicitud

        if ($endpoint == "/Coment") {
            if (Valid_Data_Comentario($data)) { // Valida que el comentario tenga datos correctos
                $Resul = $Post_obj->AddComentario($data);  // Llama a la función para crear el comentario
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacío o mal formado']);
            }

        } elseif ($endpoint == '/Post') { // Endpoint para añadir un nuevo post
            if (Valid_Data_Post($data)) {
                $Resul = $Post_obj->AddPost($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacío o mal formado']);
            }
        } elseif ($endpoint == '/Multi') { // Endpoint para añadir multimedia
            if (Valid_Data_Multi($data)) {
                $Resul = $Post_obj->AddMulti($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacío o mal formado']);
            }
        } elseif ($endpoint == '/Event') { // Endpoint para añadir eventos
            if (Valid_Data_Event($data)) {
                $Resul = $Event_obj->AddEvent($data);
                echo json_encode($Resul);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacío o mal formado']);
            }
        } elseif ($endpoint == '/like') { // Endpoint para añadir un like
            if (valid_like($data)) {
                $Resul = $Post_obj->AddLike($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacío o mal formado']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no válido']);
        }
        break;

    case 'DELETE': // delete
        $data = json_decode(file_get_contents('php://input'), true); // Obtiene los parámetros del cuerpo de la solicitud

        if (preg_match('/^\/Comentario\/(\d+)$/', $endpoint, $matches)) {
            $id_comentario = $matches[1]; // Obtiene el ID del comentario desde la URL
            $Resul = $Post_obj->DeleteComentario($id_comentario); // Llama a la función para eliminar el comentario
            echo json_encode(['success' => $Resul]);
        } elseif ($endpoint == '/Post') {
            if (Valid_ID($data)) {
                $Resul = $Post_obj->DeletePost($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacío o mal formado']);
            }
        } elseif ($endpoint == '/Event') {
            if (Valid_ID($data)) {
                $Resul = $Event_obj->DeleteEvent($data);
                echo json_encode(['success' => $Resul]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'JSON vacío o mal formado']);
            }
        } elseif ($endpoint == '/like') { // Para eliminar el like
            $Resul = $Post_obj->DeleteLike($data);
            echo json_encode(['success' => $Resul]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint no válido']);
        }
        break;
}

/////////////////////////////////////////////////////// metodos de validacion ///////////////////////////////////////////////////////////
function Valid_Data_Post($data)
{
    if (empty($data)) { // Verificar que el array de datos no esté vacío
        return false;
    } elseif (                      // verifica que cada parametro exista y no este vacio.
        !isset($data['Titulo']) || empty($data['Titulo']) ||
        !isset($data['Descripcion']) || empty($data['Descripcion']) ||
        !isset($data['ID_usuario']) || empty($data['ID_usuario'])
    ) {
        return false;
    } else {
        return true;
    }
}
function Valid_Data_Event($data)
{

    return true;

}

function Valid_Data_Multi($data)
{

    return true;

}

function Valid_ID($data)
{
    if (empty($data)) {
        return false;
    } elseif (
        !isset($data['Id']) || empty($data['Id'])
    ) {
        return false;
    } else {
        return true;
    }
}

function valid_like($data)
{

    return true;

}

function Valid_Data_Comentario($data)
{
    if (empty($data)) {
        return false;
    } elseif (
        !isset($data['ID_usuario']) || empty($data['ID_usuario']) ||
        !isset($data['ID_post']) || empty($data['ID_post']) ||
        !isset($data['Contenido']) || empty($data['Contenido'])
    ) {
        return false;
    } else {
        return true;
    }
}
