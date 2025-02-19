<?php
// Caminho absoluto para o arquivo de conexão
require_once __DIR__ . '/../conexao/conexao_bd.php';

// Diretório de uploads
$upload_dir = __DIR__ . '/../admin/uploads/'; // Caminho do sistema
$upload_url = 'admin/uploads/'; // URL para exibir as imagens

// Número do WhatsApp
$whatsapp_number = "+556293041755";

// Obter a categoria correspondente (neste caso, 'Produto 02')
$categoria_nome = 'Produto 02';

// Buscar os produtos da categoria
$stmt = $conn->prepare("SELECT id, nome, preco, imagem, status FROM produtos WHERE categoria_id = (SELECT id FROM categorias WHERE nome = :nome)");
$stmt->bindParam(':nome', $categoria_nome);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - <?= htmlspecialchars($categoria_nome) ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="products">
        <?php foreach ($produtos as $produto): ?>
            <div class="product-item <?= $produto['status'] === 'esgotado' ? 'esgotado' : '' ?>">
                <img src="<?= $upload_url . htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                <h2><?= htmlspecialchars($produto['nome']) ?></h2>
                <p>PREÇO: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                <a 
                    href="https://wa.me/<?= str_replace('+', '', $whatsapp_number) ?>?text=Olá, gostaria de mais informações sobre o produto: <?= rawurlencode($produto['nome']) ?>" 
                    class="btn-detalhes" 
                    target="_blank"
                    <?= $produto['status'] === 'esgotado' ? 'style="pointer-events: none; opacity: 0.6;"' : '' ?>
                >
                    Mais Informações
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
