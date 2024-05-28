<?php
session_start();

if (isset($_SESSION['success_message'])) {
    echo "<p>" . $_SESSION['success_message'] . "</p>";
    // Limpe a mensagem de sucesso após exibição
    unset($_SESSION['success_message']);
}

// ... Restante do código para a página inicio.php ...
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Restaurante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <a href="cadastro_restaurante.php">Cadastrar Restaurante</a>
    <a href="cadastro_produto.php">Cadastrar Produtos</a>
        <a href="cardapio.php">Crie o seu cárdapio</a>
        <a href="#">Ajude nossa equipe</a>
        <a href="config.php">Configurar</a>
        <a href="logout.php">Sair</a>
    </div>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">Cadastrar Restaurante</h1>
        <form action="processa_cadastro_restaurante.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome do Restaurante:</label>
                <input type="text" name="nome" id="nome" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="endereco" class="block text-gray-700 text-sm font-bold mb-2">Endereço:</label>
                <input type="text" name="endereco" id="endereco" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="telefone" class="block text-gray-700 text-sm font-bold mb-2">Telefone:</label>
                <input type="text" name="telefone" id="telefone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
       
            <div class="mb-4">
            <label for="whatsapp" class="block text-gray-700 text-sm font-bold mb-2">WhatsApp:</label>
            <input type="text" name="whatsapp" id="whatsapp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="instagram" class="block text-gray-700 text-sm font-bold mb-2">Instagram:</label>
            <input type="text" name="instagram" id="instagram" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-between">
                <input type="submit" value="Cadastrar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
    </div>
    </head>
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
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
