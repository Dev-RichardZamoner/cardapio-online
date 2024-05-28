<?php
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inclua o arquivo de configuração do banco de dados
require_once '../assents/src/config/database.php';

// Verifica se as colunas whatsapp e instagram já existem na tabela restaurantes
$colunas = $conn->query("SHOW COLUMNS FROM restaurantes LIKE 'whatsapp'");
if ($colunas->rowCount() == 0) {
    // Adiciona as colunas whatsapp e instagram à tabela restaurantes
    $sql_alter = "ALTER TABLE restaurantes ADD COLUMN whatsapp VARCHAR(20), ADD COLUMN instagram VARCHAR(255)";
    $conn->exec($sql_alter);
}

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifique se o formulário de restaurante foi enviado
    if (isset($_POST['nome'])) {
        // Campos do restaurante
        $nome = $_POST['nome'] ?? '';
        $endereco = $_POST['endereco'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $email = $_POST['email'] ?? '';
        $whatsapp = $_POST['whatsapp'] ?? '';
        $instagram = $_POST['instagram'] ?? '';
        $usuario_id = $_SESSION['id'] ?? null; // Supondo que o ID do usuário esteja na sessão

        // Atualize a consulta SQL para incluir os novos campos
        $sql = "INSERT INTO restaurantes (nome, endereco, telefone, email, whatsapp, instagram, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->execute([$nome, $endereco, $telefone, $email, $whatsapp, $instagram, $usuario_id]);

            // Armazene uma mensagem de sucesso na sessão
            $_SESSION['success_message'] = 'Restaurante cadastrado com sucesso!';

            // Redirecione para a página inicial após a inserção bem-sucedida
            header("location: inicio.php");
            exit;
        } else {
            echo "Erro ao preparar a consulta: " . $conn->errorInfo()[2];
        }
    }
    // ... Restante do código para processamento do formulário de produto ...
}

// Feche a conexão
$conn = null;
?>
