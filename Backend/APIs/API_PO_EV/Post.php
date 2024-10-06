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
        $query = "SELECT p.ID_post, p.Titulo, p.Descripcion, p.Fecha_creacion, u.Nombre_usuario, u.ID_usuario
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
    $query = "SELECT * FROM Post WHERE ID_usuario = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $id); // "i" indica que $id es un entero
    $stmt->execute();
    $result = $stmt->get_result();
    $Posts = [];
    while ($row = $result->fetch_assoc()) {
        $Posts[] = $row;
    }
    $stmt->close();
    return $Posts; // Devolver el array con todos los posts
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
        $last_ID = $this->conex->insert_id;                // Obtener la última ID insertada
        $stmt->close();
        return [
            "result" => $result,
            "postId" => $last_ID
        ];
    }

    public function AddMulti($data)
    {
        $query = "INSERT INTO Post_multimedia ( Src_mul, ID_post) VALUES (?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("si", $data['src'], $data['postId']); // "is" un enteros y una cadena
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    public function GetMulyiByID($id)
{
    $query = 'SELECT Src_mul FROM Post_multimedia WHERE ID_post = ?';
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $id); // "i" indica que $id es un entero
    $stmt->execute();
    $result = $stmt->get_result();
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = $row['Src_mul']; // Solo almacenar el contenido de la imagen
    }
    $stmt->close();
    return $images;
}
public function GetLikesbypost($id_post) {
    $query = "SELECT COUNT(*) as like_count FROM Dar_megusta WHERE ID_post = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $result = $stmt->get_result();
    $like_count = $result->fetch_assoc();
    $stmt->close();
    return $like_count['like_count']; // Devuelve el conteo de likes
}
public function GetkUserLike($id_user, $id_post) {
    $query = "SELECT * FROM Dar_megusta WHERE ID_usuario = ? AND ID_post = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $id_user, $id_post);
    $stmt->execute();
    $result = $stmt->get_result();
    $hasLiked = $result->num_rows > 0;
    $stmt->close();
    return $hasLiked;  // Devuelve true si existe el like, false si no
}
public function AddLike($data){
    // Asegúrate de que los datos sean enteros
    $postId = (int)$data['ID_post'];
    $ID_usuario = (int)$data['ID_usuario'];
    $query = "INSERT INTO Dar_megusta (ID_usuario, ID_post) VALUES (?, ?)";
    $stmt = $this->conex->prepare($query);
    if ($stmt === false) {
        error_log("Error en prepare: " . $this->conex->error);
        return false;
    }
    $stmt->bind_param("ii", $ID_usuario, $postId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

public function DeleteLike($data){
    $postId = (int)$data['ID_post'];
    $ID_usuario = (int)$data['ID_usuario'];
    $query = "DELETE FROM Dar_megusta WHERE  ID_usuario = ? AND ID_post = ?";
    $stmt = $this->conex->prepare($query);
    $stmt->bind_param("ii", $ID_usuario, $postId);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}



}