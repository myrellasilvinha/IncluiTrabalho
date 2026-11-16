<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';
require_once __DIR__ . '/includes/componentes.php';

$pageTitle = 'Buscar vagas';
$pageDescription = 'Busque vagas com acessibilidade verificada por cargo, cidade, modalidade e recursos como Libras, rampa e trabalho remoto.';
$pageClass = 'jobs-page';

$filtros = [
    'q' => get('q'),
    'cidade' => get('cidade'),
    'modalidade' => get('modalidade'),
    'tipo' => get('tipo'),
    'recursos' => isset($_GET['recursos']) && is_array($_GET['recursos']) ? array_values(array_filter(array_map('strval', $_GET['recursos']))) : [],
    'verificada' => get('verificada') === '1',
    'ordem' => get('ordem', 'recentes') === 'salario' ? 'salario' : 'recentes',
];
$vagas = vagas_listar($filtros);
$todas = vagas_listar(['ordem' => 'recentes']);
$salvas = candidato_logado() ? vagas_salvas_ids((int) candidato_logado()->id) : [];
$recursos = recursos_disponiveis();

$modalidades = [];
$tipos = [];
foreach ($todas as $v) {
    if (!empty($v['modalidade'])) $modalidades[$v['modalidade']] = ($modalidades[$v['modalidade']] ?? 0) + 1;
    if (!empty($v['tipoContrato'])) $tipos[$v['tipoContrato']] = ($tipos[$v['tipoContrato']] ?? 0) + 1;
}
ksort($modalidades);
ksort($tipos);

$ativos = [];
if ($filtros['q'] !== '') $ativos[] = ['rotulo' => 'Busca: ' . $filtros['q'], 'remover' => 'q'];
if ($filtros['cidade'] !== '') $ativos[] = ['rotulo' => 'Cidade: ' . $filtros['cidade'], 'remover' => 'cidade'];
if ($filtros['modalidade'] !== '') $ativos[] = ['rotulo' => $filtros['modalidade'], 'remover' => 'modalidade'];
if ($filtros['tipo'] !== '') $ativos[] = ['rotulo' => $filtros['tipo'], 'remover' => 'tipo'];
if ($filtros['verificada']) $ativos[] = ['rotulo' => 'Somente verificadas', 'remover' => 'verificada'];
foreach ($filtros['recursos'] as $r) $ativos[] = ['rotulo' => $r, 'remover' => 'recurso:' . $r];

