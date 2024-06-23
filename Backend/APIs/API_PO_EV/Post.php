<?php
class Post
{
    private $conex;

    public function __construct($conn)
    {
        $this->conex = $conn;
    }

    public function GetPosts()
    {
        $query = "SELECT * FROM Post";
        $result = mysqli_query($this->conex, $query);
        $Posts = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $Posts[] = $row;
        }
        return $Posts;
    }

    public function GetPostByID($id)
    {
        $query = "SELECT * FROM Post WHERE ID_post = ?";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("i", $id); // "i" indica que $id es un entero
        $stmt->execute();
        $result = $stmt->get_result();
        $Post = $result->fetch_assoc();
        $stmt->close();
        return $Post;
    }

    public function DeletePost($id)
    {
        $query = "DELETE FROM Post WHERE ID_post = ?";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("i", $id['Id']); // "i" indica que $id es un entero
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function AddPost($data)
    {
        $query = "INSERT INTO Post (Title, Caption, Date_crea) VALUES (?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("sss", $data['Title'], $data['Caption'], $data['Date_crea']); // "sss" indica que los tres parámetros son cadenas
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function AddMulti($data)
    {
        $query = "INSERT INTO multi (Num , ID_post, src) VALUES (?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("iis", $data['num'], $data['Id'], $data['src']); // "iis" indica dos enteros y una cadena
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function GetMulyiByID($id){
        $query = 'SELECT * FROM multi WHERE ID_post = ?';
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("i", $id); // "i" indica que $id es un entero
        $stmt->execute();
        $result = $stmt->get_result();
        $Post = $result->fetch_assoc();
        $stmt->close();
        return $Post;      }
}