<?php
class SolicitacaoModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function criar($id_item, $id_aluno, $mensagem) {
        $stmt = $this->db->prepare("
            INSERT INTO Solicitacoes (id_item, id_aluno, mensagem)
            VALUES (:id_item, :id_aluno, :mensagem)
        ");
        $stmt->bindValue(':id_item',   $id_item,  PDO::PARAM_INT);
        $stmt->bindValue(':id_aluno',  $id_aluno, PDO::PARAM_INT);
        $stmt->bindValue(':mensagem',  $mensagem);

        if ($stmt->execute()) return $this->db->lastInsertId();
        return false;
    }

    public function buscarTodas() {
        $stmt = $this->db->query("
            SELECT s.*, i.titulo AS item_titulo, u.nome AS aluno_nome, u.matricula
            FROM Solicitacoes s
            JOIN Itens i ON s.id_item = i.id_item
            JOIN Usuarios u ON s.id_aluno = u.id_usuario
            ORDER BY s.criado_em DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE Solicitacoes SET status_analise = :status WHERE id_solicitacao = :id");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id',     $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function contarPendentes() {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM Solicitacoes WHERE status_analise = 'pendente'");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
