<?php
if (!function_exists('e')) {
    require_once __DIR__ . '/config.php';
}
$base = $base ?? '';
$anoAtual = date('Y');
?>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a class="brand" href="<?= e($base) ?>index.php" aria-label="IncluiTrabalho — página inicial">
          <img src="<?= e($base) ?>assets/logo.svg" alt="" width="38" height="38">
          <span>Inclui<span>Trabalho</span></span>
        </a>
        <p>Conectamos pessoas com deficiência a empresas que investem em acessibilidade de verdade. Vagas verificadas, processos simples e mais oportunidades.</p>
        <div class="footer-social">
          <a href="mailto:<?= e(SITE_EMAIL) ?>" aria-label="Enviar e-mail para o IncluiTrabalho"><i class="bi bi-envelope" aria-hidden="true"></i></a>
          <a href="<?= e($base) ?>contato.php" aria-label="Falar com a equipe"><i class="bi bi-chat-dots" aria-hidden="true"></i></a>
          <a href="<?= e($base) ?>sitemap.php" aria-label="Mapa do site"><i class="bi bi-diagram-3" aria-hidden="true"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h3>Candidatos</h3>
        <ul>
          <li><a href="<?= e($base) ?>vagas.php">Buscar vagas</a></li>
          <li><a href="<?= e($base) ?>telas/cadastrarCandidato.php">Criar conta</a></li>
          <li><a href="<?= e($base) ?>telas/autenticarCandidato.php">Entrar</a></li>
          <li><a href="<?= e($base) ?>acessibilidade.php">Recursos de acessibilidade</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h3>Empresas</h3>
        <ul>
          <li><a href="<?= e($base) ?>para-empresas.php">Divulgar vagas</a></li>
          <li><a href="<?= e($base) ?>telas/salvarEmpresa.php">Cadastrar empresa</a></li>
          <li><a href="<?= e($base) ?>telas/autenticar.php">Área da empresa</a></li>
          <li><a href="<?= e($base) ?>telas/busca.php">Empresas parceiras</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h3>Institucional</h3>
        <ul>
          <li><a href="<?= e($base) ?>sobre.php">Sobre nós</a></li>
          <li><a href="<?= e($base) ?>contato.php">Contato</a></li>
          <li><a href="<?= e($base) ?>privacidade.php">Privacidade e LGPD</a></li>
          <li><a href="<?= e($base) ?>termos.php">Termos de uso</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= $anoAtual ?> IncluiTrabalho. Feito para incluir.</span>
      <span class="footer-seal"><i class="bi bi-universal-access-circle" aria-hidden="true"></i> Compromisso com acessibilidade WCAG 2.1 AA</span>
      <span class="footer-legal">
        <a href="<?= e($base) ?>privacidade.php">Privacidade</a>
        <a href="<?= e($base) ?>termos.php">Termos</a>
        <a href="<?= e($base) ?>acessibilidade.php">Acessibilidade</a>
      </span>
    </div>
  </div>
</footer>

<div class="cookie-banner" role="region" aria-label="Aviso de cookies">
  <strong>Nós usamos cookies</strong>
  Utilizamos cookies essenciais para manter sua sessão e lembrar preferências de acessibilidade. Você pode aceitar ou recusar os cookies de análise.
  <div class="cookie-actions">
    <button class="btn btn-sm" type="button" data-cookie="aceito">Aceitar</button>
    <button class="btn btn-outline btn-sm" type="button" data-cookie="recusado">Somente essenciais</button>
    <a class="btn btn-ghost btn-sm" href="<?= e($base) ?>privacidade.php">Saiba mais</a>
  </div>
</div>

<button class="a11y-fab" type="button" aria-expanded="false" aria-controls="a11y-panel" aria-label="Abrir opções de acessibilidade">
  <i class="bi bi-universal-access-circle" aria-hidden="true"></i>
</button>
<div class="a11y-panel" id="a11y-panel" role="region" aria-label="Opções de acessibilidade">
  <h2>Acessibilidade</h2>
  <div class="a11y-actions">
    <button class="btn btn-outline btn-sm" type="button" data-a11y="large" aria-pressed="false"><i class="bi bi-fonts" aria-hidden="true"></i> Texto maior</button>
    <button class="btn btn-outline btn-sm" type="button" data-a11y="contrast" aria-pressed="false"><i class="bi bi-circle-half" aria-hidden="true"></i> Alto contraste</button>
    <button class="btn btn-outline btn-sm" type="button" data-a11y="underline" aria-pressed="false"><i class="bi bi-link-45deg" aria-hidden="true"></i> Sublinhar links</button>
    <button class="btn btn-outline btn-sm" type="button" data-a11y-reset><i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i> Restaurar</button>
  </div>
  <p>Suas preferências ficam salvas neste navegador. Precisa de ajuda? <a class="link" href="<?= e($base) ?>contato.php">Fale com a gente</a>.</p>
</div>

<script>window.INCLUI_BASE = <?= json_encode($base, JSON_UNESCAPED_SLASHES) ?>; window.INCLUI_LOGADO = <?= (candidato_logado() || empresa_logada()) ? 'true' : 'false' ?>;</script>
<script src="<?= e($base) ?>assets/app.js<?= e(asset_versao('app.js')) ?>" defer></script>
</body>
</html>
