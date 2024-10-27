<?php
class Comentario {
    private $conex;

    public function __construct($db) {
        $this->conex = $db;
    }

    // Obtener comentarios por ID de post
    public function GetComentariosByPost($id_post) {
        $sql = "SELECT * FROM Comentarios WHERE ID_post = ?";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("i", $id_post);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Añadir un comentario
    public function AddComentario($data) {
        $sql = "INSERT INTO Comentarios (Contenido, ID_perfil, ID_post) VALUES (?, ?, ?)";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("sii", $data['Contenido'], $data['ID_perfil'], $data['ID_post']);
        return $stmt->execute();
    }

    // Eliminar un comentario
    public function DeleteComentario($id_comentario) {
        $sql = "DELETE FROM Comentarios WHERE ID_comentario = ?";
        $stmt = $this->conex->prepare($sql);
        $stmt->bind_param("i", $id_comentario);
        return $stmt->execute();
    }
}