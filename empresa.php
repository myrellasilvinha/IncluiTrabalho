<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';
require_once __DIR__ . '/includes/dados_empresas.php';
require_once __DIR__ . '/includes/componentes.php';

$id = (int) get('id');
$empresa = empresa_buscar($id);

if (!$empresa) {
    http_response_code(404);
    $pageTitle = 'Empresa não encontrada';
    require __DIR__ . '/includes/head.php';
    require __DIR__ . '/includes/cabecalho.php';
    ?>
    <main id="conteudo" class="container"><div class="not-found">
      <p class="code">404</p><h1>Empresa não encontrada</h1>
      <p>Não localizamos esta empresa. Veja as organizações parceiras do IncluiTrabalho.</p>
      <div class="hero-actions"><a class="btn btn-green" href="<?= e($base) ?>telas/busca.php">Ver empresas</a></div>
    </div></main>
    <?php require __DIR__ . '/includes/rodape.php';
    exit;
}

$avaliacoes = avaliacoes_empresa($id);
$candidato = candidato_logado();
$vagasEmpresa = array_values(array_filter(vagas_listar(['ordem' => 'recentes']), fn($v) => (int) $v['empresaId'] === $id));
$salvas = $candidato ? vagas_salvas_ids((int) $candidato->id) : [];

$pageTitle = $empresa['nome'] . ' — vagas e avaliações';
$pageDescription = resumir('Conheça ' . $empresa['nome'] . ' (' . ($empresa['cidade'] ?: 'Brasil') . '): vagas abertas, recursos de acessibilidade e avaliações. ' . ($empresa['descricao'] ?? ''), 155);
$pageClass = 'company-page';

