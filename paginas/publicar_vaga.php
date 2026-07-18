<?php
session_start();
require_once '../db_mock.php';

// Protege a página: só produtores e cooperativas podem publicar vagas
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true
    || !in_array($_SESSION['usuario_tipo'], ['produtor', 'cooperativa'], true)) {
    header('Location: ../index.php');
    exit;
}

$erro_publicacao = '';
$custo_destaque   = 1500.00;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo       = htmlspecialchars(trim($_POST['titulo'] ?? ''));
    $descricao    = htmlspecialchars(trim($_POST['descricao'] ?? ''));
    $tipo_servico = htmlspecialchars(trim($_POST['tipo_servico'] ?? ''));
    $valor_total  = filter_input(INPUT_POST, 'valor_total', FILTER_VALIDATE_FLOAT);
    $destacar     = isset($_POST['destacar_vaga']);

    if ($titulo === '' || $descricao === '' || $tipo_servico === '' || $valor_total === false || $valor_total <= 0) {
        $erro_publicacao = 'Preenche todos os campos correctamente antes de publicar.';
    } else {
        $valor_final = $destacar ? ($valor_total + $custo_destaque) : $valor_total;

        // Simula um AUTO_INCREMENT enquanto não há MySQL
        $novo_id = count($vagas) + rand(100, 999);

        $nova_vaga = [
            'id'             => $novo_id,
            'produtor_id'    => $_SESSION['usuario_id'],
            'titulo'         => $titulo,
            'descricao'      => $descricao,
            'tipo_servico'   => $tipo_servico,
            'valor_total_kz' => $valor_final,
            'estado'         => 'aberta',
            'destaque'       => $destacar,
            'eh_destacada'   => $destacar,
            'data_criacao'   => date('Y-m-d'),
        ];

        save_new_vaga($nova_vaga);

        $_SESSION['mensagem_sucesso'] = 'Vaga publicada com sucesso!';
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroJá | Publicar Vaga</title>
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
                <li><a href="dashboard.php" class="active">Meu Painel</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <a href="dashboard.php" class="btn-login">Voltar ao Painel</a>
        </div>
    </header>

    <main class="detalhe-container">
        <h1 class="detalhe-titulo">Publicar Nova Vaga</h1>
        <p class="detalhe-publicado-por">Preenche os dados abaixo. A vaga fica visível assim que publicares.</p>

        <?php if (!empty($erro_publicacao)): ?>
            <div class="alerta-sucesso" style="background-color:#ffdad6; border-color:#ba1a1a; color:#93000a;">
                <?= htmlspecialchars($erro_publicacao) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="auth-form">
            <div class="field-group">
                <label for="titulo">Título do trabalho</label>
                <input type="text" id="titulo" name="titulo" placeholder="Ex: Colheita de Milho" required>
            </div>

            <div class="field-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" rows="4" placeholder="Descreve as tarefas, o que é fornecido e requisitos..." required style="width:100%; padding:13px 14px; border-radius:12px; border:1px solid var(--border-color); font-family:var(--font-main); resize:vertical;"></textarea>
            </div>

            <div class="field-group">
                <label for="tipo_servico">Tipo de Serviço</label>
                <select id="tipo_servico" name="tipo_servico" required>
                    <option value="">Selecciona...</option>
                    <option value="mao_de_obra">Mão de obra</option>
                    <option value="especializado">Serviço Especializado</option>
                    <option value="transporte">Transporte</option>
                </select>
            </div>

            <div class="field-group">
                <label for="valor_total">Valor Total (Kz)</label>
                <input type="number" id="valor_total" name="valor_total" min="1" step="0.01" placeholder="50000" required>
            </div>

            <label class="checkbox" style="align-items:flex-start; gap:10px;">
                <input type="checkbox" name="destacar_vaga" value="1">
                <span>Destacar esta vaga por <strong>1.500 Kz</strong> — aparece no topo da lista durante 48 horas.</span>
            </label>

            <button type="submit" class="auth-submit">
                <i class="bi bi-send"></i> Publicar Vaga
            </button>
        </form>
    </main>

    <footer>
        <p>© 2026 <strong>KutungaTech</strong> - Design: KutungaTech</p>
    </footer>

    <script src="../JS/index.js"></script>
</body>
</html>