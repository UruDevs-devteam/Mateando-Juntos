<?php

class Perfil
{

private $conex ;

public function __construct($conn)
{
    $this->conex = $conn;   // el construnctor, siempre que se cre este objeto se debera pasar la conexion
}

public function getperfils(){
    $query = "SELECT * FROM Perfil_usuario";
    $result = mysqli_query($this->conex, $query); // ejecuta la consulta 
    $perfiles = [];                                // crea un array
    while ($row = mysqli_fetch_assoc($result)) { // Recorre los resultados y los añade al array
        $perfiles[] = $row;
    }
    return $perfiles;                              // retorna el array
}
function GetPerfilByUserID($User_ID) {
    $query = "SELECT * FROM Perfil_usuario WHERE ID_usuario = ?"; // crea la consulta
    $stmt = $this->conex->prepare($query); // prepara la consulta
    $stmt->bind_param("i", $User_ID); // "i" indica que $User_ID es un entero
    $stmt->execute();
    $result = $stmt->get_result(); // Obtiene el resultado
    $perfil = $result->fetch_assoc();
    // Verifica si hay una foto de perfil y la codifica en base64
    if ($perfil && !empty($perfil['Foto_perfil'])) {
        $perfil['Foto_perfil'] = base64_encode($perfil['Foto_perfil']);
    }
    $stmt->close(); // Cierra la consulta preparada
    return $perfil;
}

function AddPerfil($User_ID){
    $query = "INSERT INTO Perfil_usuario (ID_usuario) VALUES (?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $User_ID); // "i" indica un entero
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
function DeletPperfil($User_ID){
    $query = "DELETE FROM Perfil_usuario WHERE ID_usuario = ?"; //crea la consulta
    $stmt = $this->conex->prepare($query);     //prepara la consulta
    $stmt->bind_param("i", $User_ID); // "i" indica que $User_ID es un entero
    $stmt->execute();
    $result = $stmt->get_result(); // Obtiene el resultado
    $stmt->close();            // Cierra la consulta preparada
    return $result;            // retorna si la consulta fue exitosa
}
function ModifyPerfil($data) {
    $User_ID = $data['User_ID'];
    $imgContent = $data['profile_picture']; 
    $Biografia = $data['bio'];
    $query = "UPDATE Perfil_usuario SET Foto_perfil = ?, Biografia = ? WHERE ID_usuario = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ssi", $imgContent, $Biografia, $User_ID);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


}
