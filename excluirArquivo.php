<?php
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

// Verifica se o parâmetro de ID do arquivo foi passado na URL
if (isset($_GET["id"])) {
    $idArquivo = $_GET["id"];

    // Executa o comando SQL para excluir o arquivo
    $sqlExcluirArquivo = "DELETE FROM tb_arquivos_importados WHERE ID = $idArquivo";
    $resultExcluirArquivo = $conn->query($sqlExcluirArquivo);

    // Verifica se a exclusão foi bem-sucedida
    if ($conn->affected_rows > 0) {
        echo "Arquivo excluído com sucesso.";
    } else {
        echo "Erro ao excluir o arquivo.";
    }
}
header("Location: index.php");
// Fecha a conexão com o banco de dados
$conn->close();
?>
