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
        $query = "SELECT * FROM Evento";             
        $result = mysqli_query($this->conex, $query); // ejecuta la consulta 
        $Events = [];                                // crea un array
        while ($row = mysqli_fetch_assoc($result)) { // Recorre los resultados y los añade al array
            $Events[] = $row;
        }
        return $Events;                              // retorna el array
    }

    public function GetEventByID($Id)
    {
        $query = "SELECT * FROM Evento WHERE ID_event = ?";
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
        $query = "DELETE FROM Evento WHERE ID_event = ?";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("i", $data['Id']); // "i" indica que $id es un entero
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function AddEvent($data)
    {
        $ID_post = $data['ID_post'];
        $Loc_x = $data['Loc_x'];
        $Loc_y = $data['Loc_y'];
        $fecha_c = $data['Fecha'];
        $Start = $data['Start'];
        $Fin = $data['Fin'];
        $query = "INSERT INTO Evento (ID_post, Loc_x, Loc_y, Date_crea, Date_eve, Date_end) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("iiisss", $ID_post, $Loc_x, $Loc_y, $fecha_c, $Start, $Fin); // "iiisss" indica dos enteros y tres cadenas
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}