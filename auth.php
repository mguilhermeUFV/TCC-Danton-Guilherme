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

// Verifica se a matrícula e a senha foram enviadas pelo formulário de login
if (isset($_POST["matricula"]) && isset($_POST["senha"])) {
    session_start();
    // Limpa os valores enviados pelo formulário de login para evitar SQL injection
    $matricula = mysqli_real_escape_string($conn, $_POST["matricula"]);
    $senha = mysqli_real_escape_string($conn, $_POST["senha"]);

    // Criptografa a senha usando MD5
    $senhaCriptografada = md5($senha);

    // Consulta o banco de dados para verificar se as credenciais de login são válidas
    $sql = "SELECT * FROM tb_login WHERE matricula = '$matricula' AND senha = '$senhaCriptografada'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        // As credenciais de login são válidas, inicia a sessão do usuário
        $row = mysqli_fetch_assoc($result);
        $_SESSION["id"] = $row["IDAluno"];
        $_SESSION["matricula"] = $row["matricula"];

        // Verifica a matrícula do usuário autenticado
        if ($_SESSION["matricula"] == 10000) {
            header("Location: indexAdm.php"); // Redireciona para a página do administrador
            exit();
        } else {
            header("Location: index.php"); // Redireciona para a página do usuário comum
            exit();
        }
    } else {
        header("Location: login.php?message=" . urlencode("Credenciais inválidas, tente novamente."));
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>
