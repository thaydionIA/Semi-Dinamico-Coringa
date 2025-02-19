<head>
      <link rel="icon" href="assets/images/logo/Logo.png" type="image/x-icon">
    <script defer src="assets/js/menu.js"></script>
    <!-- Link para o Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<header>
    <div class="logo">
        <img src="../assets/images/logo/Logo.png" alt="Logo Sua Marca">
    </div>
    <h1 class="site-title">Sua Marca </h1>

    <!-- Ícone de menu -->
    <div class="menu-icon" onclick="toggleMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Menu lateral -->
    <nav class="sidebar">
        <div class="close-icon" onclick="toggleMenu()">×</div> <!-- Ícone de fechar -->
        <ul>
            <li><a href="../index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="../produto01.php"><i class="fas fa-tshirt"></i> Seu Produto (Produto 01)</a></li>
            <li><a href="../produto02.php"><i class="fas fa-hat-cowboy"></i> Seu Produto (Produto 02)</a></li>
            <li><a href="../produto03.php"><i class="fas fa-suitcase"></i> Seu Produto (Produto 03)</a></li>
            <li><a href="../contato.php"><i class="fas fa-envelope"></i> Contato</a></li>
            <li><a href="admin/login.php"><i class="fas fa-user-shield"></i> Admin</a></li>
        </ul>
    </nav>
</header>
