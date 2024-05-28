<?php
// Iniciar sessão e verificar login
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Conectar ao banco de dados
require_once '../assents/src/config/database.php';

// Funções para adicionar, remover e alterar registros
// ...

// Obter dados da tabela itens_cardapio
try {
    $stmt = $conn->prepare("SELECT * FROM itens_cardapio");
    $stmt->execute();
    $itens_cardapio = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

// Adicionar um item ao cardápio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adicionar'])) {
    // Sanitize user input
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $moeda = filter_input(INPUT_POST, 'moeda', FILTER_SANITIZE_STRING);
    // ... código para processar upload de imagem ...

    // Prepare a consulta SQL
    $sql = "INSERT INTO itens_cardapio (nome, descricao, preco, moeda, imagem_url, usuario_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Execute a consulta
    if ($stmt->execute([$nome, $descricao, $preco, $moeda, $nomeImagem, $usuario_id])) {
        $_SESSION['success_message'] = 'Item adicionado com sucesso!';
    } else {
        $_SESSION['error_message'] = 'Erro ao adicionar item.';
    }
}

// Fechar conexão
$conn = null;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Cardápio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">Gerenciar Cardápio</h1>
        <div class="mb-6">
        <form action="cadastro_produto.php" method="post" enctype="multipart/form-data" class="flex flex-col items-center justify-center">
    <!-- ... campos do formulário ... -->
    <div class="flex w-full justify-center">
        <button type="submit" name="adicionar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Adicionar Cardápio</button>
    </div>
</form>

        </div>
        <div class="mb-6">
            <!-- Tabela para listar itens do cardápio -->
            <table class="table-auto w-full">
                <thead>
                    <tr class="bg-blue-500 text-white">
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nome</th>
                        <th class="px-4 py-2">Descrição</th>
                        <th class="px-4 py-2">Preço</th>
                        <th class="px-4 py-2">Moeda</th>
                        <th class="px-4 py-2">Imagem</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens_cardapio as $item): ?>
                    <tr>
                        <td class="border px-4 py-2"><?= $item['id'] ?></td>
                        <td class="border px-4 py-2"><?= $item['nome'] ?></td>
                        <td class="border px-4 py-2"><?= $item['descricao'] ?></td>
                        <td class="border px-4 py-2"><?= $item['preco'] ?></td>
                        <td class="border px-4 py-2"><?= $item['moeda'] ?></td>
                        <td class="border px-4 py-2"><img src="<?= $item['imagem_url'] ?>" alt="Imagem do produto" /></td>
                        <td class="border px-4 py-2">
                            <!-- Botões para editar e remover -->
                            <!-- ... -->
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
