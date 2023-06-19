<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tcc";

    // Obtém o valor do ID da atividade enviado via POST
    $codigoArquivo = $_POST['codigoArquivo'];

    // Conecta ao banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Obtém o valor de maximoAtividade para a atividade com o ID correspondente
    $query = "SELECT maximoAtividade, maximoLimite FROM tb_atividades WHERE codigo = '$codigoArquivo'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maximoAtividade = $row["maximoAtividade"];
        $maximoLimite = $row["maximoLimite"];
        echo $maximoAtividade . ',' . $maximoLimite;
    } else {
        echo "0,0";
    }
    $conn->close();
?>
