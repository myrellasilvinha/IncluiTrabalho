<?php
$pageTitle = 'Sobre nós';
$pageDescription = 'Conheça a missão do IncluiTrabalho: conectar pessoas com deficiência a empresas comprometidas com acessibilidade de verdade.';
$pageClass = 'about-page';
require __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';
require_once __DIR__ . '/includes/dados_empresas.php';
$estatisticas = estatisticas_site();
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <section class="page-intro">
    <div class="container">
      <nav class="breadcrumb" aria-label="Você está em"><a href="<?= e($base) ?>index.php">Início</a><i class="bi bi-chevron-right" aria-hidden="true"></i><span aria-current="page">Sobre</span></nav>
      <span class="eyebrow">Nossa missão</span>
      <h1>Trabalho digno para todas as pessoas</h1>
      <p>O IncluiTrabalho nasceu para eliminar uma barreira específica: a falta de informação sobre acessibilidade antes da candidatura.</p>
    </div>
  </section>

  <section class="section">
    <div class="container split">
      <div>
        <h2>Por que o IncluiTrabalho existe</h2>
        <p>Pessoas com deficiência ainda enfrentam processos seletivos que não informam se o local tem rampa, banheiro adaptado, intérprete de Libras ou tecnologia assistiva. Isso gera insegurança, desperdício de tempo e desistência.</p>
        <p>Nossa plataforma organiza essas informações em cada vaga, permite que empresas parceiras sejam avaliadas e dá às pessoas candidatas o controle sobre o que compartilhar.</p>
        <p>Acreditamos que inclusão não é favor nem caridade: é condição para um trabalho bem feito e para uma sociedade mais justa.</p>
      </div>
      <div class="panel">
        <h2>Em números</h2>
        <div class="stat-grid" style="grid-template-columns:1fr 1fr;margin:1rem 0">
          <div class="stat-card"><span class="ico"><i class="bi bi-briefcase" aria-hidden="true"></i></span><div><span class="stat-value"><?= (int) $estatisticas['vagas'] ?></span><span class="stat-label">Vagas abertas</span></div></div>
          <div class="stat-card"><span class="ico"><i class="bi bi-buildings" aria-hidden="true"></i></span><div><span class="stat-value"><?= (int) $estatisticas['empresas'] ?></span><span class="stat-label">Empresas parceiras</span></div></div>
          <div class="stat-card"><span class="ico"><i class="bi bi-people" aria-hidden="true"></i></span><div><span class="stat-value"><?= (int) $estatisticas['candidatos'] ?></span><span class="stat-label">Candidatos cadastrados</span></div></div>
          <div class="stat-card"><span class="ico"><i class="bi bi-universal-access" aria-hidden="true"></i></span><div><span class="stat-value"><?= (int) $estatisticas['recursos'] ?></span><span class="stat-label">Recursos mapeados</span></div></div>
        </div>
        <p class="hint" style="font-size:.78rem">Dados atualizados a partir do banco da plataforma.</p>
      </div>
    </div>
  </section>

  <section class="section section-soft">
    <div class="container">
      <div class="section-heading"><span class="eyebrow navy">Nossos princípios</span><h2>O que orienta cada decisão</h2></div>
      <div class="feature-grid">
        <article class="feature"><span class="ico"><i class="bi bi-eye" aria-hidden="true"></i></span><h3>Transparência</h3><p>Informação de acessibilidade clara antes da candidatura, sem letras miúdas.</p></article>
        <article class="feature"><span class="ico"><i class="bi bi-shield-lock" aria-hidden="true"></i></span><h3>Privacidade</h3><p>Você decide o que compartilhar. Dados sensíveis não são exigidos no cadastro.</p></article>
        <article class="feature"><span class="ico"><i class="bi bi-universal-access-circle" aria-hidden="true"></i></span><h3>Acessibilidade</h3><p>O próprio site segue WCAG 2.1 AA, com navegação por teclado e ajustes visuais.</p></article>
        <article class="feature"><span class="ico"><i class="bi bi-people" aria-hidden="true"></i></span><h3>Protagonismo</h3><p>Pessoas com deficiência no centro das decisões sobre o produto.</p></article>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container split">
      <div class="panel">
        <h2>Como funciona a verificação</h2>
        <div class="check-line"><i class="bi bi-1-circle-fill" aria-hidden="true"></i><div><strong>A empresa declara</strong><span>No cadastro da vaga, a empresa informa os recursos disponíveis.</span></div></div>
        <div class="check-line"><i class="bi bi-2-circle-fill" aria-hidden="true"></i><div><strong>Nossa equipe revisa</strong><span>Conferimos consistência e solicitamos ajustes quando necessário.</span></div></div>
        <div class="check-line"><i class="bi bi-3-circle-fill" aria-hidden="true"></i><div><strong>O selo aparece</strong><span>A vaga recebe o selo “Acessibilidade verificada” e ganha destaque.</span></div></div>
        <div class="check-line"><i class="bi bi-4-circle-fill" aria-hidden="true"></i><div><strong>A comunidade avalia</strong><span>Candidatos podem avaliar a empresa e complementar as informações.</span></div></div>
      </div>
      <div>
        <span class="eyebrow">Projeto em evolução</span>
        <h2>Um projeto aberto a melhorias</h2>
        <p>O IncluiTrabalho é desenvolvido de forma iterativa. Ouvimos candidatos, empresas e especialistas em acessibilidade para melhorar a experiência continuamente.</p>
        <p>Encontrou uma barreira no site? Tem uma sugestão? <a class="link" href="<?= e($base) ?>contato.php">Fale com a nossa equipe</a>. Toda contribuição ajuda a tornar a plataforma mais inclusiva.</p>
        <div class="hero-actions">
          <a class="btn btn-green" href="<?= e($base) ?>vagas.php">Encontrar uma vaga</a>
          <a class="btn btn-outline" href="<?= e($base) ?>para-empresas.php">Para empresas</a>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
