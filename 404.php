<?php
http_response_code(404);
$pageTitle = 'Página não encontrada';
$pageDescription = 'A página que você procura não existe ou foi movida.';
$pageClass = 'not-found-page';
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo" class="container">
  <div class="not-found">
    <p class="code" aria-hidden="true">404</p>
    <h1>Página não encontrada</h1>
    <p>O endereço pode estar incorreto ou a página foi movida. Que tal continuar a busca por uma oportunidade?</p>
    <div class="hero-actions">
      <a class="btn btn-green" href="<?= e($base) ?>vagas.php"><i class="bi bi-search" aria-hidden="true"></i> Ver vagas abertas</a>
      <a class="btn btn-outline" href="<?= e($base) ?>index.php">Ir para o início</a>
      <a class="btn btn-ghost" href="<?= e($base) ?>contato.php">Falar com a equipe</a>
    </div>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
