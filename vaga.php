<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';
require_once __DIR__ . '/includes/dados_empresas.php';
require_once __DIR__ . '/includes/componentes.php';

$id = (int) get('id');
$vaga = vaga_buscar($id);

if (!$vaga) {
    http_response_code(404);
    $pageTitle = 'Vaga não encontrada';
    $pageDescription = 'A vaga que você procura não está mais disponível. Veja outras oportunidades com acessibilidade verificada.';
    require __DIR__ . '/includes/head.php';
    require __DIR__ . '/includes/cabecalho.php';
    ?>
    <main id="conteudo" class="container">
      <div class="not-found">
        <p class="code">404</p>
        <h1>Vaga não encontrada</h1>
        <p>Esta vaga pode ter sido encerrada ou removida. Confira as oportunidades abertas no momento.</p>
        <div class="hero-actions">
          <a class="btn btn-green" href="<?= e($base) ?>vagas.php">Ver vagas abertas</a>
          <a class="btn btn-outline" href="<?= e($base) ?>index.php">Voltar ao início</a>
        </div>
      </div>
    </main>
    <?php require __DIR__ . '/includes/rodape.php';
    exit;
}

$candidato = candidato_logado();
$empresaLogada = empresa_logada();
$candidatura = $candidato ? candidatura_buscar($id, (int) $candidato->id) : null;
$salvas = $candidato ? vagas_salvas_ids((int) $candidato->id) : [];
$salva = in_array($id, $salvas, true);
$recursos = $vaga['recursos'] ?? [];
$relacionadas = vagas_relacionadas($id, (int) $vaga['empresaId'], 3);
$fechada = ($vaga['status'] ?? 'Aberta') !== 'Aberta';
$empresa = empresa_buscar((int) $vaga['empresaId']);

$pageTitle = $vaga['titulo'] . ' — ' . $vaga['empresaNome'];
$pageDescription = resumir(($vaga['titulo'] ?? '') . ' em ' . ($vaga['empresaNome'] ?? '') . ': ' . ($vaga['descricao'] ?? ''), 155);
$pageClass = 'job-detail-page';

