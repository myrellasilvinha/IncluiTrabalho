<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/dados.php';

function empresas_demo(): array
{
    return [
        ['id' => 101, 'nome' => 'TechSolutions', 'cidade' => 'Rio Branco - AC', 'descricao' => 'Empresa de tecnologia focada em produtos digitais acessíveis e desenvolvimento de software sob medida.', 'site' => 'https://exemplo.com', 'telefone' => null, 'totalVagas' => 1, 'media' => 4.7, 'totalAvaliacoes' => 12],
        ['id' => 102, 'nome' => 'Instituto Nova Inclusão', 'cidade' => 'Rio Branco - AC', 'descricao' => 'Organização social que atua na formação e inclusão produtiva de pessoas com deficiência.', 'site' => null, 'telefone' => null, 'totalVagas' => 1, 'media' => 4.9, 'totalAvaliacoes' => 8],
        ['id' => 103, 'nome' => 'ConectaTech', 'cidade' => 'Cruzeiro do Sul - AC', 'descricao' => 'Suporte técnico e infraestrutura de TI para pequenas e médias empresas.', 'site' => null, 'telefone' => null, 'totalVagas' => 1, 'media' => 4.2, 'totalAvaliacoes' => 5],
        ['id' => 104, 'nome' => 'Criativa Digital', 'cidade' => 'Rio Branco - AC', 'descricao' => 'Estúdio de design e comunicação digital com foco em acessibilidade visual.', 'site' => null, 'telefone' => null, 'totalVagas' => 1, 'media' => 4.5, 'totalAvaliacoes' => 6],
    ];
}

function empresa_normalizar(array $row): array
{
    $row['media'] = isset($row['media']) ? (float) $row['media'] : null;
    $row['totalAvaliacoes'] = (int) ($row['totalAvaliacoes'] ?? 0);
    $row['totalVagas'] = (int) ($row['totalVagas'] ?? 0);
    return $row;
}

function empresas_listar(string $busca = ''): array
{
    $pdo = db_tentar();
    if (!$pdo) return [];
    $sql = "SELECT e.*, (SELECT ROUND(AVG(a.nota),1) FROM avaliacao a WHERE a.empresaId=e.id) AS media,
            (SELECT COUNT(*) FROM avaliacao a WHERE a.empresaId=e.id) AS totalAvaliacoes,
            0 AS totalVagas FROM empresa e";
    $params=[];
    if (trim($busca)!=='') { $sql .= " WHERE (e.nome LIKE :nome OR e.cidade LIKE :cidade OR e.descricao LIKE :descricao)";
      $params=[':nome'=>'%'.$busca.'%', ':cidade'=>'%'.$busca.'%', ':descricao'=>'%'.$busca.'%']; }
    $sql .= " ORDER BY e.nome";
    $stmt=$pdo->prepare($sql); $stmt->execute($params);
    return array_map('empresa_normalizar', $stmt->fetchAll());
}

function empresa_buscar(int $id): ?array
{
    if ($id<=0 || !($pdo=db_tentar())) return null;
    $stmt=$pdo->prepare("SELECT e.*, (SELECT ROUND(AVG(a.nota),1) FROM avaliacao a WHERE a.empresaId=e.id) AS media,
      (SELECT COUNT(*) FROM avaliacao a WHERE a.empresaId=e.id) AS totalAvaliacoes, 0 AS totalVagas FROM empresa e WHERE e.id=? LIMIT 1");
    $stmt->execute([$id]); $row=$stmt->fetch(); return $row ? empresa_normalizar($row) : null;
}

function avaliacoes_empresa(int $empresaId): array
{
    $pdo = db_tentar();
    if (!$pdo) return [];
    try {
        $sql = "SELECT a.*, c.nome AS candidatoNome
                FROM avaliacao a JOIN candidato c ON c.id = a.candidatoId
                WHERE a.empresaId = ? ORDER BY a.dataAvaliacao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$empresaId]);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

function avaliacao_salvar(int $empresaId, int $candidatoId, int $nota, string $comentario, array $recursos): bool
{
    $pdo = db();
    $stmt = $pdo->prepare('INSERT INTO avaliacao (empresaId, candidatoId, nota, comentario, possuiRampa, possuiBanheiroAdaptado, possuiSinalizacaoBraile, possuiAcessibilidadeDigital, dataAvaliacao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
    $stmt->execute([
        $empresaId,
        $candidatoId,
        max(1, min(5, $nota)),
        $comentario ?: null,
        !empty($recursos['rampa']) ? 1 : 0,
        !empty($recursos['banheiro']) ? 1 : 0,
        !empty($recursos['braile']) ? 1 : 0,
        !empty($recursos['digital']) ? 1 : 0,
    ]);
    return true;
}

function estatisticas_site(): array
{
    $pdo=db_tentar();
    $result=['vagas'=>count(vagas_demo()), 'empresas'=>0, 'candidatos'=>0, 'candidaturas'=>0, 'recursos'=>count(recurso_mapa())];
    if (!$pdo) return $result;
    $result['empresas']=(int)$pdo->query('SELECT COUNT(*) FROM empresa')->fetchColumn();
    $result['candidatos']=(int)$pdo->query('SELECT COUNT(*) FROM candidato')->fetchColumn();
    return $result;
}

function empresas_destaque(int $limite = 3): array
{
    return array_slice(empresas_listar(), 0, $limite);
}

function contato_salvar(array $dados): bool
{
    return false; // Sem tabela de mensagens: não afirmar que o contato foi enviado.
}

function alerta_salvar(string $email, string $cidade, string $modalidade): bool
{
    return false; // Sem tabela de alertas: não afirmar que foi cadastrado.
}
