<?php
session_start();
require_once '../assents/src/config/database.php';

// Verifique se o usuário está logado e obtenha o rank e o username
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $user_id = $_SESSION["id"]; // Certifique-se de que o ID do usuário está armazenado na sessão
    $rank = isset($_SESSION["rank"]) ? $_SESSION["rank"] : 0;

    // Prepare a consulta SQL para buscar o e-mail do usuário
    $sql = "SELECT email FROM usuarios WHERE id = :id";
    if ($stmt = $conn->prepare($sql)) {
        // Vincule o ID do usuário como parâmetro
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

        // Execute a consulta
        if ($stmt->execute()) {
            // Verifique se o usuário foi encontrado
            if ($stmt->rowCount() == 1) {
                // Busque o resultado
                if ($row = $stmt->fetch()) {
                    $username = $row["email"];
                }
            } else {
                // Usuário não encontrado, redirecione para a página de login
                header("location: login.php");
                exit;
            }
        } else {
            echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
        // Feche a declaração
        unset($stmt);
    }
} else {
    // Redirecione para a página de login se o usuário não estiver logado
    header("location: login.php");
    exit;
}

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegue os dados do formulário
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];

    // Prepare a inserção no banco de dados
    $sql = "INSERT INTO noticias (titulo, conteudo, usuario_id) VALUES (:titulo, :conteudo, :usuario_id)";
    if ($stmt = $conn->prepare($sql)) {
        // Vincule as variáveis aos parâmetros
        $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
        $stmt->bindParam(":conteudo", $conteudo, PDO::PARAM_STR);
        $stmt->bindParam(":usuario_id", $user_id, PDO::PARAM_INT);

        // Execute a consulta
        if ($stmt->execute()) {
            echo "Notícia adicionada com sucesso!";
        } else {
            echo "Erro ao adicionar notícia.";
        }
        // Feche a declaração
        unset($stmt);
    }
}
// Feche a conexão
unset($conn);
?>



<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Início</title>
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Inclua a biblioteca Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex">
    <!-- Menu lateral fixo com Tailwind CSS -->
    <div class="bg-black w-64 min-h-screen flex flex-col text-white">
        <!-- Hora e título centralizados no menu -->
        <div class="text-center py-4" id="time-display">
            <!-- JavaScript irá atualizar a hora aqui -->
        </div>
        <h1 class="text-xl font-bold text-center py-4">Cardápio Online</h1>
        <!-- E-mail do usuário e opção de sair -->
        <div class="text-center py-2">
    <p class="text-sm"><?php echo $username; ?></p>
    <div class="flex justify-center space-x-4">
        <a href="inicio.php" class="text-gray-300 hover:text-white py-2 px-4 text-sm">Início</a>
        <a href="logout.php" class="text-gray-300 hover:text-white py-2 px-4 text-sm">Sair</a>
    </div>
</div>

        <!-- Links do menu -->
        <a href="produtos_cadastrados.php" class="hover:text-white py-2 px-4">Todos cadastros</a>
        <a href="gerenciar_cadastrados.php" class="hover:text-white py-2 px-4">Gerenciar cadastros</a>
        <a href="cadastro_restaurante.php" class="hover:text-white py-2 px-4">Cadastrar Restaurante</a>
        <a href="cadastro_produto.php" class="hover:text-white py-2 px-4">Cadastrar Produtos</a>
        <a href="cardapio.php" class="hover:text-white py-2 px-4">Crie o seu cárdapio</a>
        <a href="#" class="hover:text-white py-2 px-4">Ajude nossa equipe</a>
        <a href="config.php" class="hover:text-white py-2 px-4">Configurar</a>
        <a href="logout.php" class="hover:text-white py-2 px-4">Sair</a>
    </div>
<body class="flex">
<div class="container mx-auto p-6">
<form action="" method="post" class="space-y-4">
        <div>
            <label for="titulo" class="block text-sm font-medium text-gray-700">Título da Notícia</label>
            <input type="text" id="titulo" name="titulo" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
        </div>
        <div>
            <label for="conteudo" class="block text-sm font-medium text-gray-700">Conteúdo</label>
            <textarea id="conteudo" name="conteudo" rows="4" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"></textarea>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Publicar Notícia</button>
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