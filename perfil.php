<!DOCTYPE HTML>
<html>
<head>
    <title>Perfil Aluno</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/perfil.css">
</head>
<body>
    <?php 
        // Conecta ao banco de dados
        $servername = "localhost";
        $username = "watkhf_root";
        $password = "bancodedadostcc";
        $dbname = "watkhf_tcc";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Busca o nome do aluno no banco de dados
        session_start();
        $idAluno = $_SESSION["id"];
        $sql = "SELECT nome, matricula FROM tb_alunos_semestre WHERE IDAluno = $idAluno";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nomeAluno = $row["nome"];
            $matriculaAluno = $row["matricula"];
        } else {
            $nomeAluno = "Nome do Aluno Indisponível";
            $matriculaAluno = "";
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
    ?>
    <nav class="navbar">
        <div class="navbar-left">
            <span class="navbar-brand">Horas+</span>
        </div>
        <div class="navbar-right">
            <ul class="navbar-nav">
                <li class="nav-item">
                <?php if ($matriculaAluno != "10000"): ?>
                    <a href="index.php" class="nav-link">Página Inicial</a>
                <?php else: ?>
                    <a href="indexAdm.php" class="nav-link">Página Inicial</a>
                <?php endif; ?>
                    
                </li>
                <li class="nav-item">
                    <a href="perfil.php" class="nav-link"><?php echo $nomeAluno; ?></a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="topo">
        <?php if ($matriculaAluno != "10000"): ?>
            <a href="index.php" class="button voltar">Voltar</a>
        <?php else: ?>
            <a href="indexAdm.php" class="button voltar">Voltar</a>
        <?php endif; ?>
        <h1 class="titulo">Perfil</h1>
    </div>
    <hr>
    <div class="container">
        <form method="POST" action="validarSenha.php">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $nomeAluno; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <div class="form-group">
                <label for="nova-senha">Nova senha:</label>
                <input type="password" id="nova-senha" name="nova-senha">
            </div>
            <div class="form-group">
                <label for="confirmar-senha">Confirmar nova senha:</label>
                <input type="password" id="confirmar-senha" name="confirmar-senha">
            </div>
            <div class="form-group">
                <button type="submit" class="button salvar" name="salvar">Salvar</button>
            </div>
        </form>
        <?php if (isset($_GET['message'])): ?>
            <?php
                $message = urldecode($_GET['message']);
                $messageClass = strpos($message, 'sucesso') !== false ? 'success' : 'error';
            ?>
            <div class="message-container <?php echo $messageClass; ?>">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const messageContainer = document.querySelector('.message-container');

            if (messageContainer.textContent !== '') {
                messageContainer.style.display = 'block';
            }
        });
    </script>
</body>
</html>
