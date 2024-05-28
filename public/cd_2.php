<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclua o arquivo de configuração do banco de dados
require_once '../assents/src/config/database.php';

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = filter_var($_POST['nome'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $descricao = filter_var($_POST['descricao'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
    $moeda = filter_var($_POST['moeda'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $imagem = $_FILES['imagem'] ?? null;
    $usuario_id = $_SESSION['id'] ?? null;

    // Validação do preço
    if ($preco === false) {
        $_SESSION['error_message'] = 'Por favor, insira um valor válido para o preço.';
        header('Location: seu_formulario.php');
        exit;
    }

    // Validação do nome
    if (empty($nome)) {
        $_SESSION['error_message'] = 'O campo nome é obrigatório.';
        header('Location: seu_formulario.php');
        exit;
    }

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
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">Cadastrar Produto</h1>
        <form action="processa_cadastro_produto.php" method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome do Produto:</label>
                <input type="text" name="nome" id="nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="descricao" class="block text-gray-700 text-sm font-bold mb-2">Descrição:</label>
                <textarea name="descricao" id="descricao" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>
            <div class="mb-4">
                <label for="preco" class="block text-gray-700 text-sm font-bold mb-2">Preço:</label>
                <input type="number" name="preco" id="preco" required min="0" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="moeda" class="block text-gray-700 text-sm font-bold mb-2">Moeda:</label>
                <select name="moeda" id="moeda" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="BRL">Real</option>
                    <option value="USD">Dólar</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="imagem" class="block text-gray-700 text-sm font-bold mb-2">Imagem do Produto:</label>
                <input type="file" name="imagem" id="imagem" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-center">
                <input type="submit" value="Cadastrar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
    </div>
</body>
</html>
