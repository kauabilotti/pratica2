create database pratica2;
use pratica2;

CREATE TABLE Clientes (
    id_cliente INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL
);

CREATE TABLE Funcionarios (
    id_funcionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cargo VARCHAR(50) NOT NULL
);

CREATE TABLE Solicitações (
    id_solicitacao INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL,
    descricao TEXT NOT NULL,
    urgencia ENUM('baixa', 'média', 'alta') NOT NULL,
    status ENUM('pendente', 'em andamento', 'finalizada') DEFAULT 'pendente',
    data_abertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_funcionario INT NULL,
    FOREIGN KEY (id_cliente) REFERENCES Clientes(id_cliente),
    FOREIGN KEY (id_funcionario) REFERENCES Funcionarios(id_funcionario)
);
