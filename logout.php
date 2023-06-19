<?php
    // Inicia a sessão do usuário
    session_start();

    // Destroi todas as variáveis de sessão
    session_unset();

    // Destroi a sessão
    session_destroy();

    // Redireciona o usuário para a página de login
    header("Location: login.php");
    exit();
?>
