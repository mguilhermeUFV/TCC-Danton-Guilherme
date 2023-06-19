<!DOCTYPE HTML>
<html>
    <head>
        <title>Cadastro de Atividades</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/cadastroAtividades.css">

        <style>
            .message-container {
                display: none;
                margin-top: 20px;
                padding: 10px;
                background-color: #eaf1ff;
                border: 1px solid black;
                border-radius: 4px;
                color: black;
                font-size: 16px;
                font-weight: bold;
                text-align: center;
                width: 50%; /* Define a largura desejada */
                margin: 2px auto; /* Centraliza horizontalmente */
            }
        </style>
    </head>
    <body>
        <?php
            session_start();

            // Verifica se o usuário está logado
            if (!isset($_SESSION["id"])) {
                header("Location: login.php");
                exit();
            }

            // Conexão com o banco de dados
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "tcc";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica se houve erro na conexão
            if ($conn->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conn->connect_error);
            }

            // Obtém as atividades cadastradas pelo aluno
            $alunoId = $_SESSION["id"];
            $matricula = $_SESSION["matricula"];
            
            $sqlCadastradas = "SELECT codigoArquivo, nomeArquivo, cargaHoraria, totalContabilizado, statusArquivo,ID FROM tb_arquivos_importados WHERE IDAluno = $alunoId";
            $resultCadastradas = $conn->query($sqlCadastradas);

            // Obtém as atividades aprovadas agrupadas por categoria

            // Obtém o nome do aluno logado
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
            <a href="index.php" class="button voltar">Voltar</a>
            <h1 class="titulo">Cadastro de atividades complementares</h1>
        </div>
        <hr>
        <div class="form">
            <form method="POST" action="upload.php" enctype="multipart/form-data">
                <h2>Formulário de cadastro</h2>
                <label for="atividade">Categoria da atividade:</label>
                <select id="atividade" name="atividade">
                    <option value="AE001">AE001 - Cursos e minicursos de extensão (presencial ou à distância) realizados</option>
                    <option value="AE002">AE002 - Curso, minicursos e palestras ministrados</option>
                    <option value="AE003">AE003 - Participação em congressos, seminários ou outros eventos, sem apresentação de trabalho</option>
                    <option value="AE004">AE004 - Participação em congressos, seminários ou outros eventos, com apresentação de trabalho</option>
                    <option value="AE005">AE005 - Participação na organização de eventos acadêmicos e científicos</option>
                    <option value="AE006">AE006 - Publicação de artigo completo</option>
                    <option value="AE007">AE007 - Publicação de artigo curto (shortpaper), resumo</option>
                    <option value="AE008">AE008 - Estágio não obrigatório</option>
                    <option value="AE009">AE009 - Participação na Empresa Júnior do curso de Sistemas de Informação (InfoAlto)</option>
                    <option value="AE010">AE010 - Trabalho com carteira assinada na área de Sistemas de Informação e afins</option>
                    <option value="AE011">AE011 - Monitoria</option>
                    <option value="AE012">AE012 - Atividade de iniciação cientifica</option>
                    <option value="AE013">AE013 - Atividade de iniciação à extensão</option>
                    <option value="AE014">AE014 - Viagens de estudo/visita técnica</option>
                    <option value="AE015">AE015 - Prestação de serviços relevantes à comunidade</option>
                    <option value="AE016">AE016 - Atividades culturais e esportivas</option>
                    <option value="AE017">AE017 - Casos Omissos</option>
                </select><br><br>

                <label for="nome">Nome da atividade:</label>
                <input type="text" id="nome" name="nome"><br><br>

                <label for="carga">Carga horária:</label>
                <input type="number" id="carga" name="carga"><br><br>
                
                <label for="pdfFile">Comprovante:</label>
                <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" class="file-input"/>

                <input class="button" type="submit" value="Enviar">
                <div class="message-container">
                    <p id="message-text"></p>
                </div>
            </form>
        </div>
        
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const messageContainer = document.querySelector('.message-container');
                const messageText = document.getElementById('message-text');

                const urlParams = new URLSearchParams(window.location.search);
                const message = urlParams.get('message');

                if (message) {
                    messageText.textContent = decodeURIComponent(message);
                    messageContainer.style.display = 'block';
                }
                if (message.startsWith('Erro')) {
                    messageContainer.style.backgroundColor = '#FFCCCC'; // Tom de vermelho para mensagens de erro
                } else {
                    messageContainer.style.backgroundColor = '#CCFFCC'; // Tom de verde para mensagens de envio bem-sucedido
                }
            });
        </script>
    </body>
</html>
