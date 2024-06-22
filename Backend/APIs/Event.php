<?php
class Event{
    private $conex;
  public function __construct($conn)
  {
    $this->conex = $conn;
  }
  Public function GetEvents(){
    $query = "SELECT * FROM Evento";
    $result = mysqli_query($this->conex, $query);
    $Events = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $Events[] = $row;
    }
    return $Events;
  }
  public function GetEventByID($Id){
    $query = "SELECT * FROM Evento WHERE ID_evento = $Id";
    $result = mysqli_query($this->conex, $query);
    $Event = mysqli_fetch_assoc($result);
    return $Event;
  }
  public function DeleteEvent($data){
    $ID = $data['Id'];
    $query ="DELETE FROM Post WHERE ID_evento = $ID";
    $result = mysqli_query($this->conex, $query);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
  public function AddEvent($data){
    $ID_post = $data['ID_post'];
    $Loc_x = $data['Loc_x'];
    $Loc_y = $data['Loc_y'];
    $fecha_c= $data['Feacha'];
    $Inicio = $data['Inicio'];
    $Fin = $data['Fin'];
    $query = "INSERT INTO Post_img VALUES (NULL,'$ID_post','$$Loc_x','$Loc_y','$fecha_c','$Inicio','$Fin')";
    $result = mysqli_query($this->conex, $query);
    if ($result) {
      return true;
    } else {
      return false;
    }
  }
}