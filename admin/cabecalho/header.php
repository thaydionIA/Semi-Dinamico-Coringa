<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link para o CSS -->
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <!-- Link para o Font Awesome para os ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script defer src="../admin/assets/js/menu.js"></script> <!-- Carregar o JavaScript -->
       <link rel="icon" href="../assets/images/logo/Logo.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="logo">
             <img src="../assets/images/logo/Logo.png" alt="Logo Sua Marca">
        </div>
        <h1 class="site-title">Sua Marca</h1>

        <!-- Ícone de menu -->
        <div class="menu-icon" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="sidebar">
            <div class="close-icon" onclick="toggleMenu()">×</div> <!-- Ícone de fechar -->
            <ul>
                <!-- Adicionando o item "Home" com o ícone de início -->
                <li><a href="index.php"><i class="fas fa-home"></i> Painel De Adminstração </a></li>
                <li><a href="produtos.php"><i class="fas fa-cogs"></i> Gerenciar Produtos</a></li>
                <li><a href="adicionar_produto.php"><i class="fas fa-plus-circle"></i> Adicionar Produto</a></li>
                <li><a href="../index.php"><i class="fas fa-home"></i> Página Principal Do Site</a></li>
            </ul>
        </nav>
    </header>
</body>
</html>
