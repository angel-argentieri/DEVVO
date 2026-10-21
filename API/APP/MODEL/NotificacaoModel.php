<?php
class NotificacaoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function criar($id_usuario, $titulo, $mensagem) {
        $stmt = $this->db->prepare("
            INSERT INTO Notificacoes (id_usuario, titulo, mensagem)
            VALUES (:id_usuario, :titulo, :mensagem)
        ");
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindValue(':titulo',     $titulo);
        $stmt->bindValue(':mensagem',   $mensagem);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function buscarPorUsuario($id_usuario) {
        $stmt = $this->db->prepare("
            SELECT * FROM Notificacoes 
            WHERE id_usuario = :id_usuario 
            ORDER BY criado_em DESC 
            LIMIT 30
        ");
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function marcarComoLida($id_notificacao) {
        $stmt = $this->db->prepare("UPDATE Notificacoes SET lida = 1 WHERE id_notificacao = :id");
        $stmt->bindValue(':id', $id_notificacao, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function marcarTodasComoLidas($id_usuario) {
        $stmt = $this->db->prepare("UPDATE Notificacoes SET lida = 1 WHERE id_usuario = :id_usuario");
        $stmt->bindValue(':id_usuario', $id_usuario, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
