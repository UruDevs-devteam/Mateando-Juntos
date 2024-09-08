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
        $query = "SELECT p.ID_post, p.Titulo, p.Descripcion, p.Fecha_creacion, u.Nombre_usuario
                  FROM Post p
                  JOIN Usuario u ON p.ID_usuario = u.ID_usuario";
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
        $query = "INSERT INTO Post (Titulo, Descripcion, ID_usuario) VALUES (?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("ssi", $data['Titulo'], $data['Descripcion'], $data['ID_usuario']); // "sss" indica que los tres parámetros son cadenas
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function AddMulti($data)
    {
        $query = "INSERT INTO Post_multimedia (Numero_mul , Src_mul, ID_post) VALUES (?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("isi", $data['num'], $data['src'], $data['Id']); // "iis" indica dos enteros y una cadena
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function GetMulyiByID($id)
    {
        $query = 'SELECT * FROM Post_multimedia WHERE ID_post = ?';
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("i", $id); // "i" indica que $id es un entero
        $stmt->execute();
        $result = $stmt->get_result();
        $Post = $result->fetch_assoc();
        $stmt->close();
        return $Post;
    }
}