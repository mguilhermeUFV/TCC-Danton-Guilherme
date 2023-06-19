<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/login.css">

    <style>
        @keyframes changeBackground {
            0% { background-image: url('imagens/1.jpg'); }
            25% { background-image: url('imagens/2.jpg'); }
            50% { background-image: url('imagens/3.jpeg'); }
            75% { background-image: url('imagens/4.jpg'); }
            100% { background-image: url('imagens/1.jpg'); }
        }

        .container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="container">
        <form method="post" action="auth.php">
            <h1>Horas+</h1>
            <h2>Login</h2>
            <label for="matricula">Matrícula:</label>
            <input type="text" id="matricula" name="matricula">
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha">
            <input type="submit" value="Entrar">
        </form>
        <?php if (isset($_GET['message'])): ?>
            <?php
                $message = urldecode($_GET['message']);
                $messageClass = strpos($message, 'sucesso') !== false ? 'success' : 'error';
            ?>
            <div class="message-container <?php echo $messageClass; ?>">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const messageContainer = document.querySelector('.message-container');

            if (messageContainer.textContent !== '') {
                messageContainer.style.display = 'block';
            }
        });
    </script>
</body>
</html>
