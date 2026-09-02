<?php
class ItemPerdidoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function criar($id_categoria, $id_aluno, $titulo, $descricao, $local_perdido) {
        $stmt = $this->db->prepare("
            INSERT INTO ItensPerdidos (id_categoria, id_aluno, titulo, descricao, local_perdido)
            VALUES (:id_categoria, :id_aluno, :titulo, :descricao, :local_perdido)
        ");
        $stmt->bindValue(':id_categoria',   $id_categoria, PDO::PARAM_INT);
        $stmt->bindValue(':id_aluno',       $id_aluno,     PDO::PARAM_INT);
        $stmt->bindValue(':titulo',         $titulo);
        $stmt->bindValue(':descricao',      $descricao);
        $stmt->bindValue(':local_perdido',  $local_perdido);

        if ($stmt->execute()) return $this->db->lastInsertId();
        return false;
    }

    public function buscarTodas() {
        $stmt = $this->db->query("
            SELECT p.*, c.nome AS categoria, u.nome AS aluno_nome, u.matricula
            FROM ItensPerdidos p
            JOIN Categorias c ON p.id_categoria = c.id_categoria
            JOIN Usuarios u ON p.id_aluno = u.id_usuario
            ORDER BY p.criado_em DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE ItensPerdidos SET status = :status WHERE id_perdido = :id");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id',     $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>