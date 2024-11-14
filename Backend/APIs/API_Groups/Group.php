<?php

class Group {

private $conex;

public  function __construct($cone){
    $this->conex = $cone;  
}
public function getGroups(){
    $query = "SELECT * FROM comunidad";
    $result = mysqli_query($this->conex, $query);
    $Groups = [];
    while ($row = mysqli_fetch_assoc($result)) { 
        $Groups[] = $row;
    }
    return $Groups;                            

}
public function getGroupByID($ID){
    $query = "SELECT * FROM comunidad WHERE ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();
    $stmt->close();
    return $group;
}
public function getGroupByName($name){
    $query = "SELECT * FROM comunidad WHERE Nombre_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();
    $stmt->close();
    return $group;
}
public function GetUsers($ID){
    $query = "SELECT COUNT(ID_usuario) AS total_users FROM pertenece WHERE ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); 
    $stmt->close();
    
    return $row['total_users'] ?? 0; 
}
public function DeleteGroup($data)
{
    $query = "DELETE FROM comunidad WHERE ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $data['Id']); 
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
public function AddGroup($data){
    
    $UsersUploads = "../../../UsersUploads/";
    $base64String = $data['profile_picture'];
    $fileName = uniqid() . '.jpg'; 
    $filePath = $UsersUploads . $fileName;

    if (file_put_contents($filePath, base64_decode($base64String)) !== false) {
    $Group_name = $data['communityName'];
    $Descrip = $data['communityDescription']; 
    $User_creator = $data['User_creator'];
    $query = "INSERT INTO comunidad (Nombre_comunidad, Descripcion, ID_usuario_creador, Url_fotocomunidad) VALUES (?, ?, ?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ssis", $Group_name, $Descrip , $User_creator, $fileName);
    $result = $stmt->execute();
    $stmt->close();
    return $result;

    }else{
        return false;
    }
     
}
public function AddUserToGroup($data) {
    $query = "INSERT INTO pertenece (ID_usuario, ID_comunidad) VALUES (?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $data['ID_usuario'], $data['ID_comunidad']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


public function DeleteUserFromGroup($data) {
    $query = "DELETE FROM pertenece WHERE ID_usuario = ? AND ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    
    if ($stmt === false) {
        throw new Exception("Error en la preparación de la consulta: " . $this->conex->error);
    }
    
    $bind_result = $stmt->bind_param("ii", $data['ID_usuario'], $data['ID_comunidad']);
    if ($bind_result === false) {
        throw new Exception("Error al vincular los parámetros: " . $this->conex->error);
    }
    
    $result = $stmt->execute();
    if ($result === false) {
        throw new Exception("Error al ejecutar la consulta: " . $this->conex->error);
    }
    
    $stmt->close();
    return $result;
}

public function AddPostToGroup($data) {
    $query = "INSERT INTO comunidad_post (ID_comunidad, ID_post) VALUES (?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $data['ID_comunidad'], $data['ID_post']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Agregar un evento a una comunidad
public function AddEventToGroup($data) {
    $query = "INSERT INTO comunidad_evento (ID_comunidad, ID_evento) VALUES (?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $data['ID_comunidad'], $data['ID_evento']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Obtener posts en una comunidad específica
public function GetPostsInGroup($ID_comunidad) {
    $query = "SELECT p.*, u.Nombre AS Nombre_usuario 
        FROM post p 
        INNER JOIN comunidad_post cp ON p.ID_post = cp.ID_post 
        INNER JOIN usuario u ON p.ID_usuario = u.ID_usuario 
        WHERE cp.ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $ID_comunidad);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $stmt->close();
    return $posts;
}

// Obtener eventos en una comunidad específica
public function GetEventsInGroup($ID_comunidad) {
    $query = "SELECT * FROM evento e INNER JOIN comunidad_evento ce ON e.ID_evento = ce.ID_evento WHERE ce.ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $ID_comunidad);
    $stmt->execute();
    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $stmt->close();
    return $events;
}

public function getCommunitiesByUserId($userId) {
    $query = "
        SELECT c.*
        FROM comunidad c
        INNER JOIN pertenece p ON c.ID_comunidad = p.ID_comunidad
        WHERE p.ID_usuario = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $communities = [];
    while ($row = $result->fetch_assoc()) {
        $communities[] = $row;
    }
    $stmt->close();
    return $communities;
}

public function getTopLikedPosts() {
    $query = "
 SELECT p.ID_post, p.ID_usuario, p.Titulo, p.Descripcion, p.Fecha_creacion, u.Nombre AS Nombre_usuario, COUNT(dm.ID_usuario) AS total_likes
FROM post p
LEFT JOIN dar_megusta dm ON p.ID_post = dm.ID_post
INNER JOIN usuario u ON p.ID_usuario = u.ID_usuario
GROUP BY p.ID_post
ORDER BY total_likes DESC
LIMIT 10;

    ";

    $result = mysqli_query($this->conex, $query);
    $posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
    return $posts;
}
public function CheckUserInCommunity($userID, $communityID) {
    $query = "SELECT COUNT(*) as count FROM pertenece WHERE ID_usuario = ? AND ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $userID, $communityID);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();
    return $data['count'] > 0; // Retorna true si el usuario pertenece a la comunidad
}

}
