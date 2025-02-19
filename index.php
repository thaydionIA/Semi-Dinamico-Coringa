<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="../assets/js/animado.js"></script>
    <script defer src="../assets/js/banner.js"></script>
       <link rel="icon" href="assets/images/logo/Logo.png" type="image/x-icon">
</head>
<body>
    <?php include 'cabecalho/header.php'; ?>

    <main>
        <h1 class="main-heading">Bem-vindo à (sua marca)</h1>
        
        <section class="banner">
            <div class="banner-slide">
                <img src="/assets/images/banner/banner_1.png" alt="Banner 1">
            </div>
            <div class="banner-slide">
                <img src="/assets/images/banner/banner_2.png" alt="Banner 2">
            </div>
            <div class="banner-slide">
                <img src="/assets/images/banner/banner_3.png" alt="Banner 3">
            </div>
        </section> 

        <section id="camisetas">
            <h1 class="h1-animado">Seu Produto (Produto 01)</h1>
            <?php include 'produtos/produto01.php'; ?>
        </section>
        
        <section id="bones">
            <h1 class="h1-animado">Seu Produto (Produto 02)</h1>
            <?php include 'produtos/produto02.php'; ?>
        </section>
        
        <section id="bermudas">
            <h1 class="h1-animado">Seu Produto (Produto 03)</h1>
            <?php include 'produtos/produto03.php'; ?>
        </section>

        
    </main>

    <?php include 'cabecalho/footer.php'; ?>
</body>
</html>
