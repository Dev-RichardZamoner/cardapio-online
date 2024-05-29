<?php
session_start();
require_once '../assents/src/config/database.php';
require_once '../assents/src/controllers/HeaderController.php';

// Verifique se o usuário está logado e obtenha o rank e o username
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $user_id = $_SESSION["id"];
    $rank = isset($_SESSION["rank"]) ? $_SESSION["rank"] : 0;

    // Prepare a consulta SQL para buscar o e-mail do usuário
    $sql = "SELECT email FROM usuarios WHERE id = :id";
    if($stmt = $conn->prepare($sql)){
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
         // Execute a consulta
         if($stmt->execute()){
            // Verifique se o usuário foi encontrado
            if($stmt->rowCount() == 1){
                // Busque o resultado
                if($row = $stmt->fetch()){
                    $username = $row["email"];
                }
            } else {
                header("location: login.php");
                exit;
            }
        } else {
            echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
    }
    unset($stmt);
} else {
    header("location: login.php");
    exit;
}

$controller = new HeaderController();
$current_time = $controller->getCurrentTime();

// Conteúdo específico do produtos_cadastrados.php
// Conexão com o banco de dados
$dbUsername = "root"; // Nome de usuário para a conexão com o banco de dados
$password = "pass";
$dbname = "cardapio_online";

// Criar conexão
$conn = new mysqli($servername, $dbUsername, $password, $dbname);


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



// Feche a conexão
$conn = null;
?>




<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Produtos Cadastrados</title>
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
        <a href="adicionar_noticias.php" class="hover:text-white py-2 px-4">Adicionar Noticia</a>
        <a href="ver_todas_noticias.php" class="hover:text-white py-2 px-4">Ultimas Noticia</a>
        <a href="#" class="hover:text-white py-2 px-4">Ajude nossa equipe</a>
        <a href="config.php" class="hover:text-white py-2 px-4">Configurar</a>
        <a href="logout.php" class="hover:text-white py-2 px-4">Sair</a>
    </div>
    <body class="bg-gray-100 flex flex-col min-h-screen">
    <div class="container mx-auto mt-10 flex-grow">
        <h1 class="text-2xl font-bold text-center mb-6">Produtos Cadastrados</h1>
        <!-- Listagem de produtos -->
        <?php if ($result_produtos->num_rows > 0): ?>
            <div class="grid grid-cols-3 gap-4">
                <?php $count = 0; ?>
                <?php while($count < 3 && $produto = $result_produtos->fetch_assoc()): ?>
                    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <h2 class="text-xl font-bold mb-2"><?= $produto['nome'] ?></h2>
                        <p><?= $produto['descricao'] ?></p>
                        <p class="font-bold">Preço: R$<?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    </div>
                    <?php $count++; ?>
                <?php endwhile; ?>
            </div>
            <div class="text-center my-4">
                <a href="todos_produtos.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Ver Mais Produtos</a>
            </div>
        <?php else: ?>
            <p>Nenhum produto cadastrado.</p>
        <?php endif; ?>

        <h1 class="text-2xl font-bold text-center mb-6 mt-12">Restaurantes Cadastrados</h1>
        <!-- Listagem de restaurantes -->
        <?php if ($result_restaurantes->num_rows > 0): ?>
            <div class="grid grid-cols-3 gap-4">
                <?php $count = 0; ?>
                <?php while($count < 3 && $restaurante = $result_restaurantes->fetch_assoc()): ?>
                    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                        <h2 class="text-xl font-bold mb-2"><?= $restaurante['nome'] ?></h2>
                        <p>Endereço: <?= $restaurante['endereco'] ?></p>
                        <p>Telefone: <?= $restaurante['telefone'] ?></p>
                        <p>Email: <?= $restaurante['email'] ?></p>
                    </div>
                    <?php $count++; ?>
                <?php endwhile; ?>
            </div>
            <div class="text-center my-4">
                <a href="todos_restaurantes.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Ver Mais Restaurantes</a>
            </div>
        <?php else: ?>
            <p>Nenhum restaurante cadastrado.</p>
        <?php endif; ?>
    </div>

</body>


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
