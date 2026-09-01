CREATE DATABASE IF NOT EXISTS DevvoBD CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE DevvoBD;

CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(100),
    senha VARCHAR(255) NOT NULL,
    tipo_acesso ENUM('admin', 'aluno') NOT NULL DEFAULT 'aluno',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

CREATE TABLE Itens (
    id_item INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    id_usuario_cadastro INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    foto VARCHAR(255),
    local_encontrado VARCHAR(100) NOT NULL,
    local_armazenado VARCHAR(100),
    status ENUM('disponivel', 'entregue', 'doado') NOT NULL DEFAULT 'disponivel',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria),
    FOREIGN KEY (id_usuario_cadastro) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Solicitacoes (
    id_solicitacao INT AUTO_INCREMENT PRIMARY KEY,
    id_item INT NOT NULL,
    id_aluno INT NOT NULL,
    mensagem TEXT NOT NULL,
    status_analise ENUM('pendente', 'aprovada', 'recusada') NOT NULL DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_item) REFERENCES Itens(id_item),
    FOREIGN KEY (id_aluno) REFERENCES Usuarios(id_usuario)
);

INSERT INTO Categorias (nome) VALUES
('Roupas'),
('Eletronicos'),
('Materiais'),
('Documentos'),
('Outros');

-- Admin padrao. Senha: devvo@admin
-- Troque a senha apos o primeiro acesso.
INSERT INTO Usuarios (nome, matricula, email, senha, tipo_acesso) VALUES
('Administrador', 'ADMIN001', 'admin@sesi.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
INSERT INTO Usuarios (nome, matricula, email, senha, tipo_acesso) VALUES
('Administrador', 'ADMIN001', 'admin@sesi.com', 'password', 'admin');
UPDATE Usuarios SET senha = 'password' WHERE matricula = 'ADMIN001';