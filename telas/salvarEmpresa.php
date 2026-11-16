<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';

if (empresa_logada()) {
    redirect(absolute_url('telas/home.php'));
}

$pageTitle = 'Cadastrar empresa';
$pageDescription = 'Cadastre sua empresa gratuitamente, informe os recursos de acessibilidade e publique vagas no IncluiTrabalho.';
$pageClass = 'auth-page';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="auth-layout">
    <aside class="auth-aside" aria-label="Vantagens para empresas">
      <a class="brand" href="<?= e($base) ?>index.php"><img src="<?= e($base) ?>assets/logo.svg" alt="" width="38" height="38"><span>Inclui<span>Trabalho</span></span></a>
      <div>
        <h2>Inclusão que gera resultado para o seu negócio.</h2>
        <p>Cadastre sua empresa, publique vagas e mostre ao mercado o seu compromisso com a acessibilidade.</p>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Cadastro e publicação gratuitos</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Perfil público com avaliações</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Gestão simples de candidaturas</span></div>
      </div>
      <p class="auth-quote">“Diversidade na equipe melhora produtos e decisões.”</p>
    </aside>
    <div class="auth-main">
      <div class="form-wrap">
        <div class="auth-tabs" role="tablist" aria-label="Tipo de conta">
          <a href="cadastrarCandidato.php">Sou candidato</a>
          <a class="is-active" href="salvarEmpresa.php" aria-current="page">Sou empresa</a>
        </div>
        <span class="eyebrow navy">Cadastro de empresa</span>
        <h1>Cadastrar empresa</h1>
        <p>Crie a conta para publicar vagas e gerenciar candidaturas.</p>

        <form action="<?= e($base) ?>controladores/rota.php?acao=salvarEmpresa" method="POST" data-validate>
          <?= csrf_field() ?>
          <div class="field">
            <label for="nome">Nome da empresa *</label>
            <input id="nome" name="nome" type="text" required minlength="2" autocomplete="organization" placeholder="Razão social ou nome fantasia">
            <p class="error"></p>
          </div>
          <div class="form-row">
            <div class="field">
              <label for="email">E-mail corporativo *</label>
              <input id="email" name="email" type="email" required autocomplete="email" placeholder="rh@suaempresa.com.br">
              <p class="error"></p>
            </div>
            <div class="field">
              <label for="telefone">Telefone</label>
              <input id="telefone" name="telefone" type="tel" autocomplete="tel" placeholder="(68) 99999-9999">
            </div>
          </div>
          <div class="form-row">
            <div class="field">
              <label for="cidade">Cidade e estado</label>
              <input id="cidade" name="cidade" type="text" placeholder="Rio Branco - AC">
            </div>
            <div class="field">
              <label for="areaAtuacao">Área de atuação</label>
              <input id="areaAtuacao" name="areaAtuacao" type="text" placeholder="Tecnologia, Educação, Saúde...">
            </div>
          </div>
          <div class="field">
            <label for="site">Site</label>
            <input id="site" name="site" type="url" placeholder="https://suaempresa.com.br">
          </div>
          <div class="field">
            <label for="descricao">Descrição da empresa</label>
            <textarea id="descricao" name="descricao" rows="3" placeholder="Conte o que a empresa faz e como investe em inclusão."></textarea>
          </div>
          <div class="field">
            <label for="senha">Senha *</label>
            <div class="password-field">
              <input id="senha" name="senha" type="password" required autocomplete="new-password" minlength="8" placeholder="Mínimo de 8 caracteres">
              <button class="password-toggle" type="button" data-password-toggle="senha" aria-label="Mostrar senha" aria-pressed="false"><i class="bi bi-eye" aria-hidden="true"></i></button>
            </div>
            <div class="strength" data-strength-for="senha" aria-hidden="true"><span></span></div>
            <p class="hint">Use letras, números e um caractere especial. <span data-strength-label="senha"></span></p>
            <p class="error"></p>
          </div>
          <div class="check-inline" style="align-items:flex-start">
            <input id="termos" type="checkbox" required aria-describedby="termos-hint">
            <span id="termos-hint" style="font-size:.78rem;font-weight:500">Declaro que as informações são verdadeiras e aceito os <a class="link" href="../termos.php" target="_blank">termos de uso</a>.</span>
          </div>
          <button class="btn btn-green" type="submit"><i class="bi bi-buildings" aria-hidden="true"></i> Criar conta da empresa</button>
        </form>

        <div class="form-footer">Já tem conta? <a href="autenticar.php">Entrar</a></div>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
