<?php
require_once __DIR__ . '/config.php';

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO(
        'mysql:host=localhost;dbname=gestao;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    return $pdo;
}

function db_tentar(): ?PDO
{
    try {
        return db();
    } catch (Throwable $e) {
        return null;
    }
}

function vagas_demo(): array
{
    $base = [
        'status' => 'Aberta',
        'verificada' => 0,
        'dataVerificacao' => null,
        'dataPublicacao' => date('Y-m-d H:i:s', strtotime('-2 days')),
        'nivel' => 'Pleno',
        'requisitos' => "Experiência com desenvolvimento web\nBoa comunicação\nTrabalho em equipe",
        'beneficios' => "Plano de saúde\nAuxílio home office\nHorário flexível",
    ];
    return [
        array_merge($base, [
            'id' => 1, 'empresaId' => 101, 'empresaNome' => 'TechSolutions', 'empresaCidade' => 'Rio Branco - AC',
            'titulo' => 'Desenvolvedor(a) Web', 'localizacao' => 'Rio Branco - AC', 'modalidade' => 'Remoto',
            'tipoContrato' => 'CLT', 'salario' => 5200, 'verificada' => 1,
            'descricao' => 'Desenvolvimento de páginas e aplicações web, colaboração com a equipe e melhoria contínua de produtos digitais.',
            'recursos' => ['Trabalho remoto', 'Materiais digitais acessíveis', 'Acesso sem barreiras físicas'],
        ]),
        array_merge($base, [
            'id' => 2, 'empresaId' => 102, 'empresaNome' => 'Instituto Nova Inclusão', 'empresaCidade' => 'Rio Branco - AC',
            'titulo' => 'Assistente Administrativo', 'localizacao' => 'Rio Branco - AC', 'modalidade' => 'Presencial',
            'tipoContrato' => 'CLT', 'salario' => 2400, 'verificada' => 1, 'nivel' => 'Júnior',
            'descricao' => 'Organização de documentos, atendimento e apoio aos processos administrativos.',
            'recursos' => ['Acesso sem barreiras físicas', 'Intérprete de Libras', 'Horário flexível'],
        ]),
        array_merge($base, [
            'id' => 3, 'empresaId' => 103, 'empresaNome' => 'ConectaTech', 'empresaCidade' => 'Cruzeiro do Sul - AC',
            'titulo' => 'Analista de Suporte', 'localizacao' => 'Cruzeiro do Sul - AC', 'modalidade' => 'Híbrido',
            'tipoContrato' => 'PJ', 'salario' => 4100, 'verificada' => 0, 'nivel' => 'Pleno',
            'descricao' => 'Atendimento a usuários e suporte técnico para sistemas e equipamentos.',
            'recursos' => ['Trabalho remoto', 'Materiais digitais acessíveis', 'Tecnologia assistiva'],
        ]),
        array_merge($base, [
            'id' => 4, 'empresaId' => 104, 'empresaNome' => 'Criativa Digital', 'empresaCidade' => 'Rio Branco - AC',
            'titulo' => 'Designer Gráfico', 'localizacao' => 'Rio Branco - AC', 'modalidade' => 'Remoto',
            'tipoContrato' => 'Freelancer', 'salario' => 3200, 'verificada' => 1, 'nivel' => 'Júnior',
            'descricao' => 'Criação de peças visuais para redes sociais e materiais digitais.',
            'recursos' => ['Trabalho remoto', 'Materiais digitais acessíveis', 'Intérprete de Libras'],
        ]),
    ];
}

function vaga_normalizar(array $row): array
{
    $row['recursos'] = !empty($row['recursos']) ? array_values(array_filter(explode('|', $row['recursos']))) : [];
    $row['verificada'] = (int) ($row['verificada'] ?? 0);
    $row['salario'] = $row['salario'] ?? null;
    $row['nivel'] = $row['nivel'] ?? '';
    $row['requisitos'] = $row['requisitos'] ?? '';
    $row['beneficios'] = $row['beneficios'] ?? '';
    return $row;
}

