<?php
session_start();
require_once 'db_mock.php';
?>
<!DOCTYPE html>
<html lang="pt-AO">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroJá</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="Styles/index.css">
</head>
<body>
    <header id="header">
        <a href="#hero" class="logo"><span>AgroJa</span></a>
        <button class="hamburger" id="hamburger" aria-label="menu">
            <span></span><span></span><span></span>
        </button>

        <nav class="menu" id="nav-menu">
            <ul>
                <li><a href="#hero">Inicio</a></li>
                <li><a href="#sobre">Sobre</a></li>
                <li><a href="vagas.php">Serviços</a></li>
                <li><a href="#contactos">Contactos</a></li>

                <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
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

    <?php if (!empty($_SESSION['mensagem_sucesso'])): ?>
        <div class="mensagem-sucesso">
            <?= htmlspecialchars($_SESSION['mensagem_sucesso']) ?>
        </div>
        <?php unset($_SESSION['mensagem_sucesso']); // mostra só uma vez ?>
    <?php endif; ?>

    <section id="hero">
        <div class="hero-grid" aria-hidden="true"></div>
        <h1> Trabalho agrícola, a um toque de distância.</h1>
        <p>Ligue-se a trabalhadores e produtores em toda Angola. O mercado profissional para quem cultiva o futuro da nossa terra.</p>

        <div class="hero-cta">
            <a href="paginas/cadastro.php" class="btn-primary">Juntar-me</a>
            <a href="#" class="btn-secondary">Saber mais</a>
        </div>
    </section>

    <section id="sobre" class="info-section">
        <span class="section-label">Quem somos</span>
        <h2 class="section-title">Sobre a plataforma</h2>

        <div class="sobre-grid">
            <div class="sobre-texto">
                <p>A <strong>AgroJá</strong> é um marketplace de mão de obra e serviços agricolas para Angola </p>
                <p>Aqui a tecnologia serve como ponte entre o produtor e o trabalhador de forma simples</p>
            </div>

            <div class="missao-card">
                <h3>Nossa Missão</h3>
                <p>Proporcionar conexão entre o produtor e o trabalhador de forma profissional</p>
            </div>

            <div class="card-container">
                <div class="card">
                    <h3>Produtor</h3>
                    <p>Contrate mão de obra qualificada para a sua colheita</p>
                </div>

                <div class="card">
                    <h3>Trabalhador</h3>
                    <p>Encontre oportunidades de trabalho em quintas e fazendas</p>
                </div>
            </div>
        </div>
    </section>

    <section class="how-it-works">
        <div class="exp-container">
            <h2>Como funciona</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h4> O produtor publica a necessidade</h4>
                    <p>Tipo	de serviço, local, data e número de pessoas — em menos de um minuto.</p>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <h4> O sistema notifica quem está próximo</h4>
                    <p>Trabalhadores e prestadores da zona recebem o alerta, pelo canal que já usam.</p>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <h4> O trabalhador aceita e negocia</h4>
                    <p>Preço e condições confirmados directamente entre as partes.</p>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <h4> O serviço	é realizado no campo</h4>
                    <p>Conclusão confirmada por ambos, sem burocracia.</p>
                </div>

                <div class="step">
                    <div class="step-number">5</div>
                    <h4> Pagamento e avaliação</h4>
                    <p>Multicaixa Express, dinheiro ou saldo interno — e uma nota que constrói reputação</p>
                </div>
            </div>
        </div>
    </section>

    <section id="servicos" class="vagas-section">
        <span class="section-label">Mercado de trabalho</span>
        <h2 class="section-title">Oportunidades no AgroJá</h2>

        <div class="vagas-grid">
            <?php foreach ($vagas as $vaga): ?>
                <?php
                    // Define o estilo da borda caso a vaga seja destaque (paga)
                    $estilo_destaque = $vaga['destaque'] ? 'border: 2px solid #f39c12;' : '';

                    // Define a cor do badge de estado
                    if ($vaga['estado'] === 'aberta') {
                        $badge_estado = '<span class="badge bg-success">Aberta</span>';
                    } elseif ($vaga['estado'] === 'em_candidatura') {
                        $badge_estado = '<span class="badge bg-primary">Em Candidatura</span>';
                    } else {
                        $badge_estado = '<span class="badge bg-secondary">' . htmlspecialchars($vaga['estado']) . '</span>';
                    }
                ?>
                <a href="vaga_detalhe.php?id=<?= (int)$vaga['id'] ?>" class="vaga-card-link">
                    <div class="vaga-card" style="<?= $estilo_destaque ?>">
                        <div class="vaga-card-header">
                            <?php if ($vaga['destaque']): ?>
                                <span class="badge bg-warning text-dark">🔥 Destacada</span>
                            <?php endif; ?>
                            <?= $badge_estado ?>
                        </div>

                        <h3 class="vaga-titulo"><?= htmlspecialchars($vaga['titulo']) ?></h3>
                        <p class="vaga-descricao"><?= htmlspecialchars($vaga['descricao']) ?></p>

                        <div class="vaga-footer">
                            <span class="vaga-valor">
                                <?= number_format($vaga['valor_total_kz'], 2, ',', '.') . ' Kz' ?>
                            </span>
                            <span class="btn-secondary">Ver detalhes</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="contactos" class="comu-section">
        <span class="section-label">Fale connosco</span>
        <h2 class="section-title">Entre em Contacto</h2>
        <div class="contact-grid">
            <div>
                <div class="contact-info-item">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Huila, Lubango-Angola</span>
            </div>

            <div class="contact-info-item">
                <i class="bi bi-envelope-fill"></i>
                <span>kutungatechsoluções@gmail.com</span>
            </div>

            <div class="contact-info-item">
                <i class="bi bi-whatsapp"></i>
                <a href="https://wa.me/244954620029" target="_blank">+244 954 620 029</a>
            </div>
            </div>

            <form action="#" method="post" class="contact-form">
                <div class="form-group">
                    <label for="nome">Seu Nome</label>
                    <input type="text" id="nome" name="nome" placeholder="José Costa" required>
                </div>

                <div class="form-group">
                    <label for="email_msg">Seu Email</label>
                    <input type="email" id="email_msg" name="email" placeholder="email@exemplo.com">
                </div>

                <div class="form-group">
                    <label for="msg">Mensagem</label>
                    <textarea name="mensagem" id="msg" placeholder="Escreva a sua mensagem..." required></textarea>
                </div>
                <button type="submit" class="btn-enviar"><i class="bi bi-send"></i>Enviar Mensagem</button>
            </form>
        </div>
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