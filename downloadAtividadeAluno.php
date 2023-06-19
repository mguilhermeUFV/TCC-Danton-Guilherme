<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "watkhf_root";
$password = "bancodedadostcc";
$dbname = "watkhf_tcc"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão foi estabelecida com sucesso
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Obtém o ID do arquivo a ser baixado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta o banco de dados para obter o arquivo
    $sql = "SELECT nomeArquivo, arquivo FROM tb_arquivos_importados WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();

    // Verifica se o arquivo existe no banco de dados
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nomeArquivo, $arquivo);
        $stmt->fetch();
        // Verifica a extensão do arquivo
        


        // Define os cabeçalhos HTTP para forçar o download do arquivo
        // Define os cabeçalhos HTTP para forçar o download do arquivo
        header("Content-Type: application/pdf");
        header("Content-Disposition: attachment; filename=\"" . $nomeArquivo . ".pdf\"");


        // Envia o conteúdo do arquivo para o cliente
        echo $arquivo;
    } else {
        echo "Arquivo não encontrado.";
    }

    // Fecha a conexão com o banco de dados
    $stmt->close();
    $conn->close();
} else {
    echo "ID do arquivo não fornecido.";
}
?>
