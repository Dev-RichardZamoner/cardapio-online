<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once '../assents/src/config/database.php';
require_once '../assents/src/controllers/HeaderController.php';

$controller = new HeaderController();
$current_time = $controller->getCurrentTime();

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Certifique-se de que $conn está definido e não é nulo
    if (isset($conn) && $conn instanceof PDO) {
        // Validação dos campos do formulário
        $usuario_id = $_SESSION['id'] ?? null;
        $nome = $_POST['produto_nome'] ?? null;
        $descricao = $_POST['produto_descricao'] ?? null;
        $preco = $_POST['produto_preco'] ?? null;
        $imagem_url = $_POST['produto_imagem_url'] ?? null;

        // Verifique se os campos obrigatórios estão preenchidos
        if ($nome && $preco) {
            $param_preco = floatval($preco); // Converte o preço para um número

            // Prepare a consulta SQL
            $sql = "INSERT INTO itens_cardapio (usuario_id, nome, descricao, preco, imagem_url) VALUES (:usuario_id, :nome, :descricao, :preco, :imagem_url)";
            $stmt = $conn->prepare($sql);

            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->bindParam(':preco', $param_preco);
            $stmt->bindParam(':imagem_url', $imagem_url, PDO::PARAM_STR);

            // Execute a consulta
            if ($stmt->execute()) {
                // Redirecione para a página inicial após a inserção bem-sucedida
                header("location: inicio.php");
                exit;
            } else {
                echo "Algo deu errado. Por favor, tente novamente.";
            }
        } else {
            echo "Por favor, preencha todos os campos obrigatórios.";
        }
    } else {
        echo "Erro de conexão com o banco de dados.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Crie o seu cardápio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/css/style.css" rel="stylesheet">
    <style>
        /* Estilos para o header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #333;
            color: white;
        }
        .menu-hamburguer {
            /* Estilos para o menu hambúrguer */
            cursor: pointer;
        }
        .time-display {
            /* Estilos para o display de hora */
        }
        /* Estilos para o menu */
        .menu-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .menu-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .menu-content a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="header">
        <div class="menu-hamburguer" onclick="toggleMenu()">
            ☰ Menu
        </div>
        <div class="time-display" id="time-display">
            <!-- JavaScript irá atualizar a hora aqui -->
        </div>
    </div>
    <div class="menu-content" id="menu-content">
        <a href="inicio.php">Inicio</a>
        <a href="#">Alterar cárdapio</a>
        <a href="#">Ajude nossa equipe</a>
        <a href="config.php">Configurar</a>
        <a href="logout.php">Sair</a>
    </div>

    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">Crie o seu cardápio</h1>
        <form action="cardapio.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <div class="mb-4">
        <label for="produto_nome" class="block text-gray-700 text-sm font-bold mb-2">Nome do Produto:</label>
        <input type="text" name="produto_nome" id="produto_nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="produto_descricao" class="block text-gray-700 text-sm font-bold mb-2">Descrição:</label>
        <textarea name="produto_descricao" id="produto_descricao" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
    </div>
    <div class="mb-4">
        <label for="produto_preco" class="block text-gray-700 text-sm font-bold mb-2">Preço:</label>
        <input type="text" name="produto_preco" id="produto_preco" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>
    <div class="mb-4">
        <label for="produto_imagem_url" class="block text-gray-700 text-sm font-bold mb-2">URL da Imagem:</label>
        <input type="text" name="produto_imagem_url" id="produto_imagem_url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div class="flex items-center justify-between">
        <input type="submit" value="Adicionar ao Cardápio" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
    </div>
</form>
    </div>

    <script>
        // Função para atualizar a hora
        function updateTime() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('time-display').textContent = hours + ':' + minutes + ':' + seconds;
        }

        // Atualize a hora a cada segundo
        setInterval(updateTime, 1000);

        // Função para alternar o menu
        function toggleMenu() {
            var menu = document.getElementById('menu-content');
            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }
    </script>
</body>
</html>
