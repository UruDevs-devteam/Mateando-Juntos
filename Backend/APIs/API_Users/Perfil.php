<?php

class Perfil
{

private $conex ;

public function __construct($conn)
{
    $this->conex = $conn;   // el construnctor, siempre que se cre este objeto se debera pasar la conexion
}

public function getperfils(){
    $query = "SELECT * FROM Profiles";
    $result = mysqli_query($this->conex, $query); // ejecuta la consulta 
    $perfiles = [];                                // crea un array
    while ($row = mysqli_fetch_assoc($result)) { // Recorre los resultados y los añade al array
        $perfiles[] = $row;
    }
    return $perfiles;                              // retorna el array
}
function GetPerfilByUserID($User_ID){
    $query = "SELECT * FROM Users WHERE User_ID = ?";// crea la consulta 
    $stmt = $this->conex->prepare($query);     //prepara la consulta
    $stmt->bind_param("i", $User_ID); // "i" indica que $User_ID es un entero
    $stmt->execute();
    $result = $stmt->get_result(); // Obtiene el resultado
    $perfil = $result->fetch_assoc();
    $stmt->close();            // Cierra la consulta preparada
    return $perfil;
    
}
function AddPerfil($User_ID){
    $query = "INSERT INTO Profiles (User_ID) VALUES ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $User_ID); // "i" indica un entero
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
function DeletPperfil($User_ID){
    $query = "DELETE FROM Users WHERE User_ID = ?"; //crea la consulta
    $stmt = $this->conex->prepare($query);     //prepara la consulta
    $stmt->bind_param("i", $User_ID); // "i" indica que $User_ID es un entero
    $stmt->execute();
    $result = $stmt->get_result(); // Obtiene el resultado
    $stmt->close();            // Cierra la consulta preparada
    return $result;            // retorna si la consulta fue exitosa
}
Function ModifyPerfil($data){
    $User_ID = $data['User_ID'];
    $img = $data['img'];
    $Tema = $data['Tema'];
    $Biografia = $data['Biografia'];
    $Privacidad = $data['Privacidad'];
    $query = "UPDATE Users SET profile_picture = ?, color = ?, name = ?, age = ?, bio = ? WHERE User_ID = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ssssi", $img, $Tema, $Biografia, $Privacidad, $User_ID);
}
}
