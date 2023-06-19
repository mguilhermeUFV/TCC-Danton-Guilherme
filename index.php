<!DOCTYPE HTML>
<html>
    <head>
        <title>Lista de Atividades</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/index.css">
        <style>
            
        </style>
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
            $username = "watkhf_root";
            $password = "bancodedadostcc";
            $dbname = "watkhf_tcc";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica se houve erro na conexão
            if ($conn->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            $alunoId = $_SESSION["id"];
            $matricula = $_SESSION["matricula"];
            $sqlCadastradas = "SELECT codigoArquivo, nomeArquivo, cargaHoraria, totalContabilizado, statusArquivo, ID FROM tb_arquivos_importados WHERE IDAluno = $alunoId ORDER BY codigoArquivo";
            $resultCadastradas = $conn->query($sqlCadastradas);
            
            $sqlNomeAluno = "SELECT nome FROM tb_alunos_semestre WHERE matricula = $matricula";
            $resultNomeAluno = $conn->query($sqlNomeAluno);

            // Verifica se há resultados
            if ($resultNomeAluno->num_rows > 0) {
                // Obtém o nome do aluno
                $rowNomeAluno = $resultNomeAluno->fetch_assoc();
                $nomeAluno = $rowNomeAluno["nome"];
            } else {
                // Nome do aluno não encontrado
                $nomeAluno = "Nao achei o nome";
            }
        ?>

        <nav class="navbar">
            <div class="navbar-left">
                <span class="navbar-brand">Horas+</span>
            </div>
            <div class="navbar-right">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Página Inicial</a>
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
            <h1 class="titulo">Lista de Atividades Complementares</h1>
            <a href="cadastroAtividades.php" class="button cadastro">Cadastrar Atividade</a>
        </div>
        <hr>
        <div class="tabela">
            <h2>Atividades cadastradas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome da Atividade</th>
                        <th>Carga Horária</th>
                        <th>Total Contabilizado</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($resultCadastradas->num_rows > 0) {
                        while ($row = $resultCadastradas->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["codigoArquivo"] . "</td>";

                            echo "<td>";
                            echo "<div class='opcoes-flutuantes'>";
                            echo "<span onclick=\"location.href='downloadAtividadeAluno.php?id=" . $row["ID"] . "'\">" . $row["nomeArquivo"] . "</span>";
                            echo "<div class='opcoes'>";
                            echo "<a href='excluirArquivo.php?id=" . $row["ID"] . "'>Excluir</a>";
                            echo "<a href='downloadAtividadeAluno.php?id=" . $row["ID"] . "'>Download</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</td>";

                            echo "<td>" . $row["cargaHoraria"] . "</td>";
                            echo "<td>" . $row["totalContabilizado"] . "</td>";
                            echo "<td>" . $row["statusArquivo"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>Nenhuma atividade cadastrada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="tabela">
            <h2>Atividades aprovadas por categoria</h2>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Total Carga Horária</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlAprovadasPorCategoria = "SELECT codigoArquivo, SUM(totalContabilizado) AS totalCargaHoraria FROM tb_arquivos_importados WHERE statusArquivo = 'Aprovado' AND IDAluno = $alunoId GROUP BY codigoArquivo ORDER BY codigoArquivo";
                    $resultAprovadasPorCategoria = $conn->query($sqlAprovadasPorCategoria);

                    if ($resultAprovadasPorCategoria->num_rows > 0) {
                        while ($row = $resultAprovadasPorCategoria->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["codigoArquivo"] . "</td>";
                            echo "<td>" . $row["totalCargaHoraria"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Nenhuma atividade aprovada por categoria.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>


    </body>
</html>
