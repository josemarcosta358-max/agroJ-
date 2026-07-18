<?php
session_start();
require_once '../db_mock.php';
?>
<!DOCTYPE html>
<html lang="pt-AO">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Vagas - AgroJá</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="js/tailwind-config.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="css/style.css"/>
</head>
<body class="bg-background text-on-background min-h-screen">
<div class="app-frame">
<header class="bg-surface border-b border-outline-variant w-full sticky top-0 z-30 px-margin-mobile pt-4 pb-3">
<div class="flex items-center justify-between mb-3">
<h1 class="font-headline-md text-headline-md text-primary font-bold">Vagas Disponíveis</h1>
<button class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined">tune</span></button>
</div>
<div class="flex items-center bg-surface-container-low rounded-full px-4 py-2.5 border border-outline-variant gap-2">
<span class="material-symbols-outlined text-on-surface-variant text-[20px]">search</span>
<input class="bg-transparent border-none focus:ring-0 w-full font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant p-0" placeholder="Pesquisar por cultura, serviço ou zona..." type="text"/>
</div>
<div class="flex gap-2 mt-3 overflow-x-auto pb-1">
<span class="bg-primary text-on-primary px-3 py-1.5 rounded-full font-label-md text-[12px] whitespace-nowrap">Todas</span>
<span class="bg-surface-container-high text-on-surface-variant px-3 py-1.5 rounded-full font-label-md text-[12px] whitespace-nowrap">Trabalho de Campo</span>
<span class="bg-surface-container-high text-on-surface-variant px-3 py-1.5 rounded-full font-label-md text-[12px] whitespace-nowrap">Serviço Especializado</span>
<span class="bg-surface-container-high text-on-surface-variant px-3 py-1.5 rounded-full font-label-md text-[12px] whitespace-nowrap">Transporte</span>
</div>
</header>
<main class="px-margin-mobile pt-4 pb-4 flex flex-col gap-3">
    
    <!-- CICLO PHP QUE GERA AS VAGAS DINÂMICAMENTE -->
    <?php foreach($vagas as $id => $vaga): 
        $produtor = $usuarios[$vaga['produtor_id']] ?? null;
    ?>
    <a href="../vaga_detalhe.php?id=<?= $id ?>" class="relative block bg-white rounded-xl border border-outline-variant shadow-sm hover:shadow-md transition-shadow p-4">
    
        <?php if($vaga['eh_destacada']): ?>
            <span class="absolute top-3 left-3 bg-secondary-container text-on-secondary-container text-[11px] font-semibold px-2.5 py-1 rounded-full">Urgente</span>
        <?php endif; ?>

        <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center flex-shrink-0"><span class="material-symbols-outlined">agriculture</span></div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-label-lg text-label-lg text-on-surface truncate"><?= htmlspecialchars($vaga['titulo']) ?></h3>
                    <span class="font-label-lg text-label-lg text-secondary font-bold whitespace-nowrap"><?= number_format($vaga['valor_total_kz'], 2, ',', '.') ?> Kz</span>
                </div>
                <p class="font-body-sm text-on-surface-variant"><?= htmlspecialchars(ucfirst($vaga['tipo_servico'])) ?> · <?= htmlspecialchars($produtor['provincia'] ?? 'Angola') ?></p>
                <div class="flex items-center justify-between mt-2">
                    <span class="font-body-sm text-on-surface-variant"><?= htmlspecialchars($produtor['nome'] ?? 'Produtor') ?></span>
                    <span class="flex items-center gap-1 text-secondary"><span class="material-symbols-outlined text-[14px] fill-icon">star</span><span class="font-label-md text-[12px]">4.9</span></span>
                </div>
            </div>
        </div>
    </a>
    <?php endforeach; ?>

</main>
<!-- NAV BAR ATUALIZADA COM .PHP -->
<nav class="bottom-nav w-full bg-surface-container-lowest border-t border-outline-variant flex items-stretch z-50 h-[68px]">
    <a href="index.php" class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 text-on-surface-variant"><span class="material-symbols-outlined text-[24px]">home</span><span class="font-label-md text-[11px] font-medium">Início</span></a>
    <a href="vagas.php" class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 text-primary"><span class="material-symbols-outlined fill-icon text-[24px]">work</span><span class="font-label-md text-[11px] font-medium">Vagas</span></a>
    <a href="mensagens.php" class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 text-on-surface-variant"><span class="material-symbols-outlined text-[24px]">chat_bubble</span><span class="font-label-md text-[11px] font-medium">Mensagens</span></a>
    <a href="meus-trabalhos.php" class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 text-on-surface-variant"><span class="material-symbols-outlined text-[24px]">assignment_turned_in</span><span class="font-label-md text-[11px] font-medium">Trabalhos</span></a>
    <a href="perfil.php" class="flex flex-col items-center justify-center gap-0.5 flex-1 py-2 text-on-surface-variant"><span class="material-symbols-outlined text-[24px]">person</span><span class="font-label-md text-[11px] font-medium">Perfil</span></a>
</nav>
</div>
</body></html>