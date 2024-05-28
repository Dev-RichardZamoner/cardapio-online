<?php
session_start();
require_once '../assents/src/config/database.php';

// Consulta para buscar todas as notícias
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
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Todas as Notícias</title>
    <link href="/css/style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-bold text-center mb-8">Todas as Notícias</h1>
        <?php foreach ($noticias as $noticia): ?>
            <div class="mb-6 p-6 bg-white rounded-lg shadow-lg">
                <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?></p>
                <div class="mt-4 text-gray-600">
                    Publicado por: <?php echo htmlspecialchars($noticia['autor']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
