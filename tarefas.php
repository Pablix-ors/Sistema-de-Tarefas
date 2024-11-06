<?php
// Inicia a sessão para lidar com as mensagens de sucesso
session_start();

// Conexão com o banco de dados
include 'conecta.php';

// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usu_codigo'], $_POST['tar_setor'], $_POST['tar_prioridade'], $_POST['tar_descriçao'], $_POST['tar_status'])) {
    $usu_codigo = $_POST['usu_codigo'];
    $setor = $_POST['tar_setor'];
    $prioridade = $_POST['tar_prioridade'];
    $descricao = $_POST['tar_descriçao'];
    $status = $_POST['tar_status'];

    try {
        // Insere a tarefa no banco de dados com o usu_codigo (usuário selecionado)
        $sql = "INSERT INTO tarefas (usu_codigo, tar_setor, tar_prioridade, tar_descriçao, tar_status) VALUES (:usu_codigo, :setor, :prioridade, :descricao, :status)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usu_codigo', $usu_codigo);
        $stmt->bindParam(':setor', $setor);
        $stmt->bindParam(':prioridade', $prioridade);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        // Armazena uma mensagem de sucesso na sessão
        $_SESSION['mensagem_sucesso'] = "Tarefa cadastrada com sucesso!";
        
        // Redireciona para evitar a submissão duplicada ao recarregar
        header("Location: tarefas.php");
        exit();
    } catch (PDOException $e) {
        echo "<p>Erro ao cadastrar tarefa: " . $e->getMessage() . "</p>";
    }
}

// Buscar todos os usuários cadastrados
$usuarios = [];
try {
    $sql = "SELECT usu_codigo, usu_nome FROM usuarios";
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
    <title>Cadastrar Tarefa</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para a tabela de tarefas */
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
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
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
    <h1>Cadastrar Tarefa</h1>

    <!-- Exibe a mensagem de sucesso, se existir, e remove da sessão -->
    <?php
    if (isset($_SESSION['mensagem_sucesso'])) {
        echo "<p>" . $_SESSION['mensagem_sucesso'] . "</p>";
        unset($_SESSION['mensagem_sucesso']);
    }
    ?>

    <form action="tarefas.php" method="POST">
        <label for="usu_codigo">Selecione o Usuário:</label>
        <select id="usu_codigo" name="usu_codigo" required>
            <option value="">Selecione um usuário</option>
            <?php foreach ($usuarios as $usuario): ?>
                <option value="<?= $usuario['usu_codigo'] ?>"><?= htmlspecialchars($usuario['usu_nome']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="setor">Setor da Tarefa:</label>
        <input type="text" id="setor" name="tar_setor" required>

        <label for="prioridade">Prioridade da Tarefa:</label>
        <select id="prioridade" name="tar_prioridade" required>
            <option value="alta">Alta</option>
            <option value="media">Média</option>
            <option value="baixa">Baixa</option>
        </select>

        <label for="descricao">Descrição da Tarefa:</label>
        <input type="text" id="descricao" name="tar_descriçao" required>

        <label for="prioridade">Status da Tarefa:</label>
        <select id="prioridade" name="tar_status" required>
            <option value="a fazer">A fazer</option>
            <option value="fazendo">Fazendo</option>
            <option value="Pronto">Pronto</option>
        </select>

        <input type="submit" value="Cadastrar">
    </form>

    <a href="index.html" class="btn-voltar">Voltar ao Index</a>
</body>
</html>
