<?php
// Inicia a sessão para lidar com as mensagens de sucesso
session_start();

// Conexão com o banco de dados
include 'conecta.php';

// Verifica se a exclusão foi solicitada e executa a exclusão
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    try {
        $sql = "DELETE FROM tarefas WHERE tar_codigo = :delete_id"; // Alterado para tar_codigo
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':delete_id', $delete_id);
        $stmt->execute();
        $_SESSION['mensagem_sucesso'] = "Tarefa excluída com sucesso!";
    } catch (PDOException $e) {
        echo "<p>Erro ao excluir tarefa: " . $e->getMessage() . "</p>";
    }
}

// Buscar todas as tarefas cadastradas
$tarefas = [];
try {
    $sql = "SELECT * FROM tarefas";
    $stmt = $pdo->query($sql);
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar tarefas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Tarefas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos para a tabela */
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
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .btn-edit {
            background-color: #FFA500;
        }
        .btn-delete {
            background-color: #FF0000;
        }
        .btn-delete:hover {
            background-color: #cc0000;
        }
        .btn-edit:hover {
            background-color: #e69500;
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
    <h1>Lista de Tarefas</h1>

    <!-- Exibe a mensagem de sucesso, se existir, e remove da sessão -->
    <?php
    if (isset($_SESSION['mensagem_sucesso'])) {
        echo "<p>" . $_SESSION['mensagem_sucesso'] . "</p>";
        unset($_SESSION['mensagem_sucesso']);
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Setor</th>
                <th>Prioridade</th>
                <th>Descrição</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tarefas)): ?>
                <tr>
                    <td colspan="6">Nenhuma tarefa encontrada.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tarefas as $tarefa): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tarefa['tar_codigo']); ?></td> <!-- Alterado para tar_codigo -->
                        <td><?php echo htmlspecialchars($tarefa['tar_setor']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['tar_prioridade']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['tar_descriçao']); ?></td>
                        <td><?php echo htmlspecialchars($tarefa['tar_status']); ?></td>
                        <td>
                            <a href="editar_tarefa.php?id=<?php echo $tarefa['tar_codigo']; ?>" class="btn btn-edit">Editar</a> <!-- Alterado para tar_codigo -->
                            <a href="?delete_id=<?php echo $tarefa['tar_codigo']; ?>" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">Excluir</a> <!-- Alterado para tar_codigo -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="index.html" class="btn-voltar">Voltar ao Index</a>
</body>
</html>
