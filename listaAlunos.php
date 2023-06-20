<!DOCTYPE HTML>
<html>
<head>
    <title>Alunos do semestre</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/listaAlunos.css">
</head>
<body>
    <?php
        // Inicia a sessão do usuário
        session_start();

        // Verifica se o usuário está logado
        if (!isset($_SESSION["id"])) {
            // O usuário não está logado, redireciona para a página de login
            header("Location: login.php");
            exit();
        }

        // Conexão com o banco de dados
        $servername = "localhost";
        $username = "danton_root";
        $password = "tcchorasmais";
        $dbname = "danton_tcc";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verifica se houve erro na conexão
        if ($conn->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conn->connect_error);
        }

        // Verifica se o arquivo CSV foi enviado e inserido no banco de dados com sucesso
        if (isset($_GET["message"])) {
            $message = $_GET["message"];
            echo "<p>$message</p>";
        }

        // Recupera o semestre selecionado
        $semestre = isset($_GET["semestre"]) ? $_GET["semestre"] : "";

        // Recupera os dados da tabela tb_alunos_semestre do banco de dados com base no semestre selecionado
        $query = "SELECT * FROM tb_alunos_semestre";
        
        if (!empty($semestre)) {
            $query .= " WHERE semestre = '$semestre'";
        }
        $query .= " ORDER BY matricula";
        $result = $conn->query($query);

        // Verifica se o semestre está finalizado
        $semestreFinalizado = false;
        if (!empty($semestre)) {
            $sqlSemestre = "SELECT status FROM tb_alunos_semestre WHERE semestre = '$semestre' LIMIT 1";
            $resultSemestre = $conn->query($sqlSemestre);
            $rowSemestre = $resultSemestre->fetch_assoc();
            if ($rowSemestre && $rowSemestre["status"] == "Finalizado") {
                $semestreFinalizado = true;
            }
        }
    ?>

    <nav class="navbar">
        <div class="navbar-left">
            <span class="navbar-brand">Horas+</span>
        </div>
        <div class="navbar-right">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="indexAdm.php" class="nav-link">Página Inicial</a>
                </li>
                <li class="nav-item">
                    <a href="perfil.php" class="nav-link">Coordenador</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Sair</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="topo">
        <a href="indexAdm.php" class="button voltar">Voltar</a>
        <h1 class="titulo">Lista de Alunos</h1>
        <a href="cadastroSemestre.php" class="button cadastro">Cadastrar Novo Semestre</a>
    </div>
    
    <hr>
    
    <div class="tabela">
    <h2><?php echo "Semestre: " . htmlspecialchars($_GET["semestre"]); ?></h2>
    <p>Suas alterações são salvas automaticamente.</p>
    <?php
        $semestre = isset($_GET["semestre"]) ? $_GET["semestre"] : "";
        $_SESSION["semestre"] = $semestre;
    ?>
         <table>
        <tr>
            <th>Matrícula</th>
            <th>Nome</th>
            <th>Status</th>
            <th>Conceito</th>
        </tr>
        <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["matricula"] . "</td>";
                    echo "<td><a href='avaliarAluno.php?id=" . $row["IDAluno"] . "'>" . $row["nome"] . "</a></td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>";
                    echo "<select name='conceito' onchange='atualizarConceito(" . $row["IDAluno"] . ", this.value)'>";
                    echo "<option value='Q' " . ($row['conceito'] == 'Q' ? 'selected' : '') . ">Q</option>";
                    echo "<option value='S' " . ($row['conceito'] == 'S' ? 'selected' : '') . ">S</option>";
                    echo "<option value='I' " . ($row['conceito'] == 'I' ? 'selected' : '') . ">I</option>";
                    echo "</select>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        ?>
    </table>
    </div>

    <?php if (!$semestreFinalizado): ?>
        <div class="finalizar-semestre">
            <a href="finalizarSemestre.php?semestre=<?php echo $_SESSION['semestre']; ?>" class="button" onclick="return confirm('Tem certeza? Ao finalizar o semestre, os alunos desse semestre perderão o acesso ao sistema.')">Finalizar Semestre</a>
        </div>
    <?php endif; ?>

    <script>
        // Função para atualizar o conceito do aluno usando Ajax
        function atualizarConceito(alunoID, conceito) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (response.status === "success") {
                        // Atualização bem-sucedida
                    } else {
                        // Erro ao atualizar o conceito, exibe mensagem de erro
                        console.log("Erro ao atualizar o conceito: " + response.message);
                    }
                }
            };
            xhttp.open("POST", "atualizarConceito.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("aluno_id=" + alunoID + "&conceito=" + conceito);
        }
    </script>

</body>
</html>
