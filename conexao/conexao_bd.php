<?php
// Configuração para conexão com o banco de dados
$host = "localhost";     // Endereço do servidor
$dbname = "u413819793_imports"; // Nome do banco de dados
$user = "u413819793_cliente";          // Usuário do banco de dados
$password = "b&0g=sAI#Q3q";          // Senha do banco de dados

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
