<?php
// Inicia a sessão para lidar com as mensagens de sucesso
session_start();

// Conexão com o banco de dados
include 'conecta.php';

// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usu_nome']) && isset($_POST['usu_email'])) {
    $nome = $_POST['usu_nome'];
    $email = $_POST['usu_email'];

    try {
        // Insere o usuário no banco de dados
        $sql = "INSERT INTO usuarios (usu_nome, usu_email) VALUES (:nome, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Armazena uma mensagem de sucesso na sessão
        $_SESSION['mensagem_sucesso'] = "Usuário cadastrado com sucesso!";
        
        // Redireciona para evitar a submissão duplicada ao recarregar
        header("Location: usuarios.php");
        exit();
    } catch (PDOException $e) {
        echo "<p>Erro ao cadastrar usuário: " . $e->getMessage() . "</p>";
    }
}

// Buscar todos os usuários cadastrados
$usuarios = [];
try {
    $sql = "SELECT * FROM usuarios";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar usuários: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para a tabela de usuários */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        /* Estilo para melhorar a aparência geral */
        h1, h2 {
            font-family: Arial, sans-serif;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        p {
            color: #4CAF50;
        }
        /* Estilo para o botão de voltar */
        .btn-voltar {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-voltar:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Cadastrar Usuário</h1>

    <!-- Exibe a mensagem de sucesso, se existir, e remove da sessão -->
    <?php
    if (isset($_SESSION['mensagem_sucesso'])) {
        echo "<p>" . $_SESSION['mensagem_sucesso'] . "</p>";
        unset($_SESSION['mensagem_sucesso']); // Remove a mensagem após exibir
    }
    ?>

    <form action="usuarios.php" method="POST">
        <label for="nome">Nome do Usuário:</label>
        <input type="text" id="nome" name="usu_nome" required>

        <label for="email">Email do Usuário:</label>
        <input type="email" id="email" name="usu_email" required>

        <input type="submit" value="Cadastrar">
    </form>

    <!-- Botão para voltar ao index -->
    <a href="index.html" class="btn-voltar">Voltar ao Index</a>

</body>
</html>
