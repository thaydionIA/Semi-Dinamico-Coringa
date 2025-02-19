<?php
session_start();
require_once '../conexao/conexao_bd.php';
include '../admin/cabecalho/header.php'; 

$mensagem = '';
$mensagem_tipo = ''; // 'sucesso' ou 'erro'

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Diretório de uploads
$upload_dir = __DIR__ . '/uploads/';

// Obter o ID do produto
if (!isset($_GET['id'])) {
    header('Location: produtos.php');
    exit;
}
$id = $_GET['id'];

// Buscar os dados do produto
$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

// Atualizar o produto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $status = $_POST['status'];
    $imagem_nome = $produto['imagem']; // Nome da imagem atual

    // Verificar se uma nova imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nova_imagem = uniqid() . '.' . $extensao; // Gera um nome único para o arquivo
        $caminho_completo = $upload_dir . $nova_imagem;

        // Verificar tipo de arquivo permitido
        $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($extensao), $tipos_permitidos)) {
            $mensagem = "Formato de imagem não permitido. Apenas JPG, PNG ou GIF são aceitos.";
            $mensagem_tipo = 'erro';
        } else {
            // Mover o novo arquivo
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
                // Remover a imagem antiga, se existir
                $imagem_antiga = $upload_dir . $produto['imagem'];
                if (file_exists($imagem_antiga)) {
                    unlink($imagem_antiga);
                }

                $imagem_nome = $nova_imagem; // Atualizar o nome da imagem no banco
            } else {
                $mensagem = "Erro ao mover o arquivo para o diretório de uploads.";
                $mensagem_tipo = 'erro';
            }
        }
    }

    // Atualizar o banco de dados
    $stmt = $conn->prepare("UPDATE produtos SET nome = :nome, preco = :preco, categoria_id = :categoria_id, status = :status, imagem = :imagem WHERE id = :id");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':preco', $preco);
    $stmt->bindParam(':categoria_id', $categoria_id);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':imagem', $imagem_nome);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $mensagem = "Produto atualizado com sucesso!";
        $mensagem_tipo = 'sucesso';
    } else {
        $mensagem = "Erro ao atualizar o produto.";
        $mensagem_tipo = 'erro';
    }
}

// Buscar categorias
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css"> <!-- Linkando o CSS -->
    <style>
        .notification {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #4CAF50; /* Cor para sucesso */
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

        .notification.erro {
            background-color: #f44336; /* Cor para erro */
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

    <div class="editar-produto">
        <div class="header-edit">
            <a href="produtos.php" class="back-icon"><i class="fas fa-arrow-left"></i></a> <!-- Ícone de voltar -->  
        </div>
        <h1>Editar Produto</h1>
        <form method="POST" enctype="multipart/form-data">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required><br>
            
            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($produto['preco']) ?>" required><br>
            
            <label>Categoria:</label>
            <select name="categoria_id" required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>" <?= $categoria['id'] == $produto['categoria_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br>
            
            <label>Status:</label>
            <select name="status" required>
                <option value="disponível" <?= $produto['status'] === 'disponível' ? 'selected' : '' ?>>Disponível</option>
                <option value="esgotado" <?= $produto['status'] === 'esgotado' ? 'selected' : '' ?>>Esgotado</option>
            </select><br>
            
            <label>Imagem Atual:</label><br>
            <div class="imagem-atual">
                <img src="uploads/<?= $produto['imagem'] ?>" alt="Imagem do Produto">
            </div><br>
            
            <label>Nova Imagem (opcional):</label>
            <input type="file" name="imagem" id="imagem-opcional"><br>
            
            <div class="image-preview">
                <img src="" id="img-preview" alt="Imagem opcional" style="display:none;">
                <button type="button" class="remove-image" style="display:none;" id="remove-image">×</button>
            </div>
            
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>

    <script src="../admin/assets/js/image.js"></script> <!-- Seu arquivo JS aqui -->
    <script>
        // Exibir a notificação por alguns segundos e redirecionar
        document.addEventListener('DOMContentLoaded', function () {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'flex';
                setTimeout(function () {
                    notification.style.display = 'none';
                    // Redirecionar para produtos.php
                    window.location.href = 'produtos.php';
                }, 0000); // 6 segundos
            }
        });
    </script>
</body>
</html>

<?php include '../admin/cabecalho/footer.php'; ?>