function vagas_filtro_demo(array $vagas, array $filtros): array
{
    $q = mb_strtolower(trim((string) ($filtros['q'] ?? '')));
    $cidade = mb_strtolower(trim((string) ($filtros['cidade'] ?? '')));
    $modalidade = mb_strtolower(trim((string) ($filtros['modalidade'] ?? '')));
    $tipo = mb_strtolower(trim((string) ($filtros['tipo'] ?? '')));
    $recursos = $filtros['recursos'] ?? [];
    $verificada = !empty($filtros['verificada']);

    $resultado = array_values(array_filter($vagas, function ($v) use ($q, $cidade, $modalidade, $tipo, $recursos, $verificada) {
        $texto = mb_strtolower($v['titulo'] . ' ' . $v['empresaNome'] . ' ' . $v['descricao']);
        if ($q !== '' && !str_contains($texto, $q)) return false;
        if ($cidade !== '' && !str_contains(mb_strtolower($v['localizacao'] . ' ' . $v['empresaCidade']), $cidade)) return false;
        if ($modalidade !== '' && mb_strtolower($v['modalidade']) !== $modalidade) return false;
        if ($tipo !== '' && mb_strtolower($v['tipoContrato']) !== $tipo) return false;
        if ($verificada && empty($v['verificada'])) return false;
        foreach ($recursos as $r) {
            if (!in_array($r, $v['recursos'], true)) return false;
        }
        return true;
    }));

    $ordem = $filtros['ordem'] ?? 'recentes';
    if ($ordem === 'salario') {
        usort($resultado, fn($a, $b) => ($b['salario'] ?? 0) <=> ($a['salario'] ?? 0));
    } else {
        usort($resultado, fn($a, $b) => strtotime($b['dataPublicacao']) <=> strtotime($a['dataPublicacao']));
    }
    return $resultado;
}

function vagas_listar(array $filtros = []): array
{
    return vagas_filtro_demo(vagas_demo(), $filtros);
}

function vaga_buscar(int $id): ?array
{
    foreach (vagas_demo() as $v) { if ((int)$v['id'] === $id) return $v; } return null;
}

function vagas_relacionadas(int $id, int $empresaId, int $limite = 3): array
{
    return array_slice(array_values(array_filter(vagas_demo(), fn($v) => (int)$v['id'] !== $id)), 0, $limite);
}

function vaga_salvar(array $dados, array $recursosIds): int
{
    throw new RuntimeException('Publicação de vagas indisponível: banco possui apenas candidato, empresa e avaliacao.');
}

function vaga_alternar(int $id, int $empresaId): bool
{
    return false;
}

function vaga_do_empresa(int $empresaId): array
{
    return [];
}

function vagas_salvas_ids(int $candidatoId): array
{
    return [];
}

function salvar_vaga(int $candidatoId, int $vagaId): bool
{
    return false;
}

function remover_vaga_salva(int $candidatoId, int $vagaId): bool
{
    return false;
}

function vagas_salvas(int $candidatoId): array
{
    return [];
}

function candidatura_criar(int $vagaId, int $candidatoId): array
{
    return ['ok' => false, 'mensagem' => 'Candidaturas indisponíveis nesta versão demonstrativa.'];
}

function candidatura_buscar(int $vagaId, int $candidatoId): ?array
{
    return null;
}

function candidaturas_candidato(int $candidatoId): array
{
    return [];
}

function candidaturas_empresa(int $empresaId): array
{
    return [];
}

function candidatura_atualizar_status(int $id, string $status, int $empresaId): bool
{
    return false;
}

function recursos_disponiveis(): array
{
    $lista = []; foreach (array_keys(recurso_mapa()) as $i => $nome) $lista[] = ['id' => $i+1, 'nome' => $nome, 'descricao' => recurso_descricao($nome)]; return $lista;
}

function recursos_candidato(int $candidatoId): array
{
    return [];
}

function salvar_recursos_candidato(int $candidatoId, array $recursosIds): void
{
    return; // Sem tabela para persistir recursos individuais.
}
