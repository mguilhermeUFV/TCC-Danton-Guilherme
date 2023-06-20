<!DOCTYPE HTML>
<html>
<head>
    <title>Avaliar Aluno</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/avaliarAluno.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // Verifica se o ID do aluno foi passado como parâmetro na URL
        if (!isset($_GET["id"])) {
            // ID do aluno não está presente, redireciona para a página anterior
            header("Location: indexAdm.php");
            exit();
        }

        if (isset($_SESSION["semestre"])) {
            $semestre = $_SESSION["semestre"];
        } else {
            // Semestre não definido, redireciona de volta para a página 1
            header("Location: listaAlunos.php");
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

        // Recupera o ID do aluno passado como parâmetro na URL
        $alunoID = $_GET["id"];

        // Obtém as informações do aluno com base no ID
        $query = "SELECT * FROM tb_alunos_semestre WHERE IDAluno = '$alunoID'";
        $result = $conn->query($query);

        // Verifica se o aluno existe
        if ($result->num_rows == 0) {
            // Aluno não encontrado, redireciona para a página anterior
            header("Location: indexAdm.php");
            exit();
        }

        // Obtém os dados do aluno
        $aluno = $result->fetch_assoc();
        $alunoNome = $aluno["nome"];

        // Exibe as informações do aluno
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
        <a href="listaAlunos.php?semestre=<?php echo urlencode($_SESSION['semestre']); ?>" class="button voltar">Voltar</a>
        <h1 class="titulo">Avaliar Aluno</h1>
    </div>

    <hr>
    <div class="tabela">
    <h2><?php echo $aluno["matricula"]; ?> - <?php echo $alunoNome; ?></h2>
    <h2>Atividades cadastradas:</h2>
    <p>Suas alterações são salvas automaticamente.</p>
        <table>
            <tr>
                <th>Codigo</th>
                <th>Atividade</th>
                <th>Horas</th>
                <th>Contabilizado</th>
                <th>Avaliação</th>
            </tr>
            <?php
            // Obtém as atividades do aluno com base no ID
            //$query = "SELECT * FROM tb_arquivos_importados JOIN tb_alunos_semestre WHERE IDAluno = '$alunoID' ORDER BY codigoArquivo";
            $query = "SELECT *,( SELECT SUM(totalContabilizado) FROM tb_arquivos_importados WHERE codigoArquivo = t.codigoArquivo AND IDAluno = $alunoID) AS soma FROM tb_arquivos_importados t WHERE IDAluno = $alunoID ORDER BY codigoArquivo";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $atividadeID = $row["ID"];
                    $totalContabilizado = $row["totalContabilizado"];
                    $statusArquivo = $row["statusArquivo"];
                    $codigoArquivo = $row["codigoArquivo"];
                    $soma = $row["soma"];

                    echo "<tr>";
                    echo "<td>" . $row["codigoArquivo"] . "</td>";
                    echo "<td><a href='downloadAtividadeAluno.php?id=$atividadeID'>" . $row["nomeArquivo"] . "</a></td>";
                    echo "<td>" . $row["cargaHoraria"] . "</td>";
                    echo "<td><input type='text' class='total-contabilizado' data-id='$codigoArquivo' data-atividadeid='$atividadeID' value='$totalContabilizado' data-soma='$soma'></td>";
                    echo "<td>";
                    echo "<select class='status' data-id='$atividadeID'>";
                    echo "<option value='Aprovado'" . ($statusArquivo == 'Aprovado' ? 'selected' : '') . ">Aprovado</option>";
                    echo "<option value='Reprovado'" . ($statusArquivo == 'Reprovado' ? 'selected' : '') . ">Reprovado</option>";
                    echo "<option value='Pendente'" . ($statusArquivo == 'Pendente' ? 'selected' : '') . ">Pendente</option>";
                    echo "</select>";
                    echo "</td>";
                    echo "</tr>";
                }
            }else {
                echo "<tr><td colspan='5'>Nenhuma atividade cadastrada.</td></tr>";
            }
            ?>
        </table>
    </div>

    <div class="tabela">
        <h2>Atividades aprovadas por categoria</h2>
        <table id="atividades-aprovadas">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Total Carga Horária</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>


    <script>
    $(document).ready(function() {
        // Evento de alteração do campo "Total Contabilizado"
        $(".total-contabilizado").change(function() {
            var codigoArquivo = $(this).data("id");
            var totalContabilizado = $(this).val();
            var atividadeID = $(this).data("atividadeid");
            var soma = $(this).data('soma');
            // Chamar a função para atualizar o total contabilizado
            atualizarTotalContabilizado(codigoArquivo, totalContabilizado, atividadeID,soma);
        });

        // Atualizar o status via AJAX
        $(".status").change(function() {
                var atividadeID = $(this).data("id");
                var status = $(this).val();

                $.ajax({
                    url: "atualizarStatus.php",
                    method: "POST",
                    data: { atividadeID: atividadeID, status: status },
                    success: function(response) {
                        console.log(response);
                        // Exibir mensagem de sucesso ou atualizar a tabela, se necessário
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // Exibir mensagem de erro, se necessário
                    }
                });
        });

        // Função para atualizar o total contabilizado
        function atualizarTotalContabilizado(codigoArquivo, totalContabilizado, atividadeID,soma) {
            // Realiza a requisição AJAX
            $.ajax({
                url: "buscarMaximoAtividade.php",
                method: "POST",
                data: {
                    codigoArquivo: codigoArquivo
                },
                success: function(response) {
                    alert(response);
                    var values = response.split(",");
                    var maximoAtividade = parseInt(values[0]);
                    var maximoLimite = parseInt(values[1]);
                    alert("maximoAtividade"+maximoAtividade);
                    alert("maximoLimite"+maximoLimite);
                    if (!isNaN(maximoAtividade)) {
                        soma = parseInt(soma) + parseInt(totalContabilizado);
                        alert("soma"+soma);
                        if(totalContabilizado > maximoAtividade || soma > maximoLimite){
                            alert("O valor máximo para essa atividade é de: " + Math.abs(soma - maximoAtividade - totalContabilizado));
                            location.reload();
                        }
                        else{
                            $.ajax({
                                url: "atualizarTotalContabilizado.php",
                                method: "POST",
                                data: {
                                    codigoArquivo: codigoArquivo,
                                    totalContabilizado: totalContabilizado,
                                    atividadeID: atividadeID
                                },
                                success: function(response) {
                                    location.reload();
                                    // Trata a resposta da requisição
                                    if (response === "success") {
                                        // Total contabilizado atualizado com sucesso
                                        console.log("Total contabilizado atualizado para a atividade " + codigoArquivo);
                                    } else {
                                        // Erro ao atualizar o total contabilizado
                                        console.log("Erro ao atualizar o total contabilizado para a atividade " + codigoArquivo);
                                    }
                                }
                            });
                        }
                    } else {
                        // O valor máximo não pôde ser obtido
                        console.log("Erro ao buscar o valor máximo para a atividade " + codigoArquivo);
                    }
                }
            });
        }
    });
