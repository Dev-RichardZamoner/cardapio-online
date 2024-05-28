<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "pass";
$dbname = "cardapio_online";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta para buscar produtos cadastrados
$sql_produtos = "SELECT * FROM produtos";
$result_produtos = $conn->query($sql_produtos);

// Consulta para buscar restaurantes cadastrados
$sql_restaurantes = "SELECT * FROM restaurantes";
$result_restaurantes = $conn->query($sql_restaurantes);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Cardápio</title>
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
        <h1 class="text-2xl font-bold text-center mb-6">Produtos Cadastrados</h1>
        <!-- Listagem de produtos -->
        <?php if ($result_produtos->num_rows > 0): ?>
            <div class="grid grid-cols-3 gap-4">
                <?php while($produto = $result_produtos->fetch_assoc()): ?>
                    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <h2 class="text-xl font-bold mb-2"><?= $produto['nome'] ?></h2>
                        <p><?= $produto['descricao'] ?></p>
                        <p class="font-bold">Preço: R$<?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Nenhum produto cadastrado.</p>
        <?php endif; ?>

        <h1 class="text-2xl font-bold text-center mb-6 mt-12">Restaurantes Cadastrados</h1>
        <!-- Listagem de restaurantes -->
        <?php if ($result_restaurantes->num_rows > 0): ?>
            <div class="grid grid-cols-3 gap-4">
                <?php while($restaurante = $result_restaurantes->fetch_assoc()): ?>
                    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <h2 class="text-xl font-bold mb-2"><?= $restaurante['nome'] ?></h2>
                        <p>Endereço: <?= $restaurante['endereco'] ?></p>
                        <p>Telefone: <?= $restaurante['telefone'] ?></p>
                        <p>Email: <?= $restaurante['email'] ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Nenhum restaurante cadastrado.</p>
        <?php endif; ?>
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
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
