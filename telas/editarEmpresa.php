<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados.php';

$empresaSessao = exigir_empresa();
$empresaId = (int) $empresaSessao->id;
$pdo = db();
$stmt = $pdo->prepare('SELECT * FROM empresa WHERE id = ? LIMIT 1');
$stmt->execute([$empresaId]);
$empresa = $stmt->fetch();
if (!$empresa) {
    redirect(absolute_url('controladores/rota.php?acao=sair'));
}

$pageTitle = 'Editar perfil da empresa';
$pageDescription = 'Atualize os dados e a descrição da sua empresa no IncluiTrabalho.';
$pageClass = 'dashboard-page';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container dashboard">
    <nav class="breadcrumb" aria-label="Você está em">
      <a href="home.php">Painel</a>
      <i class="bi bi-chevron-right" aria-hidden="true"></i>
      <span aria-current="page">Editar perfil</span>
    </nav>

    <div class="dash-head">
      <div>
        <span class="eyebrow navy">Área da empresa</span>
        <h1>Editar perfil da empresa</h1>
        <p>Um perfil completo transmite confiança e atrai mais candidaturas.</p>
      </div>
      <a class="btn btn-outline" href="home.php"><i class="bi bi-arrow-left" aria-hidden="true"></i> Voltar</a>
    </div>

    <form class="panel" action="<?= e($base) ?>controladores/rota.php?acao=editarEmpresa" method="POST" data-validate style="max-width:48rem">
      <?= csrf_field() ?>
      <div class="field">
        <label for="nome">Nome da empresa *</label>
        <input id="nome" name="nome" type="text" required minlength="2" value="<?= e($empresa['nome']) ?>" autocomplete="organization">
        <p class="error"></p>
      </div>
      <div class="form-row">
        <div class="field">
          <label for="email">E-mail corporativo *</label>
          <input id="email" name="email" type="email" required value="<?= e($empresa['email']) ?>" autocomplete="email">
          <p class="error"></p>
        </div>
        <div class="field">
          <label for="telefone">Telefone</label>
          <input id="telefone" name="telefone" type="tel" value="<?= e($empresa['telefone'] ?? '') ?>" autocomplete="tel">
        </div>
      </div>
      <div class="form-row">
        <div class="field">
          <label for="cidade">Cidade e estado</label>
          <input id="cidade" name="cidade" type="text" value="<?= e($empresa['cidade'] ?? '') ?>" placeholder="Rio Branco - AC">
        </div>
        <div class="field">
          <label for="areaAtuacao">Área de atuação</label>
          <input id="areaAtuacao" name="areaAtuacao" type="text" value="<?= e($empresa['areaAtuacao'] ?? '') ?>" placeholder="Tecnologia, Educação, Saúde...">
        </div>
      </div>
      <div class="field">
        <label for="site">Site</label>
        <input id="site" name="site" type="url" value="<?= e($empresa['site'] ?? '') ?>" placeholder="https://suaempresa.com.br">
      </div>
      <div class="field">
        <label for="descricao">Descrição da empresa</label>
        <textarea id="descricao" name="descricao" rows="5" placeholder="Conte o que a empresa faz, sua cultura e como investe em inclusão."><?= e($empresa['descricao'] ?? '') ?></textarea>
      </div>

      <div class="info-note" style="display:flex;gap:.6rem;align-items:flex-start">
        <i class="bi bi-patch-check" aria-hidden="true" style="margin-top:.15rem"></i>
        <span>O selo de empresa verificada é concedido pela equipe do IncluiTrabalho após análise das informações de acessibilidade.</span>
      </div>

      <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1rem">
        <button class="btn btn-green" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i> Salvar alterações</button>
        <a class="btn btn-outline" href="home.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
