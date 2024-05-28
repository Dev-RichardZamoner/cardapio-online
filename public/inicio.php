<?php
session_start();
require_once '../assents/src/config/database.php';
require_once '../assents/src/controllers/HeaderController.php';

// Verifique se o usuário está logado e obtenha o rank e o username
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    $user_id = $_SESSION["id"]; // Certifique-se de que o ID do usuário está armazenado na sessão
    $rank = isset($_SESSION["rank"]) ? $_SESSION["rank"] : 0;

    // Prepare a consulta SQL para buscar o e-mail do usuário
    $sql = "SELECT email FROM usuarios WHERE id = :id";
    if($stmt = $conn->prepare($sql)){
        // Vincule o ID do usuário como parâmetro
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
                // Usuário não encontrado, redirecione para a página de login
                header("location: login.php");
                exit;
            }
        } else {
            echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
    }

    // Feche a declaração
    unset($stmt);
} else {
    // Redirecione para a página de login se o usuário não estiver logado
    header("location: login.php");
    exit;
}

$controller = new HeaderController();
$current_time = $controller->getCurrentTime();

// Inicialize as variáveis para as contagens
$countUsuarios = $countRestaurantes = $countProdutos = 0;

// Consulte o banco de dados para obter as contagens
try {
    // Contagem de usuários
    $stmt = $conn->query("SELECT COUNT(*) FROM usuarios");
    $countUsuarios = $stmt->fetchColumn();

    // Contagem de restaurantes
    $stmt = $conn->query("SELECT COUNT(*) FROM restaurantes");
    $countRestaurantes = $stmt->fetchColumn();

    // Contagem de produtos
    $stmt = $conn->query("SELECT COUNT(*) FROM itens_cardapio");
    $countProdutos = $stmt->fetchColumn();
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}

// Consulta para buscar as últimas notícias
$sql = "SELECT noticias.titulo, noticias.conteudo, usuarios.email AS autor FROM noticias JOIN usuarios ON noticias.usuario_id = usuarios.id ORDER BY noticias.data_publicacao DESC";
$noticias = [];

if($stmt = $conn->prepare($sql)){
    // Execute a consulta
    if($stmt->execute()){
        // Busque os resultados
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $noticias[] = $row;
        }
    } else {
        echo "Erro ao buscar notícias.";
    }
}

// Feche a declaração
unset($stmt);

// Feche a conexão
unset($conn);

// Feche a conexão
$conn = null;
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
        <a href="adicionar_noticias.php" class="hover:text-white py-2 px-4">Adicionar Noticia</a>
        <a href="ver_todas_noticias.php" class="hover:text-white py-2 px-4">Ultimas Noticia</a>
        <a href="#" class="hover:text-white py-2 px-4">Ajude nossa equipe</a>
        <a href="config.php" class="hover:text-white py-2 px-4">Configurar</a>
        <a href="logout.php" class="hover:text-white py-2 px-4">Sair</a>
    </div>
    <div class="container mx-auto p-6">
        <!-- Seção de Notícias -->
        <section class="mb-8">
            <h2 class="text-3xl font-bold text-center mb-6">Últimas Notícias</h2>
            <?php foreach ($noticias as $noticia): ?>
                <div class="mb-6 p-6 bg-white rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($noticia['titulo']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?></p>
                    <div class="mt-4 text-gray-600">
                        Publicado por: <?php echo htmlspecialchars($noticia['autor']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="text-center">
                <a href="ver_todas_noticias.php" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">Ver Todas as Notícias</a>
            </div>
        </section>

        <!-- Seção do Dashboard -->
        <section class="mb-8">
            <h2 class="text-3xl lg:text-4xl font-bold text-center mb-8">Dashboard</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Conteúdo do Dashboard -->
            </div>
            <div class="mt-8 p-6 bg-white rounded-lg shadow-lg">
                <canvas id="dashboardChart"></canvas>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center p-4 text-black">
            <p>Desenvolvido por Richard Zamoner</p>
        </footer>




    <script>
        // Dados para os gráficos
        const data = {
            labels: ['Usuários', 'Restaurantes', 'Produtos'],
            datasets: [{
                label: 'Contagem',
                data: [<?php echo $countUsuarios; ?>, <?php echo $countRestaurantes; ?>, <?php echo $countProdutos; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        // Configuração dos gráficos
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Renderizar o gráfico
        const dashboardChart = new Chart(
            document.getElementById('dashboardChart'),
            config
        );
    </script>
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

