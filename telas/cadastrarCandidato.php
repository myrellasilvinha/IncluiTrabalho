<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados.php';

if (candidato_logado()) {
    redirect(absolute_url('telas/homeCandidato.php'));
}

$pageTitle = 'Criar conta de candidato';
$pageDescription = 'Crie sua conta gratuita no IncluiTrabalho e encontre vagas com acessibilidade verificada.';
$pageClass = 'auth-page';
$redirect = get('redirect');
$recursos = recursos_disponiveis();

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="auth-layout">
    <aside class="auth-aside" aria-label="Vantagens da conta">
      <a class="brand" href="<?= e($base) ?>index.php"><img src="<?= e($base) ?>assets/logo.svg" alt="" width="38" height="38"><span>Inclui<span>Trabalho</span></span></a>
      <div>
        <h2>Um perfil, várias oportunidades.</h2>
        <p>Cadastre-se uma vez e candidate-se às vagas que combinam com você. Informe os recursos de acessibilidade que precisa para trabalhar bem.</p>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>100% gratuito para candidatos</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Você controla o que compartilhar</span></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><span>Acompanhe cada etapa do processo</span></div>
      </div>
      <p class="auth-quote">“Acessibilidade não é favor: é condição para trabalhar bem.”</p>
    </aside>
    <div class="auth-main">
      <div class="form-wrap">
        <div class="auth-tabs" role="tablist" aria-label="Tipo de conta">
          <a class="is-active" href="cadastrarCandidato.php" aria-current="page">Sou candidato</a>
          <a href="salvarEmpresa.php">Sou empresa</a>
        </div>
        <span class="eyebrow">Cadastro gratuito</span>
        <h1>Criar conta de candidato</h1>
        <p>Leva menos de 2 minutos. Os campos com * são obrigatórios.</p>

        <form action="<?= e($base) ?>controladores/rota.php?acao=salvarCandidato" method="POST" data-validate>
          <?= csrf_field() ?>
          <?php if ($redirect !== ''): ?><input type="hidden" name="redirect" value="<?= e($redirect) ?>"><?php endif; ?>

          <div class="field">
            <label for="nome">Nome completo *</label>
            <input id="nome" name="nome" type="text" required autocomplete="name" minlength="3" placeholder="Como você se chama">
            <p class="error"></p>
          </div>
          <div class="form-row">
            <div class="field">
              <label for="email">E-mail *</label>
              <input id="email" name="email" type="email" required autocomplete="email" placeholder="voce@exemplo.com">
              <p class="error"></p>
            </div>
            <div class="field">
              <label for="telefone">Telefone</label>
              <input id="telefone" name="telefone" type="tel" autocomplete="tel" placeholder="(68) 99999-9999">
            </div>
          </div>
          <div class="field">
            <label for="cidade">Cidade e estado</label>
            <input id="cidade" name="cidade" type="text" autocomplete="address-level2" placeholder="Rio Branco - AC">
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
          <div class="field">
            <label for="formatoCurriculo">Formato do currículo</label>
            <select id="formatoCurriculo" name="formatoCurriculo">
              <option value="Texto">Texto (PDF ou documento)</option>
              <option value="Libras">Vídeo em Libras</option>
              <option value="Audio">Áudio</option>
            </select>
          </div>

          <fieldset>
            <legend>Recursos de acessibilidade que você precisa</legend>
            <p class="hint" style="margin:0 0 .4rem">Opcional. Ajuda a encontrar vagas compatíveis com o seu dia a dia.</p>
            <div class="check-list">
              <?php foreach ($recursos as $recurso): ?>
                <label><input type="checkbox" name="recursos[]" value="<?= (int) $recurso['id'] ?>"> <?= e($recurso['nome']) ?></label>
              <?php endforeach; ?>
            </div>
          </fieldset>

          <div class="check-inline" style="align-items:flex-start">
            <input id="termos" type="checkbox" required aria-describedby="termos-hint">
            <span id="termos-hint" style="font-size:.78rem;font-weight:500">Li e aceito os <a class="link" href="../termos.php" target="_blank">termos de uso</a> e a <a class="link" href="../privacidade.php" target="_blank">política de privacidade</a>.</span>
          </div>

          <button class="btn btn-green" type="submit"><i class="bi bi-person-plus" aria-hidden="true"></i> Criar minha conta</button>
        </form>

        <div class="form-footer">Já possui conta? <a href="autenticarCandidato.php<?= $redirect !== '' ? '?redirect=' . urlencode($redirect) : '' ?>">Entrar</a></div>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
