<?php
require_once "Data_Base.php";

function GetPosts()
{
    global $conex;
    $query = "SELECT * FROM Post";
    $result = mysqli_query($conex, $query);
    $Posts = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $Posts[] = $row;
    }
    return $Posts;
}

function GetPostByID($id)
{
    global $conex;
    $query = "SELECT * FROM Post WHERE ID_post = ?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("i", $id); // "i" indica que $id es un entero
    $stmt->execute();
    $result = $stmt->get_result();
    $Post = $result->fetch_assoc();
    $stmt->close();
    return $Post;
}

function DeletePost($id)
{
    global $conex;
    $query = "DELETE FROM Post WHERE ID_post = ?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("i", $id['Id']); // "i" indica que $id es un entero
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function AddPost($data)
{
    global $conex;
    $query = "INSERT INTO Post (Title, Caption, Date_crea) VALUES (?, ?, ?)";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("sss", $data['Title'], $data['Caption'], $data['Date_crea']); // "sss" indica que los tres parámetros son cadenas
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function AddMulti($data)
{
    global $conex;
    $query = "INSERT INTO multi (Num , ID_post, src) VALUES (?, ?, ?)";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("iis", $data['num'], $data['Id'], $data['src']); // "iis" indica dos enteros y una cadena
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function GetMulyiByID($id)
{
    global $conex;
    $query = 'SELECT * FROM multi WHERE ID_post = ?';
    $stmt = $conex->prepare($query);
    $stmt->bind_param("i", $id); // "i" indica que $id es un entero
    $stmt->execute();
    $result = $stmt->get_result();
    $Post = $result->fetch_assoc();
    $stmt->close();
    return $Post;
}


function GetEvents()
{
    global $conex;
    $query = "SELECT * FROM Evento";
    $result = mysqli_query($conex, $query); // ejecuta la consulta 
    $Events = [];                                // crea un array
    while ($row = mysqli_fetch_assoc($result)) { // Recorre los resultados y los añade al array
        $Events[] = $row;
    }
    return $Events;                              // retorna el array
}

function GetEventByID($Id)
{
    global $conex;
    $query = "SELECT * FROM Evento WHERE ID_event = ?";
    $stmt = $conex->prepare($query);     //prepara la consulta
    $stmt->bind_param("i", $Id); // "i" indica que $Id es un entero
    $stmt->execute();
    $result = $stmt->get_result(); // Obtiene el resultado
    $Event = $result->fetch_assoc();
    $stmt->close();            // Cierra la consulta preparada
    return $Event;
}

function DeleteEvent($data)
{
    global $conex;
    $query = "DELETE FROM Evento WHERE ID_event = ?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("i", $data['Id']); // "i" indica que $id es un entero
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function AddEvent($data)
{
    global $conex;
    $ID_post = $data['ID_post'];
    $Loc_x = $data['Loc_x'];
    $Loc_y = $data['Loc_y'];
    $fecha_c = $data['Fecha'];
    $Start = $data['Start'];
    $Fin = $data['Fin'];
    $query = "INSERT INTO Evento (ID_post, Loc_x, Loc_y, Date_crea, Date_eve, Date_end) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("iiisss", $ID_post, $Loc_x, $Loc_y, $fecha_c, $Start, $Fin); // "iiisss" indica dos enteros y tres cadenas
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
