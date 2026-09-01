<?php
class ItemModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarTodos($categoria = null, $busca = null) {
        $sql = "SELECT i.*, c.nome AS categoria
                FROM Itens i
                JOIN Categorias c ON i.id_categoria = c.id_categoria
                WHERE i.status = 'disponivel'";

        if ($categoria) $sql .= " AND i.id_categoria = :categoria";
        if ($busca)     $sql .= " AND (i.titulo LIKE :busca OR i.local_encontrado LIKE :busca2)";

        $sql .= " ORDER BY i.criado_em DESC";

        $stmt = $this->db->prepare($sql);
        if ($categoria) $stmt->bindValue(':categoria', $categoria, PDO::PARAM_INT);
        if ($busca) {
            $stmt->bindValue(':busca',  '%' . $busca . '%');
            $stmt->bindValue(':busca2', '%' . $busca . '%');
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $stmt = $this->db->prepare("
            SELECT i.*, c.nome AS categoria
            FROM Itens i
            JOIN Categorias c ON i.id_categoria = c.id_categoria
            WHERE i.id_item = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($id_categoria, $id_usuario, $titulo, $descricao, $foto, $local_encontrado, $local_armazenado) {
        $stmt = $this->db->prepare("
            INSERT INTO Itens (id_categoria, id_usuario_cadastro, titulo, descricao, foto, local_encontrado, local_armazenado)
            VALUES (:id_categoria, :id_usuario, :titulo, :descricao, :foto, :local_encontrado, :local_armazenado)
        ");
        $stmt->bindValue(':id_categoria',     $id_categoria, PDO::PARAM_INT);
        $stmt->bindValue(':id_usuario',       $id_usuario,   PDO::PARAM_INT);
        $stmt->bindValue(':titulo',           $titulo);
        $stmt->bindValue(':descricao',        $descricao);
        $stmt->bindValue(':foto',             $foto);
        $stmt->bindValue(':local_encontrado', $local_encontrado);
        $stmt->bindValue(':local_armazenado', $local_armazenado);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function atualizarStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE Itens SET status = :status WHERE id_item = :id");
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id',     $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function buscarTodosAdmin() {
        $stmt = $this->db->query("
            SELECT i.*, c.nome AS categoria, u.nome AS cadastrado_por
            FROM Itens i
            JOIN Categorias c ON i.id_categoria = c.id_categoria
            JOIN Usuarios u ON i.id_usuario_cadastro = u.id_usuario
            ORDER BY i.criado_em DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorStatus($status) {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM Itens WHERE status = :status");
        $stmt->bindValue(':status', $status);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
