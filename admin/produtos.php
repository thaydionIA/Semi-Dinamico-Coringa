<?php
session_start();
include '../admin/cabecalho/header.php'; 
require_once '../conexao/conexao_bd.php';

$mensagem = '';
$mensagem_tipo = ''; // 'sucesso' ou 'erro'

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Exclusão de produto
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Buscar o registro para obter o nome da imagem
    $stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        // Caminho completo do arquivo
        $imagem_caminho = __DIR__ . '/uploads/' . $produto['imagem'];

        // Remover a imagem, se existir
        if (file_exists($imagem_caminho)) {
            unlink($imagem_caminho);
        }

        // Excluir o registro do banco de dados
        $stmt = $conn->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $mensagem = "Produto excluído com sucesso!";
        $mensagem_tipo = 'sucesso';
    } 
}

// Filtragem de produtos por categoria
$categoria_id = isset($_GET['categoria']) && is_numeric($_GET['categoria']) ? $_GET['categoria'] : null;

if ($categoria_id) {
    $stmt = $conn->prepare("SELECT produtos.*, categorias.nome AS categoria_nome FROM produtos 
                            JOIN categorias ON produtos.categoria_id = categorias.id
                            WHERE produtos.categoria_id = :categoria_id");
    $stmt->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $produtos = $conn->query("SELECT produtos.*, categorias.nome AS categoria_nome FROM produtos 
                              JOIN categorias ON produtos.categoria_id = categorias.id")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css"> <!-- Linkando o CSS para o painel -->
    <link rel="stylesheet" href="../admin/assets/css/filtro.css">
    <!-- Linkando o Font Awesome para os ícones -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .notification {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #f44336; /* Cor para exclusão */
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 1rem;
            z-index: 1000;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
        }

        .notification .check-icon {
            font-size: 24px;
            margin-right: 10px;
        }
     
    </style>
</head>
<body>
    <!-- Caixa de notificação -->
    <?php if (!empty($mensagem)): ?>
        <div class="notification <?= $mensagem_tipo; ?>" id="notification">
            <span class="check-icon">&#10004;</span>
            <span class="message"><?= $mensagem; ?></span>
        </div>
    <?php endif; ?>

    <div class="gerenciamento-produtos">
       <div class="header-edit d-flex justify-content-between">
    <a href="index.php" class="back-icon"><i class="fas fa-arrow-left"></i></a> <!-- Ícone de voltar -->
    <a href="adicionar_produto.php" class="text-decoration-none text-dark fs-4">
        <i class="fas fa-plus"></i> Adicionar Produto
    </a>
</div>


        <h1>Gerenciamento de Produtos</h1>
        <p>Bem-vindo ao painel de gerenciamento de produtos. Selecione uma opção abaixo para gerenciar os produtos:</p>
        
    <div class="filter-section">
    <form method="GET" action="produtos.php">
        <label for="categoria">Filtrar por Categoria:</label>
        <select name="categoria" id="categoria">
            <option value="">Todas</option>
            <?php foreach ($conn->query("SELECT id, nome FROM categorias") as $categoria): ?>
                <option value="<?= $categoria['id'] ?>" <?= isset($_GET['categoria']) && $_GET['categoria'] == $categoria['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">
            <i class="fas fa-search"></i> <!-- Ícone de lupa -->
        </button>
    </form>
</div>


        <!-- Mensagem para arrastar para o lado -->
        <div class="mobile-scroll-message">
            <p>Arraste para o lado para ver mais.</p>
        </div>
        <!-- Wrapper da tabela com rolagem -->
        <div class="product-table-wrapper">
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Imagem</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($produto['categoria_nome']) ?></td>
                        <td><?= htmlspecialchars($produto['status']) ?></td>
                        <td><img src="uploads/<?= htmlspecialchars($produto['imagem']) ?>" alt="Imagem do Produto" width="50"></td>
                        <td>
                            <a href="editar_produto.php?id=<?= $produto['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
                          <button class="btn btn-danger" onclick="confirmarExclusao(<?= $produto['id'] ?>)"><i class="fas fa-trash-alt"></i></button>

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja excluir este produto?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="confirmDeleteButton" class="btn btn-danger">Excluir</a>
            </div>
        </div>
    </div>
</div>


<script> 
    function confirmarExclusao(id) {
    const confirmButton = document.getElementById('confirmDeleteButton');
    confirmButton.href = `produtos.php?delete=${id}`;
    let modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    modal.show();
}
</script>
    <script>
        // Exibir a notificação por alguns segundos e recarregar a página
        document.addEventListener('DOMContentLoaded', function () {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'flex';
                setTimeout(function () {
                    notification.style.display = 'none';
                }, 4000); // 4 segundos
            }
        });
    </script>
</body>
</html>

<?php include '../admin/cabecalho/footer.php'; ?>
