<?php
session_start();
require_once 'db_mock.php';

// Recebe e valida o ID da vaga vindo do link
$vaga_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Se não houver ID válido ou a vaga não existir no array, mata o processo
if (!$vaga_id || !isset($vagas[$vaga_id])) {
    die('<div style="font-family: sans-serif; text-align: center; padding: 100px 20px;">
            <h2>Vaga não encontrada.</h2>
            <p><a href="index.php">Voltar à página inicial</a></p>
         </div>');
}

$vaga = $vagas[$vaga_id];

// Busca os dados do produtor que publicou a vaga
$produtor = $usuarios[$vaga['produtor_id']] ?? null;
$nome_produtor = $produtor ? $produtor['nome'] : 'Produtor desconhecido';
$provincia_produtor = $produtor ? $produtor['provincia'] : '';

// A MATEMÁTICA: cálculo dinâmico da comissão, nunca hardcoded
$taxa = $config_sistema['comissao_padrao']; // ex: 0.05
$valor_total = $vaga['valor_total_kz'];
$valor_comissao_plataforma = $valor_total * $taxa;
$valor_liquido_trabalhador = $valor_total - $valor_comissao_plataforma;

// Badge de estado (mesma lógica do index.php)
if ($vaga['estado'] === 'aberta') {
    $badge_estado = '<span class="badge bg-success">Aberta</span>';
} elseif ($vaga['estado'] === 'em_candidatura') {
    $badge_estado = '<span class="badge bg-primary">Em Candidatura</span>';
} else {
    $badge_estado = '<span class="badge bg-secondary">' . htmlspecialchars($vaga['estado']) . '</span>';
}

// Verifica se o utilizador já se candidatou a esta vaga (evita duplicados)
// Agora consultamos o array global $candidaturas_globais (vindo do db_mock.php),
// que é partilhado por todos os utilizadores, em vez da $_SESSION individual.
$ja_candidatado = false;
if (isset($_SESSION['usuario_id'])) {
    foreach ($candidaturas_globais as $candidatura) {
        if ($candidatura['vaga_id'] === $vaga_id && $candidatura['candidato_id'] === $_SESSION['usuario_id']) {
            $ja_candidatado = true;
            break;
        }
    }
}

// Lógica do botão "Candidatar-me"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['candidatar'])) {

    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        // Não está logado: guarda um aviso e manda para o login
        $_SESSION['aviso_login'] = "Precisas de entrar na tua conta para te candidatares a uma vaga.";
        header('Location: paginas/login.php');
        exit;
    }

    if (!$ja_candidatado) {
        $nova_candidatura = [
            'vaga_id'        => $vaga_id,
            'vaga_titulo'    => $vaga['titulo'],
            'candidato_id'   => $_SESSION['usuario_id'],
            'candidato_nome' => $_SESSION['usuario_nome'],
            'estado'         => 'pendente',
        ];

        // Guarda no array global (usada pelo produtor para ver quem se candidatou)
        // e persiste em disco para sobreviver ao próximo pedido HTTP.
        $candidaturas_globais[] = $nova_candidatura;
        save_new_candidatura($nova_candidatura);

        $ja_candidatado = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroJá | <?= htmlspecialchars($vaga['titulo']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Styles/index.css">
</head>
<body>
    <header id="header">
        <a href="index.php#hero" class="logo"><span>AgroJa</span></a>
        <button class="hamburger" id="hamburger" aria-label="menu">
            <span></span><span></span><span></span>
        </button>

        <nav class="menu" id="nav-menu">
            <ul>
                <li><a href="index.php#hero">Inicio</a></li>
                <li><a href="index.php#sobre">Sobre Nós</a></li>
                <li><a href="vagas.php">Serviços</a></li>
                <li><a href="index.php#contactos">Contactos</a></li>

                <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                    <li><a href="paginas/dashboard.php">Meu Painel</a></li>
                    <li><a href="paginas/logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="paginas/login.php">Login</a></li>
                    <li><a href="paginas/cadastro.php">Cadastro</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="header-right">
            <form action="vagas.php" method="get" class="search-wrap">
                <input type="text" name="quwery" placeholder="Pesquisar trabalhos..." required>
                <button type="submit"><i class="bi bi-search"></i>Pesquisar</button>
            </form>

            <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                <span class="btn-login">
                    Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                    | <a href="paginas/dashboard.php" style="color: inherit;">Meu Painel</a>
                    | <a href="paginas/logout.php" style="color: inherit;">Sair</a>
                </span>
            <?php else: ?>
                <a href="paginas/login.php" class="btn-login">Entrar</a>
            <?php endif; ?>
        </div>
    </header>

    <section class="detalhe-container">
        <a href="index.php#servicos" class="voltar-link"><i class="bi bi-arrow-left"></i> Voltar às vagas</a>

        <div class="detalhe-header">
            <?= $badge_estado ?>
            <?php if ($vaga['destaque']): ?>
                <span class="badge bg-warning text-dark">🔥 Destacada</span>
            <?php endif; ?>
        </div>

        <h1 class="detalhe-titulo"><?= htmlspecialchars($vaga['titulo']) ?></h1>
        <p class="detalhe-publicado-por">
            <i class="bi bi-person-circle"></i>
            Publicado por: <strong><?= htmlspecialchars($nome_produtor) ?> (<?= htmlspecialchars($provincia_produtor) ?>)</strong>
        </p>

        <p class="detalhe-descricao"><?= htmlspecialchars($vaga['descricao']) ?></p>

        <div class="recibo-box">
            <h3>Resumo do Pagamento</h3>

            <div class="recibo-linha">
                <span>Valor Total Acordado</span>
                <span><?= number_format($valor_total, 2, ',', '.') . ' Kz' ?></span>
            </div>

            <div class="recibo-linha recibo-taxa">
                <span>Taxa AgroJá (<?= number_format($taxa * 100, 0) ?>%)</span>
                <span>- <?= number_format($valor_comissao_plataforma, 2, ',', '.') . ' Kz' ?></span>
            </div>

            <hr>

            <div class="recibo-linha recibo-liquido">
                <span>Valor Líquido para o Trabalhador</span>
                <span><?= number_format($valor_liquido_trabalhador, 2, ',', '.') . ' Kz' ?></span>
            </div>
        </div>

        <?php if ($ja_candidatado): ?>
            <button type="button" class="btn-candidatar btn-candidatado" disabled>Já te candidataste</button>
        <?php else: ?>
            <form method="POST" action="">
                <button type="submit" name="candidatar" value="1" class="btn-candidatar">Candidatar-me</button>
            </form>
        <?php endif; ?>
    </section>

    <footer>
        <p>© 2026 <strong>KutungaTech</strong>- Design: KutungaTech</p>
        <div class="footer-links">
            <a href="#" target="_blank"><i class="bi bi-facebook"></i></a>
            <a href="#" target="_blank"><i class="bi bi-github"></i></a>
            <a href="https://wa.me/244954620029" target="_blank"><i class="bi bi-whatsapp"></i></a>
        </div>
    </footer>

    <script src="JS/index.js"></script>
</body>
</html>