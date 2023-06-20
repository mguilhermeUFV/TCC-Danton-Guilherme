<?php
// Verifica se a solicitação foi feita por método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica se os parâmetros esperados foram recebidos
    if (isset($_POST["aluno_id"]) && isset($_POST["conceito"])) {
        // Obtém os valores dos parâmetros
        $alunoID = $_POST["aluno_id"];
        $conceito = $_POST["conceito"];

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

        // Atualiza o conceito do aluno no banco de dados
        $query = "UPDATE tb_alunos_semestre SET conceito = '$conceito' WHERE IDAluno = '$alunoID'";
        if ($conn->query($query) === TRUE) {
            // Atualização bem-sucedida
            $response = array("status" => "success", "message" => "Conceito atualizado com sucesso");
        } else {
            // Erro ao atualizar o conceito
            $response = array("status" => "error", "message" => "Erro ao atualizar o conceito: " . $conn->error);
        }

        // Fecha a conexão com o banco de dados
        $conn->close();

        // Retorna a resposta como JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Parâmetros ausentes
        $response = array("status" => "error", "message" => "Parâmetros ausentes");

        // Retorna a resposta como JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Método de solicitação inválido
    $response = array("status" => "error", "message" => "Método de solicitação inválido");

    // Retorna a resposta como JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
