<?php
session_start();
require_once '../assents/src/config/database.php';



// Consulta para buscar produtos cadastrados
$sql_produtos = "SELECT * FROM produtos LIMIT 3";
$produtos = $conn->query($sql_produtos);

// Consulta para buscar restaurantes cadastrados
$sql_restaurantes = "SELECT * FROM restaurantes LIMIT 3";
$restaurantes = $conn->query($sql_restaurantes);

$conn = null;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cardápio Digital Online</title>
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-100">
    <header class="text-center p-6 bg-white shadow-md w-full">
        <h1 class="text-4xl font-bold">Bem-vindo ao Cardápio Digital Online</h1>
        <p class="mt-2 text-gray-600">A maneira mais fácil e interativa de gerenciar e apresentar seu cardápio.</p>
    </header>

    <main class="flex-grow container mx-auto p-6">
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-center mb-6">Crie o seu cardápio online</h2>
            <p class="text-center text-gray-500">Comece a montar seu cardápio digital agora mesmo e ofereça uma experiência única aos seus clientes.</p>
            <div class="flex justify-center mt-4">
                <a href="./login.php" class="px-4 py-2 mr-2 text-white bg-blue-500 rounded hover:bg-blue-700">Login</a>
                <a href="./registro.php" class="px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700">Registrar</a>
            </div>
        </section>

        <!-- Seção de Produtos -->
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-center mb-6">Produtos em Destaque</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach($produtos as $produto): ?>
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($produto['nome']) ?></h3>
                        <p><?= htmlspecialchars($produto['descricao']) ?></p>
                        <p class="font-bold">Preço: R$<?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Seção de Restaurantes -->
        <section class="mb-10">
            <h2 class="text-3xl font-bold text-center mb-6">Restaurantes Parceiros</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php foreach($restaurantes as $restaurante): ?>
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($restaurante['nome']) ?></h3>
                        <p>Endereço: <?= htmlspecialchars($restaurante['endereco']) ?></p>
                        <p>Telefone: <?= htmlspecialchars($restaurante['telefone']) ?></p>
                        <p>Email: <?= htmlspecialchars($restaurante['email']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="bg-gray-200 text-center p-4 w-full">
        <p class="text-sm text-gray-700">Criado por Richard Zamoner</p>
    </footer>

    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
