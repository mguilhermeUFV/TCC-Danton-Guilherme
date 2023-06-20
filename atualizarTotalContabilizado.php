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
    $totalContabilizado = $_POST["totalContabilizado"];

    // Atualiza o valor do campo totalContabilizado no banco de dados
    $query = "UPDATE tb_arquivos_importados SET totalContabilizado = '$totalContabilizado' WHERE ID = '$atividadeID'";

    if ($conn->query($query) === TRUE) {
        echo "Total contabilizado atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o total contabilizado: " . $conn->error;
    }
} else {
    echo "Requisição inválida!";
}

$conn->close();
?>
