<?php
// db_mock.php - Simulação da base de dados MySQL para o MVP do AgroJá

$config_sistema = [
    'comissao_padrao' => 0.05, // 5% de comissão padrão sobre cada vaga fechada
    'comissao_maxima' => 0.10, // 10% máximo permitido
    'moeda'           => 'Kz',
];

$usuarios = [
    1 => [
        'id'       => 1,
        'nome'     => 'Manuel Kiluanji',
        'email'    => 'manuel.kiluanji@gmail.com',
        'senha'    => '123',
        'perfil'   => 'produtor',
        'provincia'=> 'Huambo',
        'telefone' => '923456789',
        'ativo'    => true,
    ],
    2 => [
        'id'       => 2,
        'nome'     => 'Ana Domingos',
        'email'    => 'ana.domingos@gmail.com',
        'senha'    => '123',
        'perfil'   => 'trabalhador_sazonal',
        'provincia'=> 'Benguela',
        'telefone' => '934567890',
        'ativo'    => true,
    ],
    3 => [
        'id'       => 3,
        'nome'     => 'Carlos',
        'email'    => 'carlos.tractorista@gmail.com',
        'senha'    => '123',
        'perfil'   => 'prestador_especializado',
        'especialidade' => 'tractorista',
        'provincia'=> 'Bié',
        'telefone' => '945678901',
        'ativo'    => true,
    ],
];

$vagas = [
    1 => [
        'id'             => 1,
        'produtor_id'    => 1, // Manuel Kiluanji
        'titulo'         => 'Limpeza de 2 hectares',
        'descricao'      => 'Precisamos de mão de obra para limpeza e preparação de terreno em 2 hectares, na zona do Huambo.',
        'valor_total_kz' => 50000.00,
        'estado'         => 'aberta',
        'destaque'       => false,
        'data_criacao'   => '2026-07-10',
    ],
    2 => [
        'id'             => 2,
        'produtor_id'    => 1, // Manuel Kiluanji
        'titulo'         => 'Tractorista com urgência',
        'descricao'      => 'Precisamos de um tractorista experiente para lavoura urgente. Início imediato.',
        'valor_total_kz' => 15000.00,
        'estado'         => 'em_candidatura',
        'destaque'       => true,
        'data_criacao'   => '2026-07-15',
    ],
];

// Estruturas ainda vazias, prontas para receber dados no futuro
$candidaturas = [];
$transacoes   = [];
$avaliacoes   = [];