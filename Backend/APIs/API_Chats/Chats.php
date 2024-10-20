<?php
class Chats
{

    private $conex;

    public function __construct($cone)
    {
        $this->conex = $cone;
    }
    public function getAllChats($userId)
    {
        $query = "SELECT 
        Contacto, 
        U.Nombre_usuario, 
        M.Contenido AS UltimoMensaje, 
        M.Fecha_envio AS FechaUltimoMensaje
      FROM (SELECT 
          IF(M.ID_usuario_envia = ?, M.ID_usuario_recibe, M.ID_usuario_envia) AS Contacto, 
          MAX(M.Fecha_envio) AS UltimaFecha
        FROM Mensaje M
        WHERE M.ID_usuario_envia = ? OR M.ID_usuario_recibe = ?
        GROUP BY Contacto
      ) AS UltimosMensajes
      JOIN Mensaje M ON M.Fecha_envio = UltimosMensajes.UltimaFecha
      JOIN Usuario U ON U.ID_usuario = UltimosMensajes.Contacto
      ORDER BY M.Fecha_envio DESC";

        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("iii", $userId, $userId, $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $contacts = [];

        while ($row = $result->fetch_assoc()) {
            $contacts[] = [
                'ID_usuario' => $row['Contacto'],
                'Nombre_usuario' => $row['Nombre_usuario'],
                'UltimoMS' => $row['UltimoMensaje'],
                'Fecha' => $row['FechaUltimoMensaje']
            ];
        }

        return $contacts;
    }


    // Obtener mensajes entre dos usuarios
    public function getMessages($contacusertId,$userId )
    {
        $query = "SELECT * FROM Mensaje  WHERE (ID_usuario_envia = ? AND ID_usuario_recibe = ?)
              OR (ID_usuario_envia = ? AND ID_usuario_recibe = ?) 
              ORDER BY Fecha_envio ASC";

        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("iiii", $userId, $contacusertId, $contacusertId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'ID_mensaje' => $row['ID_mensaje'],
                'Contenido' => $row['Contenido'],
                'ID_usuario_envia' => $row['ID_usuario_envia'],
                'ID_usuario_recibe' => $row['ID_usuario_recibe'],
                'leeido' => $row['leeido'],
                'Fecha_envio' => $row['Fecha_envio']
            ];
        }

        $stmt->close();
        return $messages;
    }

    // Enviar un nuevo mensaje
    public function sendMessage($data)
    {
        $senderId = $data['senderId'];
        $receiverId = $data['receiverId'];
        $content = $data['contenido'];
        $query = "INSERT INTO Mensaje (Contenido, ID_usuario_envia, ID_usuario_recibe)   VALUES (?, ?, ?)";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("sii", $content, $senderId, $receiverId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Marcar mensajes como leídos
    public function markAsRead($data)
    {
        $contacusertId = $data['contacusertId'];
        $userId = $data['userId'];
        $query = "UPDATE Mensaje 
              SET leeido = true 
              WHERE ID_usuario_envia = ? AND ID_usuario_recibe = ? AND leeido = false";
        $stmt = $this->conex->prepare($query);
        $stmt->bind_param("ii", $contacusertId, $userId);
        if ($stmt->execute()) {
            $stmt->close();
            return ['status' => 'success', 'message' => 'Mensajes marcados como leídos.'];
        } else {
            $stmt->close();
            return ['status' => 'error', 'message' => 'Error al marcar los mensajes como leídos.'];
        }
    }

}