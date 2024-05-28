
<?php

$servername = "localhost";
$username = "root";
$password = "pass";
$database = "cardapio_online";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Conexão falhou: " . $e->getMessage();
}
?>