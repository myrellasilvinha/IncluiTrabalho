<?php
$pageTitle = 'Acessibilidade';
$pageDescription = 'Recursos de acessibilidade do IncluiTrabalho: navegação por teclado, contraste, texto ampliado, Libras e compromisso com WCAG 2.1 AA.';
$pageClass = 'accessibility-page';
require __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';
$recursos = recursos_disponiveis();
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <section class="page-intro">
    <div class="container">
      <nav class="breadcrumb" aria-label="Você está em"><a href="<?= e($base) ?>index.php">Início</a><i class="bi bi-chevron-right" aria-hidden="true"></i><span aria-current="page">Acessibilidade</span></nav>
      <span class="eyebrow">Compromisso com todas as pessoas</span>
      <h1>Acessibilidade não é um recurso extra. É a base.</h1>
      <p>Construímos o IncluiTrabalho para ser usado por qualquer pessoa, com ou sem tecnologia assistiva. Conheça os recursos do site e das vagas.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-heading"><span class="eyebrow navy">No site</span><h2>Ferramentas que você pode usar agora</h2></div>
      <div class="feature-grid">
        <article class="feature"><span class="ico"><i class="bi bi-keyboard" aria-hidden="true"></i></span><h3>Navegação por teclado</h3><p>Use Tab para percorrer os elementos, Enter para ativar e Esc para fechar menus. Há um link “Pular para o conteúdo” no topo.</p></article>
        <article class="feature"><span class="ico"><i class="bi bi-fonts" aria-hidden="true"></i></span><h3>Texto ampliado</h3><p>No botão de acessibilidade, ative “Texto maior” para aumentar a fonte em todo o site sem quebrar o layout.</p></article>
        <article class="feature"><span class="ico"><i class="bi bi-circle-half" aria-hidden="true"></i></span><h3>Alto contraste</h3><p>Ative o modo de alto contraste para reforçar a diferença entre texto e fundo.</p></article>
        <article class="feature"><span class="ico"><i class="bi bi-link-45deg" aria-hidden="true"></i></span><h3>Links sublinhados</h3><p>Se preferir identificar links pela forma, ative “Sublinhar links”. A preferência fica salva no navegador.</p></article>
      </div>
      <div class="info-note" style="margin-top:1.4rem;display:flex;gap:.6rem;align-items:flex-start">
        <i class="bi bi-lightbulb" aria-hidden="true" style="margin-top:.15rem"></i>
        <span>As preferências ficam no seu navegador e podem ser restauradas a qualquer momento no painel de acessibilidade, no canto inferior direito.</span>
      </div>
    </div>
  </section>

  <section class="section section-soft">
    <div class="container">
      <div class="section-heading"><span class="eyebrow">Nas vagas</span><h2>O que cada recurso significa</h2><p>Estas são as informações que as empresas declaram em cada oportunidade.</p></div>
      <div class="access-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <?php foreach ($recursos as $recurso): ?>
          <div class="access-item is-on">
            <i class="bi <?= e(recurso_icone($recurso['nome'])) ?>" aria-hidden="true"></i>
            <strong><?= e($recurso['nome']) ?></strong>
            <p><?= e($recurso['descricao'] ?: recurso_descricao($recurso['nome'])) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container split">
      <div>
        <span class="eyebrow">Padrões técnicos</span>
        <h2>Nosso compromisso com WCAG 2.1 AA</h2>
        <p>Seguimos as diretrizes de acessibilidade para conteúdo web, buscando garantir percepção, operabilidade, compreensão e robustez para todas as pessoas.</p>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Contraste de texto</strong><span>Relação mínima de 4.5:1 para textos e 3:1 para elementos de interface.</span></div></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Estrutura semântica</strong><span>Cabeçalhos em ordem, landmarks e textos alternativos em imagens.</span></div></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Foco visível</strong><span>Indicadores claros de foco para navegação por teclado.</span></div></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Sem dependência de cor</strong><span>Informações nunca são transmitidas apenas por cor.</span></div></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Movimento reduzido</strong><span>Animações são respeitadas conforme a preferência do sistema.</span></div></div>
      </div>
      <div class="panel">
        <h2>Encontrou uma barreira?</h2>
        <p>Se algum conteúdo não estiver acessível para você, queremos saber. Descreva a página, o problema e a tecnologia assistiva que está usando.</p>
        <a class="btn btn-green" href="<?= e($base) ?>contato.php">Reportar problema de acessibilidade</a>
        <p class="hint" style="margin-top:.8rem">Também aceitamos sugestões de melhoria e parcerias com organizações de pessoas com deficiência.</p>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
