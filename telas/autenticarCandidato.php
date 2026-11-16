<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';

if (candidato_logado()) {
    redirect(absolute_url('telas/homeCandidato.php'));
}

$pageTitle = 'Entrar como candidato';
$pageDescription = 'Acesse sua conta do IncluiTrabalho para acompanhar candidaturas e vagas salvas.';
$pageClass = 'auth-page';
$redirect = get('redirect');

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="auth-layout">
    <aside class="auth-aside" aria-label="Sobre o IncluiTrabalho">
      <a class="brand" href="<?= e($base) ?>index.php"><img src="<?= e($base) ?>assets/logo.svg" alt="" width="38" height="38"><span>Inclui<span>Trabalho</span></span></a>
      <div>
        <h2>Seu talento merece um lugar que respeite você.</h2>
        <p>Acompanhe candidaturas, salve vagas e encontre empresas que informam os recursos de acessibilidade antes de contratar.</p>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Vagas com acessibilidade verificada</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Candidatura gratuita e sem burocracia</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Seus dados protegidos pela LGPD</span></div>
      </div>
      <p class="auth-quote">“Informação sobre acessibilidade antes da candidatura muda tudo.”</p>
    </aside>
    <div class="auth-main">
      <div class="form-wrap">
        <div class="auth-tabs" role="tablist" aria-label="Tipo de conta">
          <a class="is-active" href="autenticarCandidato.php" aria-current="page">Sou candidato</a>
          <a href="autenticar.php">Sou empresa</a>
        </div>
        <span class="eyebrow">Área do candidato</span>
        <h1>Entrar na sua conta</h1>
        <p>Acesse para acompanhar suas candidaturas e vagas salvas.</p>

        <?php if (get('cadastro') === 'sucesso'): ?>
          <div class="alert alert-success" role="status"><i class="bi bi-check-circle" aria-hidden="true"></i><span>Cadastro realizado com sucesso! Entre com seu e-mail e senha.</span></div>
        <?php endif; ?>

        <form action="<?= e($base) ?>controladores/rota.php?acao=autenticarCandidato" method="POST" data-validate>
          <?= csrf_field() ?>
          <?php if ($redirect !== ''): ?><input type="hidden" name="redirect" value="<?= e($redirect) ?>"><?php endif; ?>
          <div class="field">
            <label for="email">E-mail</label>
            <input id="email" name="email" type="email" required autocomplete="email" placeholder="voce@exemplo.com">
            <p class="error"></p>
          </div>
          <div class="field">
            <label for="senha">Senha</label>
            <div class="password-field">
              <input id="senha" name="senha" type="password" required autocomplete="current-password" placeholder="Sua senha">
              <button class="password-toggle" type="button" data-password-toggle="senha" aria-label="Mostrar senha" aria-pressed="false"><i class="bi bi-eye" aria-hidden="true"></i></button>
            </div>
            <p class="error"></p>
          </div>
          <button class="btn btn-green" type="submit"><i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Entrar</button>
        </form>

        <div class="form-footer">Ainda não tem conta? <a href="cadastrarCandidato.php<?= $redirect !== '' ? '?redirect=' . urlencode($redirect) : '' ?>">Criar conta grátis</a></div>
        <div class="form-footer" style="margin-top:.4rem"><a href="../contato.php">Esqueci minha senha / preciso de ajuda</a></div>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
