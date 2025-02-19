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
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $preco = $_POST['preco'];
    $categoria_id = $_POST['categoria_id'];
    $status = isset($_POST['status']) ? ucfirst(strtolower($_POST['status'])) : 'Disponível'; // Ajusta para padrão correto
    $imagem_nome = '';

    // Depuração: verificar valores recebidos
    // var_dump($_POST); exit;

    // Upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . '.' . $extensao;
        $caminho_completo = $upload_dir . $imagem_nome;

        $tipos_permitidos = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($extensao), $tipos_permitidos)) {
            $mensagem = "Formato de imagem não permitido. Apenas JPG, PNG ou GIF são aceitos.";
            $mensagem_tipo = 'erro';
        } else {
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_completo)) {
                // Inserção no banco de dados
                $stmt = $conn->prepare("INSERT INTO produtos (nome, preco, imagem, status, categoria_id) 
                                        VALUES (:nome, :preco, :imagem, :status, :categoria_id)");
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':preco', $preco);
                $stmt->bindParam(':imagem', $imagem_nome);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':categoria_id', $categoria_id);
                
                if ($stmt->execute()) {
                    $mensagem = "Produto adicionado com sucesso!";
                    $mensagem_tipo = 'sucesso';
                } else {
                    $mensagem = "Erro ao adicionar produto no banco de dados.";
                    $mensagem_tipo = 'erro';
                }
            } else {
                $mensagem = "Erro ao mover a imagem para o diretório de uploads.";
                $mensagem_tipo = 'erro';
            }
        }
    } else {
        $mensagem = "Erro no upload da imagem.";
        $mensagem_tipo = 'erro';
    }
}

// Buscar categorias para o select
$categorias = $conn->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

  

    <div class="adicionar-produto">
        
         <?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?= ($mensagem_tipo === 'sucesso') ? 'success' : 'danger'; ?> alert-dismissible fade show text-center" role="alert" id="notification">
        <?= htmlspecialchars($mensagem); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

    
         <div class="header-edit">
            <a href="index.php" class="back-icon"><i class="fas fa-arrow-left"></i></a> <!-- Ícone de voltar -->  
        </div>
        <h1>Adicionar Produto</h1>
        <form method="POST" enctype="multipart/form-data">
            <label>Nome:</label>
            <input type="text" name="nome" required><br>

            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" required><br>

            <label>Categoria:</label>
            <select name="categoria_id" required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                <?php endforeach; ?>
            </select><br>

            <label>Status:</label>
            <select name="status" required>
                <option value="Disponível">Disponível</option>
                <option value="Esgotado">Esgotado</option>
            </select><br>

            <label>Imagem:</label>
            <input type="file" name="imagem" id="imagem-opcional" required><br>

            <button type="submit">Adicionar Produto</button>
        </form>
    </div>

    <script>
        // Notificação automática
        <?php if ($mensagem): ?>
        document.addEventListener('DOMContentLoaded', function () {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'block';
                setTimeout(() => { notification.style.display = 'none'; }, 5000);
            }
        });
        <?php endif; ?>
    </script>

</body>
</html>

<?php include '../admin/cabecalho/footer.php'; ?>
