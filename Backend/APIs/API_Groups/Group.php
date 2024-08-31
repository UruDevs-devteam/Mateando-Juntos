<?php

class Group {

private $conex;

public  function __construct($cone){
    $this->conex = $cone;  
}
public function getGroups(){
    $query = "SELECT * FROM Groups";
    $result = mysqli_query($this->conex, $query);
    $Groups = [];
    while ($row = mysqli_fetch_assoc($result)) { 
        $Groups[] = $row;
    }
    return $Groups;                            

}
public function getGropupByID($ID){
    $query = "SELECT * FROM Groups WHERE Group_ID = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();
    $stmt->close();
    return $group;

}
public function getGropupByName($name){
    $query = "SELECT * FROM Groups WHERE Group_name = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $group = $result->fetch_assoc();
    $stmt->close();
    return $group;

}
public function GetUsers($ID){
    $query = "SELECT ID_User FROM Pertenece WHERE ID_group = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $ID);
    $stmt->execute();
    $result = $stmt->get_result(); 
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row['ID_User']; 
    }
    $stmt->close();
    return $users; 
}
public function DeleteGroup($data)
{
    $query = "DELETE FROM Groups WHERE ID_group = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $data['Id']); 
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
public function AddGroup($data){
    
    $Group_name = $data['Group_name'];
    $Photo = $data['Photo'];
    $Descrip = $data['Descrip']; 
    $Creation_date = $data['Creation_date'];
    $query = "INSERT INTO Goups (Group_name, Photo, Descrip, Creation_date) VALUES (?, ?, ?, ?)";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ssss", $Group_name, $Photo , $Descrip, $Creation_date);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
     
}



}
