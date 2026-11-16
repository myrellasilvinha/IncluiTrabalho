<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';

if (empresa_logada()) {
    redirect(absolute_url('telas/home.php'));
}

$pageTitle = 'Entrar como empresa';
$pageDescription = 'Acesse o painel da empresa para publicar vagas e gerenciar candidaturas no IncluiTrabalho.';
$pageClass = 'auth-page';
$redirect = get('redirect');

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="auth-layout">
    <aside class="auth-aside" aria-label="Sobre o painel da empresa">
      <a class="brand" href="<?= e($base) ?>index.php"><img src="<?= e($base) ?>assets/logo.svg" alt="" width="38" height="38"><span>Inclui<span>Trabalho</span></span></a>
      <div>
        <h2>Talentos diversos fortalecem a sua empresa.</h2>
        <p>Publique vagas, informe os recursos de acessibilidade e encontre profissionais qualificados comprometidos com resultados.</p>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Publicação de vagas em poucos minutos</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Gestão completa das candidaturas</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Perfil público com avaliações</span></div>
      </div>
      <p class="auth-quote">“Contratar com inclusão é ampliar repertório e resultado.”</p>
    </aside>
    <div class="auth-main">
      <div class="form-wrap">
        <div class="auth-tabs" role="tablist" aria-label="Tipo de conta">
          <a href="autenticarCandidato.php">Sou candidato</a>
          <a class="is-active" href="autenticar.php" aria-current="page">Sou empresa</a>
        </div>
        <span class="eyebrow navy">Área da empresa</span>
        <h1>Entrar no painel</h1>
        <p>Gerencie suas vagas e candidaturas.</p>

        <?php if (get('cadastro') === 'sucesso'): ?>
          <div class="alert alert-success" role="status"><i class="bi bi-check-circle" aria-hidden="true"></i><span>Cadastro realizado com sucesso! Entre com seu e-mail e senha.</span></div>
        <?php endif; ?>

        <form action="<?= e($base) ?>controladores/rota.php?acao=autenticar" method="POST" data-validate>
          <?= csrf_field() ?>
          <?php if ($redirect !== ''): ?><input type="hidden" name="redirect" value="<?= e($redirect) ?>"><?php endif; ?>
          <div class="field">
            <label for="email">E-mail corporativo</label>
            <input id="email" name="email" type="email" required autocomplete="email" placeholder="rh@suaempresa.com.br">
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

        <div class="form-footer">Ainda não cadastrou a empresa? <a href="salvarEmpresa.php">Criar conta da empresa</a></div>
        <div class="form-footer" style="margin-top:.4rem"><a href="../contato.php">Preciso de ajuda para acessar</a></div>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
