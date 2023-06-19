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

// Verifica se o semestre foi passado como parâmetro na URL
if (isset($_GET["semestre"])) {
    $semestre = $_GET["semestre"];

    // Exclui os alunos desse semestre da tabela "tb_login"
    $sql = "DELETE FROM tb_login WHERE IDAluno IN (SELECT IDAluno FROM tb_alunos_semestre WHERE semestre = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $semestre);
    $stmt->execute();

    // Atualiza o status dos alunos desse semestre para "Finalizado" na tabela "tb_alunos_semestre"
    $sql = "UPDATE tb_alunos_semestre SET status = 'Finalizado' WHERE semestre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $semestre);
    $stmt->execute();

    // Verifica se a exclusão foi bem-sucedida
    if ($stmt->affected_rows > 0) {
        echo "Semestre finalizado com sucesso. Os alunos desse semestre foram removidos da tabela de login.";
    } else {
        echo "Erro ao finalizar o semestre. Nenhum aluno foi removido da tabela de login.";
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    //$conn->close();
    header("Location: ListaAlunos.php?semestre=" . urlencode($semestre));
} else {
    echo "Semestre inválido.";
}
?>
