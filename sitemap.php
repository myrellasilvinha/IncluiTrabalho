<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';

if (get('formato') === 'xml') {
    header('Content-Type: application/xml; charset=utf-8');
    $urls = ['index.php', 'vagas.php', 'para-empresas.php', 'sobre.php', 'acessibilidade.php', 'contato.php', 'privacidade.php', 'termos.php', 'telas/busca.php'];
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls as $u) {
        echo '  <url><loc>' . e(absolute_url($u)) . '</loc><changefreq>weekly</changefreq></url>' . "\n";
    }
    foreach (vagas_listar(['ordem' => 'recentes']) as $vaga) {
        echo '  <url><loc>' . e(absolute_url('vaga.php?id=' . (int) $vaga['id'])) . '</loc><changefreq>daily</changefreq></url>' . "\n";
    }
    echo '</urlset>';
    exit;
}

$pageTitle = 'Mapa do site';
$pageDescription = 'Todas as páginas do IncluiTrabalho em um só lugar: vagas, empresas, conteúdos institucionais e áreas de conta.';
$pageClass = 'sitemap-page';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container" style="padding-top:2rem;padding-bottom:4rem">
    <nav class="breadcrumb" aria-label="Você está em"><a href="<?= e($base) ?>index.php">Início</a><i class="bi bi-chevron-right" aria-hidden="true"></i><span aria-current="page">Mapa do site</span></nav>
    <h1>Mapa do site</h1>
    <p class="muted">Encontre rapidamente qualquer área da plataforma.</p>
    <div class="company-grid" style="margin-top:1.5rem">
      <section class="panel">
        <h2>Navegação</h2>
        <ul style="list-style:none;padding:0;display:grid;gap:.5rem">
          <li><a class="link" href="<?= e($base) ?>index.php">Início</a></li>
          <li><a class="link" href="<?= e($base) ?>vagas.php">Buscar vagas</a></li>
          <li><a class="link" href="<?= e($base) ?>telas/busca.php">Empresas</a></li>
          <li><a class="link" href="<?= e($base) ?>para-empresas.php">Para empresas</a></li>
          <li><a class="link" href="<?= e($base) ?>sobre.php">Sobre nós</a></li>
        </ul>
      </section>
      <section class="panel">
        <h2>Conta</h2>
        <ul style="list-style:none;padding:0;display:grid;gap:.5rem">
          <li><a class="link" href="<?= e($base) ?>telas/cadastrarCandidato.php">Criar conta de candidato</a></li>
          <li><a class="link" href="<?= e($base) ?>telas/autenticarCandidato.php">Entrar como candidato</a></li>
          <li><a class="link" href="<?= e($base) ?>telas/salvarEmpresa.php">Cadastrar empresa</a></li>
          <li><a class="link" href="<?= e($base) ?>telas/autenticar.php">Entrar como empresa</a></li>
        </ul>
      </section>
      <section class="panel">
        <h2>Institucional</h2>
        <ul style="list-style:none;padding:0;display:grid;gap:.5rem">
          <li><a class="link" href="<?= e($base) ?>acessibilidade.php">Acessibilidade</a></li>
          <li><a class="link" href="<?= e($base) ?>contato.php">Contato</a></li>
          <li><a class="link" href="<?= e($base) ?>privacidade.php">Privacidade</a></li>
          <li><a class="link" href="<?= e($base) ?>termos.php">Termos de uso</a></li>
          <li><a class="link" href="<?= e($base) ?>sitemap.php?formato=xml">Sitemap XML</a></li>
        </ul>
      </section>
    </div>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
