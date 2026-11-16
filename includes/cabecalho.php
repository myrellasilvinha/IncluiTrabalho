<?php
if (!function_exists('e')) {
    require_once __DIR__ . '/config.php';
}
$base = $base ?? '';
$candidatoSessao = candidato_logado();
$empresaSessao = empresa_logada();
$paginaAtual = basename($_SERVER['SCRIPT_NAME'] ?? '');
$emTelas = str_contains(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''), '/telas/');
$navAtivo = function (array $arquivos) use ($paginaAtual): string {
    return in_array($paginaAtual, $arquivos, true) ? ' aria-current="page"' : '';
};
?>
<header class="site-header">
  <div class="container nav">
    <a class="brand" href="<?= e($base) ?>index.php" aria-label="IncluiTrabalho — página inicial">
      <img src="<?= e($base) ?>assets/logo.svg" alt="" width="41" height="41">
      <span>Inclui<span>Trabalho</span></span>
    </a>
    <button class="mobile-menu-toggle" type="button" aria-expanded="false" aria-controls="main-navigation" aria-label="Abrir menu">
      <i class="bi bi-list" aria-hidden="true"></i><span>Menu</span>
    </button>
    <nav id="main-navigation" class="nav-links" aria-label="Menu principal">
      <a href="<?= e($base) ?>index.php"<?= $navAtivo(['index.php']) ?>>Início</a>
      <a href="<?= e($base) ?>vagas.php"<?= $navAtivo(['vagas.php', 'vaga.php']) ?>>Vagas</a>
      <a href="<?= e($base) ?>telas/busca.php"<?= $navAtivo(['busca.php', 'avaliacoesEmpresas.php']) ?>>Empresas</a>
      <a href="<?= e($base) ?>para-empresas.php"<?= $navAtivo(['para-empresas.php', 'empresa.php']) ?>>Para empresas</a>
      <a href="<?= e($base) ?>sobre.php"<?= $navAtivo(['sobre.php', 'acessibilidade.php', 'contato.php']) ?>>Sobre</a>
      <span class="mobile-only">
        <?php if ($candidatoSessao): ?>
          <a class="btn btn-sm btn-green" href="<?= e($base) ?>telas/homeCandidato.php"><i class="bi bi-person-circle" aria-hidden="true"></i> Minha área</a>
          <a class="btn btn-outline btn-sm" href="<?= e($base) ?>controladores/rota.php?acao=sair"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Sair</a>
        <?php elseif ($empresaSessao): ?>
          <a class="btn btn-sm btn-green" href="<?= e($base) ?>telas/home.php"><i class="bi bi-buildings" aria-hidden="true"></i> Painel da empresa</a>
          <a class="btn btn-outline btn-sm" href="<?= e($base) ?>controladores/rota.php?acao=sair"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Sair</a>
        <?php else: ?>
          <a class="btn btn-outline btn-sm" href="<?= e($base) ?>telas/autenticarCandidato.php">Entrar</a>
          <a class="btn btn-sm" href="<?= e($base) ?>telas/cadastrarCandidato.php">Criar conta</a>
        <?php endif; ?>
      </span>
    </nav>
    <div class="actions">
      <?php if ($candidatoSessao): ?>
        <a class="btn btn-outline btn-sm" href="<?= e($base) ?>telas/homeCandidato.php"><i class="bi bi-heart" aria-hidden="true"></i> Vagas salvas</a>
        <a class="btn btn-sm btn-green" href="<?= e($base) ?>telas/homeCandidato.php"><i class="bi bi-person-circle" aria-hidden="true"></i> <?= e(explode(' ', trim((string) ($candidatoSessao->nome ?? 'Minha conta')))[0]) ?></a>
        <a class="btn btn-ghost btn-sm btn-icon" href="<?= e($base) ?>controladores/rota.php?acao=sair" aria-label="Sair da conta" title="Sair da conta"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></a>
      <?php elseif ($empresaSessao): ?>
        <a class="btn btn-outline btn-sm" href="<?= e($base) ?>telas/publicarVaga.php"><i class="bi bi-plus-lg" aria-hidden="true"></i> Publicar vaga</a>
        <a class="btn btn-sm btn-green" href="<?= e($base) ?>telas/home.php"><i class="bi bi-buildings" aria-hidden="true"></i> Painel</a>
        <a class="btn btn-ghost btn-sm btn-icon" href="<?= e($base) ?>controladores/rota.php?acao=sair" aria-label="Sair da conta" title="Sair da conta"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></a>
      <?php else: ?>
        <a class="btn btn-outline btn-sm" href="<?= e($base) ?>telas/autenticarCandidato.php">Entrar</a>
        <a class="btn btn-sm" href="<?= e($base) ?>telas/cadastrarCandidato.php">Criar conta grátis</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<?php
$flashes = flash_get();
if ($flashes):
?>
<div class="container" style="padding-top:1rem">
  <?php foreach ($flashes as $flash): ?>
    <div class="alert alert-<?= e($flash['tipo'] === 'success' ? 'success' : ($flash['tipo'] === 'error' ? 'error' : 'info')) ?>" role="status">
      <i class="bi <?= $flash['tipo'] === 'success' ? 'bi-check-circle' : ($flash['tipo'] === 'error' ? 'bi-exclamation-circle' : 'bi-info-circle') ?>" aria-hidden="true"></i>
      <span><?= e($flash['mensagem']) ?></span>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>
