<?php
class CategoriaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarTodas() {
        $stmt = $this->db->query("SELECT * FROM Categorias ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
