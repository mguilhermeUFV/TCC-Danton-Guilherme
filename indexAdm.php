<DOCTYPE HTML>
<html>
    <head>
        <title>Semestres</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/indexAdm.css">
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

            // Recupera os dados da tabela tb_alunos_semestre do banco de dados
            $query = "SELECT * FROM tb_alunos_semestre";
            $result = $conn->query($query);
            
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
            <h1 class="titulo">Lista de Semestres</h1>
            <a href="cadastroSemestre.php" class="button cadastro">Cadastrar Novo Semestre</a>
        </div>

        <hr>

        <div class="dropdown">
            <h2>Escolha o semestre</h2>
            <select class="dropbtn" onchange="redirecionar(this)">
                <option value="">Semestre</option>
                <?php
                // Recupera os semestres disponíveis do banco de dados
                $query_semestres = "SELECT DISTINCT semestre FROM tb_alunos_semestre";
                $result_semestres = $conn->query($query_semestres);

                // Exibe as opções do dropdown com base nos semestres encontrados
                if ($result_semestres->num_rows > 0) {
                    while ($row_semestres = $result_semestres->fetch_assoc()) {
                        $semestre = $row_semestres["semestre"];
                        echo "<option value='" . $semestre . "'>" . $semestre . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <script>
        function redirecionar(dropdown) {
            var semestre = dropdown.value;
            if (semestre) {
                var url = "listaAlunos.php?semestre=" + encodeURIComponent(semestre);
                window.location.href = url;
            }
        }
        </script>



    </body>
</html>