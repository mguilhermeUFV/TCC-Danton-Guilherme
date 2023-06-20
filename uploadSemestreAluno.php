<?php
// Conecta ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tcc";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica se o arquivo CSV foi enviado
if (isset($_FILES["csvFile"]) && !empty($_FILES["csvFile"]["tmp_name"])) {
    // Verifica se houve algum erro no upload do arquivo
    if ($_FILES["csvFile"]["error"] > 0) {
        $message = "Erro no upload do arquivo: " . $_FILES["csvFile"]["error"];

        // Redireciona para a página de cadastro com a mensagem de erro
        header("Location: cadastroSemestre.php?message=" . urlencode($message));
        exit();
    } else {
        // Lê o conteúdo do arquivo CSV
        $file = $_FILES["csvFile"]["tmp_name"];
        $handle = fopen($file, "r");

        // Ignorar a primeira linha (cabeçalho)
        $header = fgetcsv($handle, 1000, ",");

        // Prepara a query para inserção dos dados na tabela tb_alunos_semestre
        $stmt = $conn->prepare("INSERT INTO tb_alunos_semestre (matricula, nome, status, semestre) VALUES (?, ?, ?, ?)");

        // Prepara a query para inserção dos dados na tabela tb_login
        $stmtLogin = $conn->prepare("INSERT INTO tb_login (matricula, senha, IDAluno) VALUES (?, ?, ?)");

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            // Extrai as colunas do arquivo CSV
            $servername = "localhost";
            $username = "danton_root";
            $password = "tcchorasmais";
            $dbname = "danton_tcc";

            // Executa a query de inserção na tabela tb_alunos_semestre
            $stmt->bind_param("ssss", $matricula, $nome, $status, $semestre);
            $stmt->execute();

            // Verifica se ocorreu uma inserção bem-sucedida na tabela tb_alunos_semestre
            if ($stmt->affected_rows > 0) {
                // Obtém o ID do aluno inserido
                $idAluno = $stmt->insert_id;

                // Gera a senha para o aluno (iniciais do nome + matrícula) e criptografa usando MD5
                $senha = md5(strtoupper(substr($nome, 0, 2)) . $matricula);

                // Executa a query de inserção na tabela tb_login
                $stmtLogin->bind_param("ssi", $matricula, $senha, $idAluno);
                $stmtLogin->execute();
            }
        }

        fclose($handle);

        // Verifica se ocorreram inserções no banco de dados
        if ($stmt->affected_rows > 0) {
            $message = "Alunos cadastrados com sucesso";
        } else {
            $message = "Erro ao cadastrar alunos";
        }

        // Redireciona para a página de cadastro com uma mensagem de sucesso ou erro
        header("Location: cadastroSemestre.php?message=" . urlencode($message));
        exit();
    }
}

// Fecha as declarações preparadas
$stmt->close();
$stmtLogin->close();

// Fecha a conexão com o banco de dados
$conn->close();
?>