$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'JobPosting',
    'title' => $vaga['titulo'],
    'description' => $vaga['descricao'],
    'datePosted' => date('c', strtotime($vaga['dataPublicacao'])),
    'employmentType' => $vaga['tipoContrato'] ?: 'OTHER',
    'hiringOrganization' => [
        '@type' => 'Organization',
        'name' => $vaga['empresaNome'],
    ],
    'jobLocation' => [
        '@type' => 'Place',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $vaga['localizacao'] ?: 'Brasil',
            'addressCountry' => 'BR',
        ],
    ],
    'directApply' => true,
];
if ($vaga['salario']) {
    $jsonLd['baseSalary'] = [
        '@type' => 'MonetaryAmount',
        'currency' => 'BRL',
        'value' => ['@type' => 'QuantitativeValue', 'value' => (float) $vaga['salario'], 'unitText' => 'MONTH'],
    ];
}
if ($vaga['modalidade'] === 'Remoto') {
    $jsonLd['jobLocationType'] = 'TELECOMMUTE';
}

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container" style="padding-top:1.6rem">
    <nav class="breadcrumb" aria-label="Você está em">
      <a href="<?= e($base) ?>index.php">Início</a>
      <i class="bi bi-chevron-right" aria-hidden="true"></i>
      <a href="<?= e($base) ?>vagas.php">Vagas</a>
      <i class="bi bi-chevron-right" aria-hidden="true"></i>
      <span aria-current="page"><?= e(resumir($vaga['titulo'], 48)) ?></span>
    </nav>

    <div class="detail-layout">
      <div class="detail-main">
        <article class="detail-hero">
          <div class="detail-head">
            <div>
              <div class="job-company" style="margin-bottom:.6rem">
                <?= avatar_empresa($vaga) ?>
                <div>
                  <strong><?= e($vaga['empresaNome']) ?></strong>
                  <div class="muted" style="font-size:.8rem"><i class="bi bi-geo-alt" aria-hidden="true"></i> <?= e($vaga['localizacao'] ?: 'Local a combinar') ?></div>
                </div>
              </div>
              <h1><?= e($vaga['titulo']) ?></h1>
              <p class="detail-meta">
                <span><i class="bi <?= e(modalidade_icone($vaga['modalidade'])) ?>" aria-hidden="true"></i> <?= e($vaga['modalidade']) ?></span>
                <?php if ($vaga['tipoContrato']): ?><span><i class="bi bi-briefcase" aria-hidden="true"></i> <?= e($vaga['tipoContrato']) ?></span><?php endif; ?>
                <?php if ($vaga['nivel']): ?><span><i class="bi bi-bar-chart" aria-hidden="true"></i> <?= e($vaga['nivel']) ?></span><?php endif; ?>
                <span><i class="bi bi-cash-coin" aria-hidden="true"></i> <?= e(formatar_salario($vaga['salario'])) ?></span>
                <span><i class="bi bi-clock" aria-hidden="true"></i> Publicada <?= e(tempo_relativo($vaga['dataPublicacao'])) ?></span>
              </p>
              <div class="job-tags" style="margin-top:.9rem">
                <?php if ($vaga['verificada']): ?>
                  <span class="badge badge-verified"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Acessibilidade verificada</span>
                <?php endif; ?>
                <?php if ($fechada): ?>
                  <span class="badge badge-muted">Vaga encerrada</span>
                <?php else: ?>
                  <span class="badge badge-soft">Aberta para candidaturas</span>
                <?php endif; ?>
                <?php foreach (array_slice($recursos, 0, 3) as $recurso): ?>
                  <span class="pill"><i class="bi <?= e(recurso_icone($recurso)) ?>" aria-hidden="true"></i> <?= e(recurso_curto($recurso)) ?></span>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </article>

        <section class="detail-body" aria-labelledby="sobre-vaga">
          <h2 id="sobre-vaga">Sobre a vaga</h2>
          <?php foreach (preg_split('/\n\s*\n/', trim((string) $vaga['descricao'])) as $paragrafo): ?>
            <?php if (trim($paragrafo) !== ''): ?><p><?= nl2br(e($paragrafo)) ?></p><?php endif; ?>
          <?php endforeach; ?>

          <?php if (!empty($vaga['requisitos'])): ?>
            <h2>Requisitos</h2>
            <?php $itens = preg_split('/\r?\n/', trim($vaga['requisitos'])); ?>
            <ul>
              <?php foreach ($itens as $item): ?>
                <?php if (trim($item) !== ''): ?><li><?= e(trim($item, " -•\t")) ?></li><?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if (!empty($vaga['beneficios'])): ?>
            <h2>Benefícios</h2>
            <?php $itens = preg_split('/\r?\n/', trim($vaga['beneficios'])); ?>
            <ul>
              <?php foreach ($itens as $item): ?>
                <?php if (trim($item) !== ''): ?><li><?= e(trim($item, " -•\t")) ?></li><?php endif; ?>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <h2>Acessibilidade no trabalho</h2>
          <p>A empresa informou os recursos abaixo para esta vaga. Em caso de dúvida, converse com a equipe antes ou durante o processo seletivo.</p>
          <?php if ($recursos): ?>
            <div class="access-grid">
              <?php foreach ($recursos as $recurso): ?>
                <div class="access-item is-on">
                  <i class="bi <?= e(recurso_icone($recurso)) ?>" aria-hidden="true"></i>
                  <strong><?= e($recurso) ?></strong>
                  <p><?= e(recurso_descricao($recurso)) ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="info-note">A empresa não informou recursos específicos para esta vaga. Recomendamos entrar em contato para confirmar as condições de acessibilidade.</div>
          <?php endif; ?>

          <?php if ($vaga['verificada']): ?>
            <p style="margin-top:1rem;font-size:.82rem"><i class="bi bi-patch-check-fill text-green" aria-hidden="true"></i> Recursos verificados pela equipe do IncluiTrabalho em <?= e(formatar_data($vaga['dataVerificacao'] ?? $vaga['dataPublicacao'])) ?>.</p>
          <?php endif; ?>
        </section>
      </div>

      <aside class="detail-side" aria-label="Ações e informações da vaga">
        <div class="apply-card">
          <h2><?= $fechada ? 'Vaga encerrada' : 'Quero me candidatar' ?></h2>
          <?php if ($candidatura): ?>
            <p>Você já se candidatou. Acompanhe o andamento na sua área.</p>
            <span class="status-pill <?= e(status_candidatura_classe($candidatura['status'])) ?>" style="width:100%;justify-content:center;margin-bottom:.7rem">
              <i class="bi bi-clock-history" aria-hidden="true"></i> Status: <?= e($candidatura['status']) ?>
            </span>
            <a class="btn btn-outline" href="<?= e($base) ?>telas/homeCandidato.php">Ver minhas candidaturas</a>
          <?php elseif ($fechada): ?>
            <p>Esta vaga não recebe mais candidaturas. Veja outras oportunidades parecidas.</p>
            <a class="btn btn-green" href="<?= e($base) ?>vagas.php">Ver vagas abertas</a>
          <?php elseif ($candidato): ?>
            <p>Envie seu perfil para a empresa. Seu currículo e recursos de acessibilidade serão compartilhados.</p>
            <form method="POST" action="<?= e($base) ?>controladores/rota.php?acao=candidatar">
              <?= csrf_field() ?>
              <input type="hidden" name="vagaId" value="<?= $id ?>">
              <button class="btn btn-green" type="submit"><i class="bi bi-send" aria-hidden="true"></i> Candidatar-se agora</button>
            </form>
            <p class="apply-note">Candidatura gratuita. Você pode acompanhar o retorno na sua conta.</p>
          <?php else: ?>
            <p>Entre ou crie sua conta para enviar seu perfil a esta vaga.</p>
            <a class="btn btn-green" href="<?= e($base) ?>telas/autenticarCandidato.php?redirect=<?= urlencode('vaga.php?id=' . $id) ?>">Entrar e candidatar</a>
            <a class="btn btn-outline" href="<?= e($base) ?>telas/cadastrarCandidato.php">Criar conta grátis</a>
          <?php endif; ?>

          <?php if (!$fechada): ?>
            <button class="btn btn-outline" type="button" data-salvar-vaga="<?= $id ?>" aria-pressed="<?= $salva ? 'true' : 'false' ?>" style="margin-top:.45rem">
              <i class="bi <?= $salva ? 'bi-heart-fill' : 'bi-heart' ?>" aria-hidden="true"></i>
              <span data-save-label><?= $salva ? 'Vaga salva' : 'Salvar vaga' ?></span>
            </button>
          <?php endif; ?>

          <div class="share-row">
            <button class="btn btn-outline" type="button" data-copy="<?= e(absolute_url('vaga.php?id=' . $id)) ?>"><i class="bi bi-link-45deg" aria-hidden="true"></i> Copiar link</button>
            <a class="btn btn-outline" href="https://wa.me/?text=<?= urlencode($vaga['titulo'] . ' — ' . absolute_url('vaga.php?id=' . $id)) ?>" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp" aria-hidden="true"></i> WhatsApp</a>
          </div>
        </div>

        <div class="apply-card">
          <div class="company-mini">
            <?= avatar_empresa($vaga) ?>
            <div>
              <strong><?= e($vaga['empresaNome']) ?></strong>
              <small><?= e($vaga['empresaCidade'] ?: $vaga['localizacao']) ?></small>
            </div>
          </div>
          <?php if ($empresa && $empresa['totalAvaliacoes'] > 0): ?>
            <div class="rating" style="margin-bottom:.5rem"><?= estrelas($empresa['media'], 'Nota ' . $empresa['media'] . ' de 5') ?> <?= e($empresa['media']) ?> <small>(<?= (int) $empresa['totalAvaliacoes'] ?> avaliações)</small></div>
          <?php endif; ?>
          <p style="font-size:.8rem;color:var(--muted);margin:0 0 .8rem">Conheça a empresa, veja outras vagas e avaliações de quem já trabalhou lá.</p>
          <a class="btn btn-outline btn-sm" href="<?= e($base) ?>empresa.php?id=<?= (int) $vaga['empresaId'] ?>" style="width:100%">Perfil da empresa</a>
        </div>

        <?php if ($relacionadas): ?>
          <div class="apply-card">
            <h2 style="margin-bottom:.6rem">Vagas parecidas</h2>
            <div class="related-jobs">
              <?php foreach ($relacionadas as $relacionada): ?>
                <?= vaga_card($relacionada, ['compacto' => true, 'mostrar_salvar' => false]) ?>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