function url_filtros(array $filtros, array $mudancas = []): string
{
    $novo = $filtros;
    foreach ($mudancas as $chave => $valor) {
        if ($valor === null) unset($novo[$chave]);
        else $novo[$chave] = $valor;
    }
    $query = http_build_query(array_filter($novo, function ($v) {
        return !($v === '' || $v === false || $v === []);
    }));
    return 'vagas.php' . ($query ? '?' . $query : '');
}

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <section class="page-intro">
    <div class="container">
      <nav class="breadcrumb" aria-label="Você está em">
        <a href="<?= e($base) ?>index.php">Início</a>
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
        <span aria-current="page">Vagas</span>
      </nav>
      <span class="eyebrow"><?= e(paginacao_info(count($todas), count($vagas))) ?></span>
      <h1>Encontre uma vaga que respeite você</h1>
      <p>Filtre por cidade, modalidade e recursos de acessibilidade. Todas as vagas verificadas passaram por checagem das informações.</p>

      <form class="search-card" action="vagas.php" method="GET" role="search" aria-label="Buscar vagas">
        <div class="search-field">
          <i class="bi bi-search" aria-hidden="true"></i>
          <label class="sr-only" for="q">Cargo ou palavra-chave</label>
          <input id="q" name="q" type="search" value="<?= e($filtros['q']) ?>" placeholder="Cargo, palavra-chave ou empresa">
        </div>
        <div class="search-field">
          <i class="bi bi-geo-alt" aria-hidden="true"></i>
          <label class="sr-only" for="cidade">Cidade</label>
          <input id="cidade" name="cidade" type="search" value="<?= e($filtros['cidade']) ?>" placeholder="Cidade ou estado">
        </div>
        <button class="btn btn-green" type="submit">Buscar</button>
      </form>
    </div>
  </section>

  <div class="container">
    <div class="jobs-layout">
      <button class="btn btn-outline filter-toggle" type="button" data-filter-toggle aria-expanded="false" aria-controls="filtros">
        <i class="bi bi-funnel" aria-hidden="true"></i> Mostrar filtros
      </button>

      <form class="filter-panel" id="filtros" method="GET" action="vagas.php" aria-label="Filtros de vagas">
        <h2><i class="bi bi-sliders2" aria-hidden="true"></i> Filtros</h2>
        <?php if ($filtros['q'] !== ''): ?><input type="hidden" name="q" value="<?= e($filtros['q']) ?>"><?php endif; ?>
        <?php if ($filtros['cidade'] !== ''): ?><input type="hidden" name="cidade" value="<?= e($filtros['cidade']) ?>"><?php endif; ?>

        <div class="filter-group">
          <p class="filter-title">Modalidade</p>
          <?php foreach ($modalidades as $nome => $qtd): ?>
            <label class="check">
              <input type="radio" name="modalidade" value="<?= e($nome) ?>" <?= $filtros['modalidade'] === $nome ? 'checked' : '' ?>>
              <span><?= e($nome) ?></span><span class="count"><?= $qtd ?></span>
            </label>
          <?php endforeach; ?>
          <?php if ($filtros['modalidade'] !== ''): ?>
            <a class="chip-remove" href="<?= e(url_filtros($filtros, ['modalidade' => null])) ?>"><i class="bi bi-x-lg" aria-hidden="true"></i> Limpar modalidade</a>
          <?php endif; ?>
        </div>

        <div class="filter-group">
          <p class="filter-title">Tipo de contrato</p>
          <?php foreach ($tipos as $nome => $qtd): ?>
            <label class="check">
              <input type="radio" name="tipo" value="<?= e($nome) ?>" <?= $filtros['tipo'] === $nome ? 'checked' : '' ?>>
              <span><?= e($nome) ?></span><span class="count"><?= $qtd ?></span>
            </label>
          <?php endforeach; ?>
        </div>

        <div class="filter-group">
          <p class="filter-title">Acessibilidade</p>
          <?php foreach ($recursos as $recurso): ?>
            <label class="check">
              <input type="checkbox" name="recursos[]" value="<?= e($recurso['nome']) ?>" <?= in_array($recurso['nome'], $filtros['recursos'], true) ? 'checked' : '' ?>>
              <span><?= e($recurso['nome']) ?></span>
            </label>
          <?php endforeach; ?>
        </div>

        <div class="filter-group">
          <label class="check">
            <input type="checkbox" name="verificada" value="1" <?= $filtros['verificada'] ? 'checked' : '' ?>>
            <span>Somente vagas verificadas</span>
          </label>
        </div>

        <div class="filter-actions">
          <button class="btn btn-green btn-sm" type="submit">Aplicar filtros</button>
          <a class="btn btn-outline btn-sm" href="vagas.php">Limpar tudo</a>
        </div>
      </form>

      <div>
        <div class="results-head">
          <h2><?= e(paginacao_info(count($todas), count($vagas))) ?> <span><?= $filtros['q'] !== '' ? 'para “' . e($filtros['q']) . '”' : '' ?></span></h2>
          <form class="sort-wrap" method="GET" action="vagas.php" aria-label="Ordenar resultados">
            <?php foreach (['q', 'cidade', 'modalidade', 'tipo', 'verificada'] as $campo): ?>
              <?php if ($filtros[$campo] !== '' && $filtros[$campo] !== false): ?><input type="hidden" name="<?= e($campo) ?>" value="<?= e($campo === 'verificada' ? '1' : $filtros[$campo]) ?>"><?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($filtros['recursos'] as $r): ?><input type="hidden" name="recursos[]" value="<?= e($r) ?>"><?php endforeach; ?>
            <label for="ordem">Ordenar por</label>
            <select id="ordem" name="ordem" data-submit>
              <option value="recentes" <?= $filtros['ordem'] === 'recentes' ? 'selected' : '' ?>>Mais recentes</option>
              <option value="salario" <?= $filtros['ordem'] === 'salario' ? 'selected' : '' ?>>Maior salário</option>
            </select>
            <noscript><button class="btn btn-outline btn-sm" type="submit">Aplicar</button></noscript>
          </form>
        </div>

        <?php if ($ativos): ?>
          <div class="active-filters" aria-label="Filtros ativos">
            <?php foreach ($ativos as $ativo): ?>
              <?php
                $mudanca = $ativo['remover'] === 'verificada' ? ['verificada' => null] : [$ativo['remover'] => null];
                if (str_starts_with($ativo['remover'], 'recurso:')) {
                    $nome = substr($ativo['remover'], 8);
                    $novos = array_values(array_filter($filtros['recursos'], fn($r) => $r !== $nome));
                    $mudanca = ['recursos' => $novos ?: null];
                }
              ?>
              <a class="chip-remove" href="<?= e(url_filtros($filtros, $mudanca)) ?>"><i class="bi bi-x-lg" aria-hidden="true"></i> <?= e($ativo['rotulo']) ?></a>
            <?php endforeach; ?>
            <a class="chip-remove" href="vagas.php"><i class="bi bi-x-circle" aria-hidden="true"></i> Limpar todos</a>
          </div>
        <?php endif; ?>

        <?php if ($vagas): ?>
          <div class="jobs-grid">
            <?php foreach ($vagas as $vaga): ?>
              <?= vaga_card($vaga, ['salvas' => $salvas, 'logado' => (bool) candidato_logado()]) ?>
            <?php endforeach; ?>
          </div>
          <div class="info-note" style="margin-top:1.2rem;display:flex;gap:.6rem;align-items:flex-start">
            <i class="bi bi-info-circle" aria-hidden="true" style="margin-top:.15rem"></i>
            <span>Não encontrou o que procura? <a class="link" href="<?= e($base) ?>contato.php">Fale com a gente</a> ou crie um alerta na página de contato.</span>
          </div>
        <?php else: ?>
          <div class="empty">
            <i class="bi bi-search" aria-hidden="true"></i>
            <h3>Nenhuma vaga encontrada</h3>
            <p>Tente remover alguns filtros ou buscar por termos mais amplos. Novas vagas são publicadas toda semana.</p>
            <a class="btn btn-green" href="vagas.php">Limpar filtros</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
