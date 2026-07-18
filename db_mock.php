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
$transacoes   = [];
$avaliacoes   = [];

// ==========================================================
// PERSISTÊNCIA GLOBAL (simula "todos verem", enquanto não há MySQL)
// ==========================================================
// As candidaturas deixaram de viver na $_SESSION (onde só o próprio
// utilizador as via) e passam a ser um array global $candidaturas_globais,
// espelhado num ficheiro em disco para sobreviver entre pedidos HTTP —
// já que um array PHP normal reinicia sempre que a página é recarregada.
$candidaturas_globais = [];

define('DATA_DIR', __DIR__ . '/data');
define('VAGAS_EXTRA_FILE', DATA_DIR . '/vagas_extra.json');
define('CANDIDATURAS_FILE', DATA_DIR . '/candidaturas_globais.json');

if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0775, true);
}

// Junta as vagas publicadas dinamicamente (via publicar_vaga.php) às vagas mock fixas acima
if (file_exists(VAGAS_EXTRA_FILE)) {
    $vagas_extra = json_decode(file_get_contents(VAGAS_EXTRA_FILE), true) ?: [];
    foreach ($vagas_extra as $vaga_extra) {
        $vagas[$vaga_extra['id']] = $vaga_extra;
    }
}

// Junta as candidaturas guardadas em ficheiro (simula a tabela "candidaturas")
if (file_exists(CANDIDATURAS_FILE)) {
    $candidaturas_globais = json_decode(file_get_contents(CANDIDATURAS_FILE), true) ?: [];
}

/**
 * Grava uma nova vaga no ficheiro que simula a tabela "vagas" do MySQL.
 */
function save_new_vaga(array $vaga): void
{
    $vagas_extra = file_exists(VAGAS_EXTRA_FILE)
        ? (json_decode(file_get_contents(VAGAS_EXTRA_FILE), true) ?: [])
        : [];
    $vagas_extra[] = $vaga;
    file_put_contents(VAGAS_EXTRA_FILE, json_encode($vagas_extra, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

/**
 * Grava uma nova candidatura no ficheiro que simula a tabela "candidaturas" do MySQL.
 */
function save_new_candidatura(array $candidatura): void
{
    $candidaturas = file_exists(CANDIDATURAS_FILE)
        ? (json_decode(file_get_contents(CANDIDATURAS_FILE), true) ?: [])
        : [];
    $candidaturas[] = $candidatura;
    file_put_contents(CANDIDATURAS_FILE, json_encode($candidaturas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

/**
 * Actualiza o estado de uma candidatura (ex: 'pendente' -> 'aceite').
 */
function update_candidatura_estado(int $indice, string $novo_estado): void
{
    $candidaturas = file_exists(CANDIDATURAS_FILE)
        ? (json_decode(file_get_contents(CANDIDATURAS_FILE), true) ?: [])
        : [];
    if (isset($candidaturas[$indice])) {
        $candidaturas[$indice]['estado'] = $novo_estado;
        file_put_contents(CANDIDATURAS_FILE, json_encode($candidaturas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}