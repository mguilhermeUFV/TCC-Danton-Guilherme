<?php
// Conecta ao banco de dados
$servername = "localhost";
$username = "danton_root";
$password = "tcchorasmais";
$dbname = "danton_tcc";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verifica se a matrícula e a senha foram enviadas pelo formulário
if (isset($_POST["senha"])) {
    // Verifica se a senha atual está correta
    session_start();
    $idAluno = $_SESSION["id"];
    $senhaAtual = $_POST["senha"];

    // Consulta o banco de dados para obter a senha atual do aluno
    $sql = "SELECT senha FROM tb_login WHERE IDAluno = $idAluno";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $senhaDoAluno = $row["senha"];

        if (md5($senhaAtual) != $senhaDoAluno) {
            // Redireciona com mensagem de erro
            header("Location: perfil.php?message=" . urlencode("Senha atual incorreta. Por favor, tente novamente."));
            exit();
        } else {
            $novaSenha = $_POST["nova-senha"];
            $confirmarSenha = $_POST["confirmar-senha"];

            if ($novaSenha != $confirmarSenha) {
                // Redireciona com mensagem de erro
                header("Location: perfil.php?message=" . urlencode("As novas senhas não coincidem. Por favor, tente novamente."));
                exit();
            } else {
                // Criptografar a nova senha usando MD5
                $senhaCriptografada = md5($novaSenha);

                // Atualizar a senha no banco de dados
                $sql = "UPDATE tb_login SET senha = '$senhaCriptografada' WHERE IDAluno = $idAluno";
                if ($conn->query($sql) === TRUE) {
                    // Redireciona com mensagem de sucesso
                    header("Location: perfil.php?message=" . urlencode("Senha alterada com sucesso."));
                    exit();
                } else {
                    // Redireciona com mensagem de erro
                    header("Location: perfil.php?message=" . urlencode("Erro ao atualizar a senha: " . $conn->error));
                    exit();
                }
            }
        }
    } else {
        // Redireciona com mensagem de erro
        header("Location: perfil.php?message=" . urlencode("Erro ao buscar a senha atual do aluno."));
        exit();
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
