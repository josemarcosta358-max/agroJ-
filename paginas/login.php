<?php
session_start();
require_once '../db_mock.php';

// Junta utilizadores registados nesta sessão (ver cadastro.php) aos utilizadores mock,
// para que um registo feito "ao vivo" também consiga fazer login.
if (isset($_SESSION['usuarios_extra']) && is_array($_SESSION['usuarios_extra'])) {
    foreach ($_SESSION['usuarios_extra'] as $extra) {
        $usuarios[$extra['id']] = $extra;
    }
}

$erro_login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
    $senha    = $_POST['senha'] ?? '';

    $usuario_encontrado = null;

    foreach ($usuarios as $usuario) {
        if ($usuario['telefone'] === $telefone && $usuario['senha'] === $senha) {
            $usuario_encontrado = $usuario;
            break;
        }
    }

    if ($usuario_encontrado) {
        $_SESSION['logado']       = true;
        $_SESSION['usuario_id']   = $usuario_encontrado['id'];
        $_SESSION['usuario_nome'] = $usuario_encontrado['nome'];
        $_SESSION['usuario_tipo'] = $usuario_encontrado['perfil'];

        header('Location: ../index.php');
        exit;
    } else {
        $erro_login = "Telefone ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroJá | Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"> 
    <link rel="stylesheet" href="../Styles/index.css">
</head>
<body class="auth-body">
    <header id="header">
        <a href="../index.php" class="logo"><span>AgroJa</span></a>
        <button class="hamburger" id="hamburger" aria-label="menu">
            <span></span><span></span><span></span>
        </button>

        <nav class="menu" id="nav-menu">
            <ul>
                <li><a href="../index.php#hero">Inicio</a></li>
                <li><a href="../index.php#sobre">Sobre Nós</a></li>
                <li><a href="../index.php#servicos">Serviços</a></li>
                <li><a href="../index.php#contactos">Contactos</a></li>
                <li><a href="login.php" class="active">Login</a></li>
                <li><a href="cadastro.php">Cadastro</a></li>
            </ul>
        </nav>

        <div class="header-right">
            <a href="../index.php" class="btn-login">Voltar</a>
        </div>
    </header>

    <main class="auth-page">
        <section class="auth-shell">
            <div class="auth-visual">
                <span class="section-label">Acesso rápido</span>
                <h1>Entrar na tua conta</h1>
                <p>Conecta-te com produtores, trabalhadores e oportunidades agrícolas em Angola.</p>

                <ul class="auth-benefits">
                    <li><i class="bi bi-check2-circle"></i> Gestão simples de serviços</li>
                    <li><i class="bi bi-check2-circle"></i> Alertas de oportunidades próximas</li>
                    <li><i class="bi bi-check2-circle"></i> Comunicação directa e segura</li>
                </ul>
            </div>

            <div class="auth-card">
                <div class="auth-card-head">
                    <h2>Bem-vindo de volta</h2>
                    <p>Continue onde ficou e avance para o próximo trabalho.</p>
                    <?php if (!empty($_SESSION['aviso_login'])): ?>
                        <p style="color: #e67e22; font-weight: 600; margin-top: 10px;">
                            <?= htmlspecialchars($_SESSION['aviso_login']) ?>
                        </p>
                        <?php unset($_SESSION['aviso_login']); ?>
                    <?php endif; ?>
                </div>

                <form class="auth-form" method="POST" action="">
                    <div class="field-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="+244 900 000 000" required>
                    </div>

                    <div class="field-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite a sua senha" required>
                    </div>

                    <div class="auth-row">
                        <label class="checkbox">
                            <input type="checkbox">
                            Lembrar-me
                        </label>
                        <a href="#">Esqueci a senha</a>
                    </div>

                    <button type="submit" class="auth-submit">Entrar</button>

                    <p class="auth-switch">
                        Ainda não tens conta?
                        <a href="cadastro.php">Criar conta</a>
                    </p>
                </form>
            </div>
        </section>
    </main>

    <script src="../JS/index.js"></script>
</body>
</html>