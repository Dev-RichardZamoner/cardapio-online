<?php
// Inclua o arquivo de configuração do banco de dados
require_once '../assents/src/config/database.php';

// Verifique se o usuário está logado
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Defina variáveis para mensagens de erro
$erro_nome = $erro_senha = $erro_confirm_senha = "";

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação e atualização do nome e senha
    $param_nome = $_POST["nome"] ?? '';
    $param_senha = $_POST["senha"] ?? '';

    // Certifique-se de que a senha não seja nula antes de tentar hash
    if (!empty($param_senha)) {
        $param_senha = password_hash($param_senha, PASSWORD_DEFAULT);
    } else {
        $erro_senha = "Por favor, insira uma senha.";
    }

    // Após a execução bem-sucedida da atualização
header("location: inicio.php");
exit;


    // Atualize o banco de dados com as novas informações
    if (empty($erro_nome) && empty($erro_senha)) {
        $sql = "UPDATE usuarios SET nome = :nome, senha = :senha WHERE id = :id";
        if ($stmt = $conn->prepare($sql)) {
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":nome", $param_nome, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $param_senha, PDO::PARAM_STR);
            $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Atualização bem-sucedida, redirecione ou exiba uma mensagem
                // ...
            } else {
                echo "Algo deu errado. Por favor, tente novamente.";
            }
        }
        unset($stmt);
    }
}
unset($conn);
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Configurações</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center items-center h-screen bg-gray-100">
    <div class="w-full max-w-xs">
        <h1 class="text-lg font-semibold text-center mb-6">Adicione o nome do seu restaurante</h1>
        <form action="config.php" method="post" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
                <input type="text" name="nome" id="nome" value="<?php echo $_SESSION["nome"] ?? ''; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="error text-red-500 text-xs italic"><?php echo $erro_nome; ?></div>
            </div>
            <div class="mb-4">
                <label for="senha" class="block text-gray-700 text-sm font-bold mb-2">Nova Senha:</label>
                <input type="password" name="senha" id="senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                <div class="error text-red-500 text-xs italic"><?php echo $erro_senha; ?></div>
            </div>
            <div class="mb-6">
                <label for="confirm_senha" class="block text-gray-700 text-sm font-bold mb-2">Confirme a Nova Senha:</label>
                <input type="password" name="confirm_senha" id="confirm_senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="error text-red-500 text-xs italic"><?php echo $erro_confirm_senha; ?></div>
            </div>
            <div class="flex items-center justify-between">
                <input type="submit" value="Atualizar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </div>
        </form>
    </div>
</body>
</html>
