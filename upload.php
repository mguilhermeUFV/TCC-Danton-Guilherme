<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $categoria = $_POST['atividade'];
    $nomeAtividade = $_POST['nome'];
    $cargaHoraria = $_POST['carga'];
    $alunoId = $_SESSION["id"];

    // Verifica se um arquivo foi enviado
    if (isset($_FILES['pdfFile'])) {
        $file = $_FILES['pdfFile'];

        // Verifica se não ocorreu um erro no upload do arquivo
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = 'Pendente';
            $tempFilePath = $file['tmp_name'];

            // Lê o conteúdo do arquivo como uma sequência de bytes
            $fileContent = file_get_contents($tempFilePath);

            // Conexão com o banco de dados
            $servername = "localhost";
            $username = "danton_root";
            $password = "tcchorasmais";
            $dbname = "danton_tcc";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verifica se a conexão foi estabelecida com sucesso
            if ($conn->connect_error) {
                die("Falha na conexão com o banco de dados: " . $conn->connect_error);
            }

            // Prepara a declaração de inserção com um parâmetro binário
            $sql = "INSERT INTO tb_arquivos_importados (codigoArquivo, nomeArquivo, cargaHoraria, statusArquivo, arquivo, IDAluno) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissi", $categoria, $nomeAtividade, $cargaHoraria, $fileName, $fileContent, $alunoId);
            $stmt->send_long_data(4, $fileContent); // Envia o parâmetro binário

            // Executa a declaração preparada
            if ($stmt->execute()) {
                $message = "Enviado!";
            } else {
                $message = "Erro ao inserir dados no banco de dados: " . $stmt->error;
            }

            // Fecha a conexão com o banco de dados
            $stmt->close();
            $conn->close();

            // Redireciona para a página de cadastro com a mensagem
            header("Location: cadastroAtividades.php?message=" . urlencode($message));
            exit();
        } else {
            $message = "Erro no upload do arquivo: " . $file['error'];

            // Redireciona para a página de cadastro com a mensagem de erro
            header("Location: cadastroAtividades.php?message=" . urlencode($message));
            exit();
        }
    } else {
        $message = "Nenhum arquivo foi enviado.";

        // Redireciona para a página de cadastro com a mensagem de erro
        header("Location: cadastroAtividades.php?message=" . urlencode($message));
        exit();
    }
}
?>
