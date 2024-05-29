<?php
// Habilitar a exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclua o arquivo de configuração do banco de dados
require_once '../assents/src/config/database.php';

// Defina variáveis e inicialize com valores vazios
$email = $senha = "";
$erro_email = $erro_senha = "";

// Processando dados do formulário quando o formulário é enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação do email
    if (empty(trim($_POST["email"]))) {
        $erro_email = "Por favor, insira um email.";
    } else {
        // Prepare uma declaração para verificar se o email já existe
        $sql = "SELECT id FROM usuarios WHERE email = :email";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);
            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    $erro_email = "Este email já está em uso. Por favor, tente outro.";
                } else {
                    $email = $param_email;
                }
            } else {
                echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }

    // Validação da senha
    if (empty(trim($_POST["senha"]))) {
        $erro_senha = "Por favor, insira uma senha.";
    } else {
        $senha = trim($_POST["senha"]);
    }

    // Verifique os erros de entrada antes de inserir no banco de dados
    if (empty($erro_email) && empty($erro_senha)) {
        // Prepare uma declaração de inserção
        $sql = "INSERT INTO usuarios (email, senha, `rank`) VALUES (:email, :senha, 1)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Vincule as variáveis à instrução preparada como parâmetros
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":senha", $param_senha, PDO::PARAM_STR);

            // Defina os parâmetros e execute
            $param_email = $email;
            $param_senha = password_hash($senha, PASSWORD_DEFAULT); // Cria uma senha hash
            
            if ($stmt->execute()) {
                // Redirecione para a página de login
                header("location: login.php");
                exit;
            } else {
                $erro_email = "Algo deu errado. Por favor, tente novamente.";
            }
        }
        unset($stmt);
    }
    unset($conn);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form action="registro.php" method="post" class="bg-white p-10 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-center">Registrar</h2>
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="email" name="email" id="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <div class="error text-red-500 text-xs italic"><?php echo $erro_email; ?></div>
        </div>
        <div class="mb-4">
            <label for="senha" class="block text-gray-700 text-sm font-bold mb-2">Senha:</label>
            <input type="password" name="senha" id="senha" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
            <div class="error text-red-500 text-xs italic"><?php echo $erro_senha; ?></div>
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" required class="form-checkbox text-indigo-600">
                <span class="ml-2 text-gray-700 text-sm">Não sou um robô</span>
            </label>
        </div>
        <div class="flex items-center justify-between">
            <input type="submit" value="Registrar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        </div>
    </form>
</body>
</html>
