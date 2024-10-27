<?php

class Group {

private $conex;

public  function __construct($cone){
    $this->conex = $cone;  
}
public function getGroups(){
    $query = "SELECT * FROM Comunidad";
    $result = mysqli_query($this->conex, $query);
    $Groups = [];
    while ($row = mysqli_fetch_assoc($result)) { 
        $Groups[] = $row;
    }
    return $Groups;                            

}
public function getGroupByID($ID){
    $query = "SELECT * FROM Comunidad WHERE ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();
    $stmt->close();
    return $group;
}
public function getGroupByName($name){
    $query = "SELECT * FROM Comunidad WHERE Nombre_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();
    $stmt->close();
    return $group;
}
public function GetUsers($ID){
    $query = "SELECT COUNT(ID_usuario) AS total_users FROM Pertenece WHERE ID_comunidad = ?";
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
    $query = "DELETE FROM Comunidad WHERE ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $data['Id']); 
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
public function AddGroup($data){
    
    $Group_name = $data['communityName'];
    $Photo = $data['profile_picture'];
    $Descrip = $data['communityDescription']; 
    $User_creator = $data['User_creator'];
    $query = "INSERT INTO Comunidad (Nombre_comunidad, Descripcion, ID_usuario_creador, Url_fotocomunidad) VALUES (?, ?, ?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ssis", $Group_name, $Descrip , $User_creator, $Photo);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
     
}
public function AddUserToGroup($data) {
    $query = "INSERT INTO Pertenece (ID_usuario, ID_comunidad) VALUES (?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $data['ID_usuario'], $data['ID_comunidad']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


public function DeleteUserFromGroup($data) {
    $query = "DELETE FROM Pertenece WHERE ID_usuario = ? AND ID_comunidad = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $data['ID_usuario'], $data['ID_comunidad']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

public function AddPostToGroup($data) {
    $query = "INSERT INTO Comunidad_Post (ID_comunidad, ID_post) VALUES (?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $data['ID_comunidad'], $data['ID_post']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Agregar un evento a una comunidad
public function AddEventToGroup($data) {
    $query = "INSERT INTO Comunidad_Evento (ID_comunidad, ID_evento) VALUES (?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $data['ID_comunidad'], $data['ID_evento']);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Obtener posts en una comunidad específica
public function GetPostsInGroup($ID_comunidad) {
    $query = "SELECT p.*, u.Nombre AS Nombre_usuario 
        FROM Post p 
        INNER JOIN Comunidad_Post cp ON p.ID_post = cp.ID_post 
        INNER JOIN Usuario u ON p.ID_usuario = u.ID_usuario 
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
    $query = "SELECT * FROM Evento e INNER JOIN Comunidad_Evento ce ON e.ID_evento = ce.ID_evento WHERE ce.ID_comunidad = ?";
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




}