$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $empresa['nome'],
    'description' => $empresa['descricao'] ?: null,
    'address' => $empresa['cidade'] ?: null,
    'url' => $empresa['site'] ?: null,
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container" style="padding-top:1.6rem;padding-bottom:4rem">
    <nav class="breadcrumb" aria-label="Você está em">
      <a href="<?= e($base) ?>index.php">Início</a>
      <i class="bi bi-chevron-right" aria-hidden="true"></i>
      <a href="<?= e($base) ?>telas/busca.php">Empresas</a>
      <i class="bi bi-chevron-right" aria-hidden="true"></i>
      <span aria-current="page"><?= e($empresa['nome']) ?></span>
    </nav>

    <header class="company-hero">
      <span class="company-avatar avatar-xl <?= e(avatar_classe($empresa['id'])) ?>" aria-hidden="true"><?= e(iniciais($empresa['nome'])) ?></span>
      <div>
        <h1><?= e($empresa['nome']) ?></h1>
        <p><i class="bi bi-geo-alt" aria-hidden="true"></i> <?= e($empresa['cidade'] ?: 'Local não informado') ?>
          <?php if ($empresa['totalAvaliacoes'] > 0): ?> · <?= estrelas($empresa['media'], 'Nota ' . $empresa['media'] . ' de 5') ?> <?= e($empresa['media']) ?> (<?= (int) $empresa['totalAvaliacoes'] ?>)<?php endif; ?>
        </p>
      </div>
      <div class="company-actions">
        <a class="btn btn-green" href="#vagas-empresa">Ver vagas (<?= count($vagasEmpresa) ?>)</a>
        <a class="btn btn-outline" href="#avaliacoes">Avaliações</a>
      </div>
    </header>

    <div class="company-layout">
      <div>
        <section class="panel" style="margin-bottom:1rem">
          <h2>Sobre a empresa</h2>
          <p><?= nl2br(e($empresa['descricao'] ?: 'Esta empresa ainda não adicionou uma descrição ao perfil.')) ?></p>
          <div class="meta-row" style="display:flex;flex-wrap:wrap;gap:.8rem;margin-top:1rem;font-size:.84rem;color:var(--muted)">
            <span><i class="bi bi-briefcase" aria-hidden="true"></i> <?= (int) $empresa['totalVagas'] ?> vaga(s) aberta(s)</span>
            <?php if (!empty($empresa['site'])): ?>
              <span><i class="bi bi-globe" aria-hidden="true"></i> <a class="link" href="<?= e($empresa['site']) ?>" target="_blank" rel="noopener noreferrer">Site da empresa</a></span>
            <?php endif; ?>
            <?php if (!empty($empresa['telefone']) && $candidato): ?>
              <span><i class="bi bi-telephone" aria-hidden="true"></i> <?= e($empresa['telefone']) ?></span>
            <?php endif; ?>
          </div>
        </section>

        <section id="vagas-empresa" style="margin-top:1.6rem">
          <div class="section-head">
            <div><h2>Vagas abertas</h2><p>Oportunidades publicadas por <?= e($empresa['nome']) ?>.</p></div>
          </div>
          <?php if ($vagasEmpresa): ?>
            <div class="jobs-grid">
              <?php foreach ($vagasEmpresa as $vaga): ?>
                <?= vaga_card($vaga, ['salvas' => $salvas, 'logado' => (bool) $candidato]) ?>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="empty">
              <i class="bi bi-briefcase" aria-hidden="true"></i>
              <h3>Nenhuma vaga aberta no momento</h3>
              <p>Salve a empresa nos favoritos e volte em breve para conferir novas oportunidades.</p>
              <a class="btn btn-outline" href="<?= e($base) ?>vagas.php">Ver outras vagas</a>
            </div>
          <?php endif; ?>
        </section>

        <section id="avaliacoes" style="margin-top:1.8rem">
          <div class="section-head">
            <div><h2>Avaliações de candidatos</h2><p>Relatos de quem já se candidatou ou trabalhou na empresa.</p></div>
            <?php if ($empresa['totalAvaliacoes'] > 0): ?>
              <div class="rating"><?= estrelas($empresa['media'], 'Média ' . $empresa['media'] . ' de 5') ?> <?= e($empresa['media']) ?> <small>(<?= (int) $empresa['totalAvaliacoes'] ?>)</small></div>
            <?php endif; ?>
          </div>

          <?php if ($avaliacoes): ?>
            <?php foreach ($avaliacoes as $avaliacao): ?>
              <article class="review-card">
                <div class="review-head">
                  <div class="review-author">
                    <span class="company-avatar <?= e(avatar_classe($avaliacao['candidatoId'])) ?>" style="width:2.4rem;height:2.4rem;font-size:.8rem" aria-hidden="true"><?= e(iniciais($avaliacao['candidatoNome'])) ?></span>
                    <div><strong><?= e($avaliacao['candidatoNome']) ?></strong><small><?= e(formatar_data($avaliacao['dataAvaliacao'])) ?></small></div>
                  </div>
                  <div class="rating"><?= estrelas((int) $avaliacao['nota'], 'Nota ' . $avaliacao['nota'] . ' de 5') ?></div>
                </div>
                <?php if (!empty($avaliacao['comentario'])): ?><p><?= nl2br(e($avaliacao['comentario'])) ?></p><?php endif; ?>
                <div class="review-tags">
                  <?php if ($avaliacao['possuiRampa']): ?><span class="pill pill-green"><i class="bi bi-check2" aria-hidden="true"></i> Rampa</span><?php endif; ?>
                  <?php if ($avaliacao['possuiBanheiroAdaptado']): ?><span class="pill pill-green"><i class="bi bi-check2" aria-hidden="true"></i> Banheiro adaptado</span><?php endif; ?>
                  <?php if ($avaliacao['possuiSinalizacaoBraile']): ?><span class="pill pill-green"><i class="bi bi-check2" aria-hidden="true"></i> Braile</span><?php endif; ?>
                  <?php if ($avaliacao['possuiAcessibilidadeDigital']): ?><span class="pill pill-green"><i class="bi bi-check2" aria-hidden="true"></i> Acessibilidade digital</span><?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty" style="margin-bottom:1rem">
              <i class="bi bi-chat-square-text" aria-hidden="true"></i>
              <h3>Ainda não há avaliações</h3>
              <p>Se você já teve contato com esta empresa, compartilhe sua experiência para ajudar outras pessoas.</p>
            </div>
          <?php endif; ?>

          <?php if ($candidato): ?>
            <form class="review-form" method="POST" action="<?= e($base) ?>controladores/rota.php?acao=avaliar" data-validate>
              <?= csrf_field() ?>
              <input type="hidden" name="empresaId" value="<?= $id ?>">
              <h2>Avaliar <?= e($empresa['nome']) ?></h2>
              <div class="field">
                <label for="nota">Sua nota</label>
                <select id="nota" name="nota" required>
                  <option value="5">5 — Excelente</option>
                  <option value="4">4 — Muito bom</option>
                  <option value="3">3 — Bom</option>
                  <option value="2">2 — Regular</option>
                  <option value="1">1 — Ruim</option>
                </select>
              </div>
              <div class="field">
                <label for="comentario">Comentário (opcional)</label>
                <textarea id="comentario" name="comentario" rows="3" placeholder="Conte como foi sua experiência com acessibilidade e processo seletivo."></textarea>
              </div>
              <fieldset>
                <legend>Recursos que você encontrou</legend>
                <div class="check-list">
                  <label><input type="checkbox" name="recursos[rampa]" value="1"> Rampa de acesso</label>
                  <label><input type="checkbox" name="recursos[banheiro]" value="1"> Banheiro adaptado</label>
                  <label><input type="checkbox" name="recursos[braile]" value="1"> Sinalização em braile</label>
                  <label><input type="checkbox" name="recursos[digital]" value="1"> Acessibilidade digital</label>
                </div>
              </fieldset>
              <button class="btn btn-green" type="submit"><i class="bi bi-send" aria-hidden="true"></i> Enviar avaliação</button>
            </form>
          <?php else: ?>
            <div class="panel" style="margin-top:1rem">
              <h2>Já teve contato com esta empresa?</h2>
              <p>Entre na sua conta de candidato para avaliar a acessibilidade e o processo seletivo.</p>
              <a class="btn btn-green" href="<?= e($base) ?>telas/autenticarCandidato.php">Entrar para avaliar</a>
            </div>
          <?php endif; ?>
        </section>
      </div>

      <aside style="display:grid;gap:1rem">
        <div class="panel">
          <h2 style="font-size:1.05rem">Resumo</h2>
          <div class="check-line"><i class="bi bi-briefcase" aria-hidden="true"></i><div><strong><?= count($vagasEmpresa) ?> vagas abertas</strong><span>Oportunidades publicadas agora.</span></div></div>
          <div class="check-line"><i class="bi bi-star" aria-hidden="true"></i><div><strong><?= $empresa['totalAvaliacoes'] > 0 ? e($empresa['media']) . ' de 5' : 'Sem nota' ?></strong><span><?= (int) $empresa['totalAvaliacoes'] ?> avaliação(ões) de candidatos.</span></div></div>
          <div class="check-line"><i class="bi bi-geo-alt" aria-hidden="true"></i><div><strong><?= e($empresa['cidade'] ?: 'Brasil') ?></strong><span>Localização principal.</span></div></div>
        </div>
        <div class="panel">
          <h2 style="font-size:1.05rem">É esta a sua empresa?</h2>
          <p style="font-size:.85rem">Gerencie o perfil, publique vagas e responda candidaturas na área da empresa.</p>
          <a class="btn btn-outline" href="<?= e($base) ?>telas/autenticar.php" style="width:100%">Acessar painel</a>
        </div>
      </aside>
    </div>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
