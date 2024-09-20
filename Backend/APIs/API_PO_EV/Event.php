<?php
class Event
{
    private $conex;  //atributo de la clase, la conexion a la BD

    public function __construct($conn)
    {
        $this->conex = $conn;   // el construnctor, siempre que se cre este objeto se debera pasar la conexion
    }

    public function GetEvents()
    {
        $query = " SELECT e.*, 
                u.Nombre_usuario FROM Evento e JOIN 
                Usuario u ON 
                e.ID_usuario = u.ID_usuario
        ";             
        $result = mysqli_query($this->conex, $query);
        $Events = [];                                
        while ($row = mysqli_fetch_assoc($result)) {
            $Events[] = $row;
        }
        return $Events;
    }
    

    public function GetEventByID($Id)
    {
        $query = "SELECT * FROM Evento WHERE ID_evento = ?";
        $stmt = $this->conex->prepare($query);     //prepara la consulta
        $stmt->bind_param("i", $Id); // "i" indica que $Id es un entero
        $stmt->execute();           
        $result = $stmt->get_result(); // Obtiene el resultado
        $Event = $result->fetch_assoc();
        $stmt->close();            // Cierra la consulta preparada
        return $Event;
    }

    public function DeleteEvent($data)
    {
        $query = "DELETE FROM Evento WHERE ID_evento = ?";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("i", $data['Id']); // "i" indica que $id es un entero
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function AddEvent($data)
    {
        $ID_usuario = $data['ID_usuario'];
        $Titulo = $data['Titulo'];
        $Descripcion = $data['Descripcion'];
        $Fecha_encuentro = $data['Fecha_encuentro']; 
        $Hora_inicio = $data['Hora_inicio']; 
        $Hora_fin = $data['Hora_fin']; 
        $Lugar = $data['Lugar'];

        $query = "INSERT INTO Evento (ID_usuario, Titulo, Descripcion, Fecha_encuentro, Hora_inicio, Hora_fin, Lugar) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("issssss", $ID_usuario, $Titulo, $Descripcion, $Fecha_encuentro, $Hora_inicio, $Hora_fin, $Lugar); // "issssss" indica las columnas
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
}