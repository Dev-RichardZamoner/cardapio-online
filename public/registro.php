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
    <!-- Adicione aqui o link para seus estilos CSS se necessário -->
    <style>
        /* Estilos básicos para o formulário, substitua com seu próprio CSS se necessário */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box; /* Adiciona padding dentro do width e height */
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .error {
            color: red;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <form action="registro.php" method="post">
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <!-- Mensagem de erro para o email, se necessário -->
            <div class="error"><?php echo $erro_email; ?></div>
        </div>
        <div>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            <!-- Mensagem de erro para a senha, se necessário -->
            <div class="error"><?php echo $erro_senha; ?></div>
        </div>
        <div>
            <input type="submit" value="Registrar">
        </div>
    </form>
</body>
</html>
