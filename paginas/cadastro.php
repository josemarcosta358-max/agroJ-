<?php
session_start();
require_once '../db_mock.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $telefone = htmlspecialchars(trim($_POST['telefone'] ?? ''));
    $email    = htmlspecialchars(trim($_POST['email'] ?? ''));
    $tipo     = htmlspecialchars(trim($_POST['tipo'] ?? ''));
    $senha    = $_POST['senha'] ?? ''; // senha não passa por htmlspecialchars para não alterar caracteres

    // Gera um novo ID simples, contando os utilizadores mock + os já criados nesta sessão
    $total_extra = isset($_SESSION['usuarios_extra']) ? count($_SESSION['usuarios_extra']) : 0;
    $novo_id = count($usuarios) + $total_extra + 1;

    $novo_usuario = [
        'id'       => $novo_id,
        'nome'     => $nome,
        'email'    => $email,
        'senha'    => $senha,
        'perfil'   => $tipo,
        'provincia'=> '', // ainda não recolhido no formulário
        'telefone' => $telefone,
        'ativo'    => true,
    ];

    // Como ainda não temos MySQL, guardamos o novo utilizador apenas na sessão (para fins de teste)
    if (!isset($_SESSION['usuarios_extra'])) {
        $_SESSION['usuarios_extra'] = [];
    }
    $_SESSION['usuarios_extra'][] = $novo_usuario;

    // Login automático após o registo
    $_SESSION['logado']       = true;
    $_SESSION['usuario_id']   = $novo_usuario['id'];
    $_SESSION['usuario_nome'] = $novo_usuario['nome'];
    $_SESSION['usuario_tipo'] = $novo_usuario['perfil'];
    $_SESSION['mensagem_sucesso'] = "Conta criada com sucesso! Bem-vindo(a) à AgroJá, " . $novo_usuario['nome'] . ".";

    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroJá | Cadastro</title>
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
                <li><a href="login.php">Login</a></li>
                <li><a href="cadastro.php" class="active">Cadastro</a></li>
            </ul>
        </nav>

        <div class="header-right">
            <a href="../index.php" class="btn-login">Voltar</a>
        </div>
    </header>

    <main class="auth-page">
        <section class="auth-shell">
            <div class="auth-visual">
                <span class="section-label">Começa hoje</span>
                <h1>Criar a tua conta</h1>
                <p>Junta-te à rede agrícola mais prática de Angola e encontre as melhores oportunidades.</p>

                <ul class="auth-benefits">
                    <li><i class="bi bi-people-fill"></i> Conecta-te com produtores e trabalhadores</li>
                    <li><i class="bi bi-broadcast-pin"></i> Publica ou encontra serviços rapidamente</li>
                    <li><i class="bi bi-shield-check"></i> Perfil profissional e credível</li>
                </ul>
            </div>

            <div class="auth-card">
                <div class="auth-card-head">
                    <h2>Regista-te</h2>
                    <p>Preenche os dados abaixo e entra para a comunidade AgroJá.</p>
                </div>

                <form class="auth-form" method="POST" action="">
                    <div class="field-group">
                        <label for="nome">Nome completo</label>
                        <input type="text" id="nome" name="nome" placeholder="Seu nome" required>
                    </div>

                    <div class="field-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" placeholder="+244 900 000 000">
                    </div>

                    <div class="field-group">
                        <label for="email-cadastro">Email</label>
                        <input type="email" id="email-cadastro" name="email" placeholder="seu@email.com" required>
                    </div>

                    <div class="field-group">
                        <label for="tipo">Eu sou</label>
                        <select id="tipo" name="tipo">
                            <option value="produtor">Produtor Individual</option>
                            <option value="cooperativa">Cooperativa</option>
                            <option value="trabalhador">Trabalhador Rural Sazonal</option>
                            <option value="prestador">Prestador Especializado</option>
                            <option value="agente">Agente Comunitário</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="senha-cadastro">Senha</label>
                        <input type="password" id="senha-cadastro" name="senha" placeholder="Crie uma senha forte" required>
                    </div>

                    <button type="submit" class="auth-submit">Criar conta</button>

                    <p class="auth-switch">
                        Já tens conta?
                        <a href="login.php">Entrar</a>
                    </p>
                </form>
            </div>
        </section>
    </main>

    <script src="../JS/index.js"></script>
</body>
</html>