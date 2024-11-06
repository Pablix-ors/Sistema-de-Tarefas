<?php
// Inicia a sessão para lidar com as mensagens de sucesso
session_start();

// Conexão com o banco de dados
include 'conecta.php';

// Verifica se o id da tarefa foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Buscar a tarefa a ser editada
    try {
        $sql = "SELECT * FROM tarefas WHERE tar_codigo = :id"; // Alterei 'id' para 'tar_codigo'
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Se a tarefa não for encontrada, redireciona para a lista de tarefas
        if (!$tarefa) {
            $_SESSION['mensagem_erro'] = "Tarefa não encontrada!";
            header("Location: editar_tarefa.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro ao buscar tarefa: " . $e->getMessage();
    }
}

// Verifica se o formulário de edição foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $setor = $_POST['setor'];
    $prioridade = $_POST['prioridade'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    // Atualiza os dados da tarefa no banco
    try {
        $sql = "UPDATE tarefas SET tar_setor = :setor, tar_prioridade = :prioridade, tar_descriçao = :descricao, tar_status = :status WHERE tar_codigo = :id"; // Alterei 'id' para 'tar_codigo'
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':setor', $setor);
        $stmt->bindParam(':prioridade', $prioridade);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $_SESSION['mensagem_sucesso'] = "Tarefa atualizada com sucesso!";
        header("Location: editar_tarefa.php");
        exit();
    } catch (PDOException $e) {
        echo "Erro ao atualizar tarefa: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para o formulário de edição */
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-submit:hover {
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

    <h1>Editar Tarefa</h1>

    <!-- Exibe a mensagem de sucesso ou erro, se existir -->
    <?php
    if (isset($_SESSION['mensagem_sucesso'])) {
        echo "<p>" . $_SESSION['mensagem_sucesso'] . "</p>";
        unset($_SESSION['mensagem_sucesso']);
    }
    if (isset($_SESSION['mensagem_erro'])) {
        echo "<p>" . $_SESSION['mensagem_erro'] . "</p>";
        unset($_SESSION['mensagem_erro']);
    }
    ?>

    <form action="editar_tarefa.php?id=<?php echo $tarefa['tar_codigo']; ?>" method="POST"> <!-- Alterei 'id' para 'tar_codigo' -->
        <label for="setor">Setor:</label>
        <input type="text" id="setor" name="setor" value="<?php echo htmlspecialchars($tarefa['tar_setor']); ?>" required>

        <label for="prioridade">Prioridade:</label>
        <select id="prioridade" name="prioridade" required>
            <option value="Baixa" <?php if ($tarefa['tar_prioridade'] == 'Baixa') echo 'selected'; ?>>Baixa</option>
            <option value="Média" <?php if ($tarefa['tar_prioridade'] == 'Média') echo 'selected'; ?>>Média</option>
            <option value="Alta" <?php if ($tarefa['tar_prioridade'] == 'Alta') echo 'selected'; ?>>Alta</option>
        </select>

        <label for="descricao">Descrição:</label>
        <textarea id="descricao" name="descricao" required><?php echo htmlspecialchars($tarefa['tar_descriçao']); ?></textarea>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Em andamento" <?php if ($tarefa['tar_status'] == 'Em andamento') echo 'selected'; ?>>Em andamento</option>
            <option value="Concluída" <?php if ($tarefa['tar_status'] == 'Concluída') echo 'selected'; ?>>Concluída</option>
            <option value="Pendente" <?php if ($tarefa['tar_status'] == 'Pendente') echo 'selected'; ?>>Pendente</option>
        </select>

        <button type="submit" class="btn-submit">Atualizar Tarefa</button>
    </form>

    <a href="index.html" class="btn-voltar">Voltar à Lista de Tarefas</a>

</body>
</html>
