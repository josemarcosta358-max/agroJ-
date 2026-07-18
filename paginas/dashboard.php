<?php
session_start();
require_once '../db_mock.php';

// Protege a página: só utilizadores logados podem ver o painel
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: login.php');
    exit;
}

$minhas_candidaturas   = $_SESSION['minhas_candidaturas'] ?? [];
$candidaturas_globais  = $_SESSION['candidaturas_globais'] ?? [];

$taxa = $config_sistema['comissao_padrao']; // usada no alerta de aceitação

// Lógica do Produtor: aceitar um candidato
$alerta_aceite = '';

if ($_SESSION['usuario_tipo'] === 'produtor' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aceitar_indice'])) {
    $indice = (int) $_POST['aceitar_indice'];

    if (isset($_SESSION['candidaturas_globais'][$indice])) {
        $_SESSION['candidaturas_globais'][$indice]['estado'] = 'aceite';
        $candidaturas_globais = $_SESSION['candidaturas_globais']; // atualiza a cópia local

        $valor_vaga_aceite = 0;
        foreach ($vagas as $v) {
            if ($v['id'] === $candidaturas_globais[$indice]['vaga_id']) {
                $valor_vaga_aceite = $v['valor_total_kz'];
                break;
            }
        }
        $comissao_retida = $valor_vaga_aceite * $taxa;

        $alerta_aceite = "Candidato aceito! A taxa de " . number_format($taxa * 100, 0) . "% ("
            . number_format($comissao_retida, 2, ',', '.') . " Kz) sobre o valor da vaga foi retida para a KutungaTech.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroJá | Meu Painel</title>
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
                <li><a href="dashboard.php" class="active">Meu Painel</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>

        <div class="header-right">
            <a href="../index.php" class="btn-login">Voltar</a>
        </div>
    </header>

    <main class="detalhe-container">

        <?php if ($_SESSION['usuario_tipo'] === 'produtor'): ?>

            <!-- ============================================
                 PAINEL DO PRODUTOR
                 ============================================ -->
            <h1 class="detalhe-titulo">Painel do Produtor</h1>
            <p class="detalhe-publicado-por">
                Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome']) ?></strong>. Aqui estão as tuas vagas publicadas.
            </p>

            <?php if (!empty($alerta_aceite)): ?>
                <div class="alerta-sucesso">
                    <?= htmlspecialchars($alerta_aceite) ?>
                </div>
            <?php endif; ?>

            <section class="dashboard-secao">
                <?php
                    // Filtra só as vagas deste produtor
                    $minhas_vagas = array_filter($vagas, function ($v) {
                        return $v['produtor_id'] === $_SESSION['usuario_id'];
                    });
                ?>

                <?php if (empty($minhas_vagas)): ?>
                    <p class="dashboard-vazio">Ainda não publicaste nenhuma vaga.</p>
                <?php else: ?>
                    <div class="dashboard-lista">
                        <?php foreach ($minhas_vagas as $vaga_produtor): ?>
                            <div class="dashboard-vaga-card">
                                <div class="dashboard-vaga-header">
                                    <h3><?= htmlspecialchars($vaga_produtor['titulo']) ?></h3>
                                    <span class="dashboard-valor">
                                        <?= number_format($vaga_produtor['valor_total_kz'], 2, ',', '.') . ' Kz' ?>
                                    </span>
                                </div>

                                <?php
                                    // Procura candidaturas para esta vaga específica
                                    $tem_candidatos = false;
                                ?>

                                <div class="dashboard-candidatos">
                                    <?php foreach ($candidaturas_globais as $indice => $candidatura): ?>
                                        <?php if ($candidatura['vaga_id'] === $vaga_produtor['id']): ?>
                                            <?php $tem_candidatos = true; ?>
                                            <div class="candidato-linha">
                                                <span class="candidato-nome">
                                                    <i class="bi bi-person-fill"></i>
                                                    <?= htmlspecialchars($candidatura['candidato_nome']) ?>
                                                </span>

                                                <?php if ($candidatura['estado'] === 'aceite'): ?>
                                                    <span class="badge bg-success">Aceite</span>
                                                <?php else: ?>
                                                    <form method="POST" action="" style="margin: 0;">
                                                        <input type="hidden" name="aceitar_indice" value="<?= (int) $indice ?>">
                                                        <button type="submit" class="btn-aceitar">Aceitar Candidato</button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <?php if (!$tem_candidatos): ?>
                                        <p class="dashboard-sem-candidatos">Ainda sem candidatos para esta vaga.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        <?php else: ?>

            <!-- ============================================
                 PAINEL DO TRABALHADOR / PRESTADOR
                 ============================================ -->
            <h1 class="detalhe-titulo">
                Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>! Este é o teu painel.
            </h1>

            <section class="dashboard-secao">
                <h2 class="section-title" style="font-size: 1.6rem; margin-bottom: 25px;">Minhas Candidaturas</h2>

                <?php if (empty($minhas_candidaturas)): ?>
                    <p class="dashboard-vazio">
                        Ainda não te candidataste a nenhuma vaga. <a href="../index.php">Ver vagas</a>
                    </p>
                <?php else: ?>
                    <div class="dashboard-lista">
                        <?php foreach ($minhas_candidaturas as $candidatura): ?>
                            <div class="dashboard-card">
                                <div class="dashboard-card-info">
                                    <h3><?= htmlspecialchars($candidatura['titulo']) ?></h3>
                                    <span class="dashboard-valor">
                                        <?= number_format($candidatura['valor'], 2, ',', '.') . ' Kz' ?>
                                    </span>
                                </div>
                                <span class="badge bg-warning text-dark">Pendente</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        <?php endif; ?>

    </main>

    <footer>
        <p>© 2026 <strong>KutungaTech</strong>- Design: KutungaTech</p>
        <div class="footer-links">
            <a href="#" target="_blank"><i class="bi bi-facebook"></i></a>
            <a href="#" target="_blank"><i class="bi bi-github"></i></a>
            <a href="https://wa.me/244954620029" target="_blank"><i class="bi bi-whatsapp"></i></a>
        </div>
    </footer>

    <script src="../JS/index.js"></script>
</body>
</html>