<?php
session_start();
require_once '../conexao/conexao_bd.php';
include '../cabecalho/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['admin'] = $username;
        header('Location: index.php');
        exit;
    } else {
        $error = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <style>
        /* Estilos para a página de Login */
        .login-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width:80%;
            max-width: 400px;
            text-align: center;
            justify-content: center;
            margin: 60px auto;  /* Adicionando margem em cima e em baixo */
            display: flex;
            flex-direction: column; /* Adicionando a direção de flex */
            flex-grow: 1; /* Ajuste aqui para garantir que cresça adequadamente */
        }

        /* Estilo dos campos de input */
        .login-form input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f5f5f5;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .login-form input:focus {
            border: 1px solid #5c6bc0; /* Cor mais suave para o foco */
            box-shadow: 0 0 5px rgba(92, 107, 192, 0.5); /* Melhor destaque de foco */
            outline: none;
        }

        /* Estilo do botão "Entrar" */
        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #555;
        }

        /* Estilo para a mensagem de erro */
        .error-message {
            color: red;
            font-size: 1rem;
            margin-top: 10px;
            font-weight: bold;
        }

        /* Responsividade para Login */
        @media (max-width: 600px) {
            .login-container {
                padding: 30px;
                max-width: 90%;
            }

            .login-container h1 {
                font-size: 2rem;
            }

            .login-form input {
                font-size: 0.9rem;
            }

            .login-form button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form method="POST" class="login-form">
            <h1>Login</h1>
            <input type="text" name="username" placeholder="Usuário" required>
            <input type="password" name="password" placeholder="Senha" required>
            <button type="submit">Entrar</button>
            <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>

<?php include '../admin/cabecalho/footer.php'; ?>
