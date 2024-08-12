<?php
class User
{
    private $conex;  //atributo de la clase, la conexion a la BD

    public function __construct($conn)
    {
        $this->conex = $conn;   // el construnctor, siempre que se cre este objeto se debera pasar la conexion
    }

    public function GetUsers()
    {
        $query = "SELECT * FROM Users";
        $result = mysqli_query($this->conex, $query); // ejecuta la consulta 
        $Users = [];                                // crea un array
        while ($row = mysqli_fetch_assoc($result)) { // Recorre los resultados y los añade al array
            $Users[] = $row;
        }
        return $Users;                              // retorna el array
    }

    public function GetUserbyName($name)
    {
        $query = "SELECT * FROM Users WHERE User_name = ?";
        $stmt = $this->conex->prepare($query);     //prepara la consulta
        $stmt->bind_param("s", $name); // "i" indica que $Id es un entero
        $stmt->execute();
        $result = $stmt->get_result(); // Obtiene el resultado
        $user = $result->fetch_assoc();
        $stmt->close();            // Cierra la consulta preparada
        return $user;
    }
    public function GetUserbyID($Id)
    {
        $query = "SELECT * FROM Users WHERE ID_user = ?";
        $stmt = $this->conex->prepare($query);     //prepara la consulta
        $stmt->bind_param("s", $Id); // "i" indica que $Id es un entero
        $stmt->execute();
        $result = $stmt->get_result(); // Obtiene el resultado
        $user = $result->fetch_assoc();
        $stmt->close();            // Cierra la consulta preparada
        return $user;
    }


    public function DeleteUser($data)
    {
        $query = "DELETE FROM Users WHERE ID_user = ?";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("i", $data['Id']); // "i" indica que $id es un entero
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function AddUser($data)
    {

        $Full_name = $data['Full_name'];
        $User_name = $data['Username'];
        $Email = $data['Email'];
        $hashed_password = password_hash($data['Pass'], CRYPT_BLOWFISH);    // Encripta la contraseña
        $query = "INSERT INTO Users (Full_name, User_name, Email, Pass) VALUES (?, ?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("ssss", $Full_name, $User_name , $Email, $hashed_password); // "ssss" indica cuatro cadenas
        $result = [];
        $result["result"] = $stmt->execute();
        $result["last_ID"] = $this->conex->insert_id;                // Obtener la última ID insertada
        $stmt->close();
        return $result;
    }

    function VerifyUser($data)
{
    global $conex;
    $password = $data['Password'];
    $UserName = $data['UserName'];
    $query = "SELECT Pass FROM Users WHERE User_name = ?";// Consulta  para obtener la contraseña encriptada 
    $stmt = $conex->prepare($query);
    $stmt->bind_param("s", $UserName);
    $stmt->execute();
    $stmt->bind_result($hashed);
    $stmt->fetch();
    $verify_pass = password_verify($password, $hashed);
    $stmt->close();
    return $verify_pass;
}
}