</script>


    <script>
        // Função para atualizar a tabela com os dados recebidos
        function atualizarTabela(atividades) {
            var tbody = document.getElementById("atividades-aprovadas").getElementsByTagName("tbody")[0];
            tbody.innerHTML = "";

            if (atividades.length > 0) {
                for (var i = 0; i < atividades.length; i++) {
                    var atividade = atividades[i];

                    var row = document.createElement("tr");
                    var codigoCell = document.createElement("td");
                    var cargaHorariaCell = document.createElement("td");

                    codigoCell.textContent = atividade.codigoArquivo;
                    cargaHorariaCell.textContent = atividade.totalCargaHoraria;

                    row.appendChild(codigoCell);
                    row.appendChild(cargaHorariaCell);

                    tbody.appendChild(row);
                }
            } else {
                var row = document.createElement("tr");
                var messageCell = document.createElement("td");
                messageCell.setAttribute("colspan", "2");
                messageCell.textContent = "Nenhuma atividade aprovada por categoria.";

                row.appendChild(messageCell);
                tbody.appendChild(row);
            }
        }

        // Função para fazer a requisição AJAX e atualizar a tabela
        function atualizarTabelaAutomaticamente() {
            var alunoID = "<?php echo $alunoID; ?>";

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    var atividades = JSON.parse(this.responseText);
                    atualizarTabela(atividades);
                }
            };
            xhttp.open("GET", "obterAtividadesAprovadas.php?id=" + alunoID, true);
            xhttp.send();
        }

        // Chama a função de atualização inicialmente e a cada 1 segundos
        atualizarTabelaAutomaticamente();
    </script>

</body>
</html>