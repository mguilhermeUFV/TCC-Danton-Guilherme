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

// Verifica se a requisição é do tipo POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtém os dados enviados via POST
    $atividadeID = $_POST["atividadeID"];
    $status = $_POST["status"];

    // Atualiza o valor do campo statusArquivo no banco de dados
    $query = "UPDATE tb_arquivos_importados SET statusArquivo = '$status' WHERE ID = '$atividadeID'";

    if ($conn->query($query) === TRUE) {
        echo "Status atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o status: " . $conn->error;
    }
} else {
    echo "Requisição inválida!";
}

$conn->close();
?>
