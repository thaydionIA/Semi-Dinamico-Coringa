<?php
// Configuração para conexão com o banco de dados
$host = "localhost";     // Endereço do servidor
$dbname = "semi-dinamico-coringa"; // Nome do banco de dados
$user = "root";          // Usuário do banco de dados
$password = "";          // Senha do banco de dados

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
