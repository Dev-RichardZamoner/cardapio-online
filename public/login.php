<?php
// Inclua o arquivo de configuração do banco de dados
require_once '../assents/src/config/database.php';

// Inicialize as variáveis de sessão
session_start();

// Verifique se o formulário foi enviado
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegue o email e a senha do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepare uma declaração para evitar injeções de SQL
    $sql = "SELECT id, senha FROM usuarios WHERE email = :email";
    if($stmt = $conn->prepare($sql)) {
        // Vincule as variáveis à instrução preparada como parâmetros
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        // Tente executar a declaração preparada
        if($stmt->execute()) {
            // Verifique se o usuário existe, se sim, verifique a senha
            if($stmt->rowCount() == 1) {
                if($row = $stmt->fetch()) {
                    $id = $row['id'];
                    $hashed_password = $row['senha'];
                    if(password_verify($senha, $hashed_password)) {
                        // Senha correta, inicie uma nova sessão
                        session_start();
                        
                        // Armazene os dados em variáveis de sessão
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['email'] = $email;
                        
                        // Redirecione o usuário para a página de boas-vindas
                        header("location: inicio.php");
                        exit;
                    } else {
                        // Senha incorreta
                        $erro_login = "Senha inválida.";
                    }
                }
            } else {
                // Exiba uma mensagem de erro se o email não existir
                $erro_login = "Nenhuma conta encontrada com esse email.";
            }
        } else {
            echo "Oops! Algo deu errado. Por favor, tente novamente mais tarde.";
        }
        // Feche a declaração
        unset($stmt);
    }
}
// Feche a conexão
unset($conn);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="/css/style.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
    
    <div class="w-full max-w-xs">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" name="email" required>
            </div>    
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="senha">Senha</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" type="password" name="senha" required>
            </div>
            <div class="flex items-center justify-between">
                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" value="Login">
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="registro.php">Registre-se</a>
            </div>
            <!-- Mensagem de erro para login, se necessário -->
            <?php if(!empty($erro_login)): ?>
                <div class="error"><?php echo $erro_login; ?></div>
            <?php endif; ?>
        </form>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
