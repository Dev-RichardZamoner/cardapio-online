CREATE DATABASE IF NOT EXISTS cardapio_online;

USE cardapio_online;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    `rank` INT DEFAULT 1
);

CREATE TABLE IF NOT EXISTS ranks (
    rank_id INT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL
);

INSERT INTO ranks (rank_id, descricao) VALUES (1, 'Usuário'), (2, 'Moderador'), (3, 'Administrador');

SELECT * FROM usuarios;

CREATE TABLE IF NOT EXISTS itens_cardapio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    imagem_url VARCHAR(255),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);


ALTER TABLE usuarios ADD COLUMN nome VARCHAR(255);

CREATE TABLE restaurantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    endereco VARCHAR(255),
    telefone VARCHAR(20),
    email VARCHAR(255),
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    imagem_url VARCHAR(255),
    restaurante_id INT,
    FOREIGN KEY (restaurante_id) REFERENCES restaurantes(id)
);

SHOW COLUMNS FROM itens_cardapio LIKE 'usuario_id';

ALTER TABLE restaurantes
ADD COLUMN whatsapp VARCHAR(20),
ADD COLUMN instagram VARCHAR(255);

ALTER TABLE itens_cardapio
ADD COLUMN moeda VARCHAR(3);

-- Adiciona a tabela de notícias
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    data_publicacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
