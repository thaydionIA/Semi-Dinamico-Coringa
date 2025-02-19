<?php
session_start();
include '../admin/cabecalho/header.php'; 

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['admin'])) {
    // Redireciona para a página de login se não estiver logado
    header('Location: login.php');
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css"> <!-- Linkando o CSS para o painel -->
</head>
<body>
    <div class="admin-panel">
        <h1>Painel Administrativo</h1>
        <p>Bem-vindo ao painel administrativo da Sua Marca. Selecione uma opção abaixo para gerenciar os produtos:</p>
        <ul>
            <li><a href="produtos.php">Gerenciar Produtos</a></li>
            <li><a href="adicionar_produto.php">Adicionar Novo Produto</a></li>
            <li><a href="?logout=true" onclick="return confirm('Deseja realmente sair?')">Sair</a></li>
        </ul>
    </div>
</body>
</html>

<?php include '../admin/cabecalho/footer.php'; ?>
