<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados_empresas.php';
require_once __DIR__ . '/../includes/componentes.php';

$pageTitle = 'Empresas parceiras';
$pageDescription = 'Conheça as empresas que publicam vagas com acessibilidade verificada no IncluiTrabalho.';
$pageClass = 'companies-page';
$busca = get('q');
$empresas = empresas_listar($busca);

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <section class="page-intro">
    <div class="container">
      <nav class="breadcrumb" aria-label="Você está em">
        <a href="<?= e($base) ?>index.php">Início</a>
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
        <span aria-current="page">Empresas</span>
      </nav>
      <span class="eyebrow"><?= count($empresas) ?> empresa(s) parceira(s)</span>
      <h1>Empresas que contratam com inclusão</h1>
      <p>Conheça as organizações que publicam vagas no IncluiTrabalho, seus recursos de acessibilidade e avaliações de candidatos.</p>
      <form class="search-card" method="GET" action="busca.php" role="search" aria-label="Buscar empresas">
        <div class="search-field">
          <i class="bi bi-search" aria-hidden="true"></i>
          <label class="sr-only" for="q">Buscar empresa</label>
          <input id="q" name="q" type="search" value="<?= e($busca) ?>" placeholder="Nome da empresa, cidade ou área de atuação">
        </div>
        <button class="btn btn-green" type="submit">Buscar empresa</button>
      </form>
    </div>
  </section>

  <div class="container" style="padding-top:2rem;padding-bottom:4rem">
    <?php if ($empresas): ?>
      <div class="company-grid">
        <?php foreach ($empresas as $empresa): ?>
          <article class="company-card">
            <div class="company-card-head">
              <span class="company-avatar <?= e(avatar_classe($empresa['id'])) ?>" aria-hidden="true"><?= e(iniciais($empresa['nome'])) ?></span>
              <div>
                <h2><?= e($empresa['nome']) ?></h2>
                <small><i class="bi bi-geo-alt" aria-hidden="true"></i> <?= e($empresa['cidade'] ?: 'Brasil') ?></small>
              </div>
            </div>
            <p><?= e(resumir($empresa['descricao'] ?: 'Empresa parceira do IncluiTrabalho.', 140)) ?></p>
            <div class="meta-row">
              <span><i class="bi bi-briefcase" aria-hidden="true"></i> <?= (int) $empresa['totalVagas'] ?> vaga(s)</span>
              <?php if ($empresa['totalAvaliacoes'] > 0): ?>
                <span class="rating"><?= estrelas($empresa['media'], 'Nota ' . $empresa['media'] . ' de 5') ?> <?= e($empresa['media']) ?></span>
              <?php else: ?>
                <span><i class="bi bi-star" aria-hidden="true"></i> Sem avaliações</span>
              <?php endif; ?>
            </div>
            <a class="btn btn-outline btn-sm" href="<?= e($base) ?>empresa.php?id=<?= (int) $empresa['id'] ?>">Ver perfil e vagas</a>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty">
        <i class="bi bi-buildings" aria-hidden="true"></i>
        <h3>Nenhuma empresa encontrada</h3>
        <p>Tente outro termo ou cadastre sua empresa para aparecer aqui.</p>
        <a class="btn btn-green" href="salvarEmpresa.php">Cadastrar empresa</a>
      </div>
    <?php endif; ?>

    <div class="cta-band" style="margin-top:2.5rem">
      <div>
        <h2>Sua empresa também pode fazer parte</h2>
        <p>Cadastre-se gratuitamente, informe seus recursos de acessibilidade e publique vagas.</p>
      </div>
      <a class="btn btn-light" href="<?= e($base) ?>telas/salvarEmpresa.php">Cadastrar empresa <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
