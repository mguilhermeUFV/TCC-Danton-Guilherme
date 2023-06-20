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

// Obtém o ID do aluno enviado via parâmetro na requisição GET
if (isset($_GET["id"])) {
    $alunoID = $_GET["id"];

    // Consulta SQL para obter as atividades aprovadas por categoria
    $sqlAprovadasPorCategoria = "SELECT ai.codigoArquivo, SUM(ai.totalContabilizado) AS totalCargaHoraria FROM tb_arquivos_importados ai JOIN tb_alunos_semestre als ON ai.IDAluno = als.IDAluno WHERE ai.statusArquivo = 'Aprovado' AND als.IDAluno = '$alunoID' GROUP BY ai.codigoArquivo";

    $resultAprovadasPorCategoria = $conn->query($sqlAprovadasPorCategoria);

    $atividadesAprovadas = array();

    if ($resultAprovadasPorCategoria->num_rows > 0) {
        while ($row = $resultAprovadasPorCategoria->fetch_assoc()) {
            $atividade = array(
                "codigoArquivo" => $row["codigoArquivo"],
                "totalCargaHoraria" => $row["totalCargaHoraria"]
            );
            $atividadesAprovadas[] = $atividade;
        }
    }

    // Retorna as atividades aprovadas por categoria como resposta JSON
    header('Content-Type: application/json');
    echo json_encode($atividadesAprovadas);
} else {
    // Caso o ID do aluno não seja fornecido na requisição GET
    echo "ID do aluno não fornecido na requisição.";
}
?>