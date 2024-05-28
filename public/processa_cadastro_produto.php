<?php
session_start();

// Caminho para o diretório que você deseja alterar as permissões
$directoryPath = '../assents/images/';

// Verifique se o diretório existe
if (!file_exists($directoryPath)) {
    // Tente criar o diretório se ele não existir
    if (!mkdir($directoryPath, 0777, true)) {
        die('Falha ao criar diretórios...');
    }
}

// Altere as permissões do diretório
if (!chmod($directoryPath, 0777)) {
    echo 'Não foi possível alterar as permissões do diretório';
} else {
    echo 'Permissões do diretório alteradas com sucesso';
}

// Verifique se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclua o arquivo de configuração do banco de dados
require_once '../assents/src/config/database.php';

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $moeda = $_POST['moeda'] ?? '';
    $imagem = $_FILES['imagem'] ?? null;
    $usuario_id = $_SESSION['id'] ?? null; // Certifique-se de que esta variável está sendo definida corretamente

    // Upload da imagem
    if ($imagem && $imagem['tmp_name']) {
        $nomeImagem = 'uploads/' . basename($imagem['name']);
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true); // Cria o diretório se ele não existir
        }
        move_uploaded_file($imagem['tmp_name'], $nomeImagem);
    } else {
        $nomeImagem = ''; // Defina $nomeImagem como uma string vazia se não houver upload
    }

    // Prepare a consulta SQL
    $sql = "INSERT INTO itens_cardapio (nome, descricao, preco, moeda, imagem_url, usuario_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Execute a consulta
    if ($stmt->execute([$nome, $descricao, $preco, $moeda, $nomeImagem, $usuario_id])) {
        $_SESSION['success_message'] = 'Produto cadastrado com sucesso!';
        header("location: inicio.php");
        exit;
    } else {
        echo "Algo deu errado. Por favor, tente novamente.";
    }
}

// Feche a conexão
$conn = null;
?>
