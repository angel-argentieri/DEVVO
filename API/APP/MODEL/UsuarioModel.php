<?php
class UsuarioModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarPorMatricula($matricula) {
        $stmt = $this->db->prepare("SELECT * FROM Usuarios WHERE matricula = :matricula");
        $stmt->bindValue(':matricula', $matricula);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($nome, $matricula, $email, $senha) {
        $stmt = $this->db->prepare("INSERT INTO Usuarios (nome, matricula, email, senha, tipo_acesso) VALUES (:nome, :matricula, :email, :senha, 'aluno')");
        $stmt->bindValue(':nome',      $nome);
        $stmt->bindValue(':matricula', $matricula);
        $stmt->bindValue(':email',     $email);
        $stmt->bindValue(':senha',     $senha);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
}
?>
