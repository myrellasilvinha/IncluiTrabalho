<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados.php';

$empresa = exigir_empresa();
$empresaId = (int) $empresa->id;
$id = (int) get('id');
$vaga = $id > 0 ? vaga_buscar($id) : null;

if ($id > 0 && (!$vaga || (int) $vaga['empresaId'] !== $empresaId)) {
    flash_set('error', 'Vaga não encontrada ou sem permissão para editar.');
    redirect(absolute_url('telas/home.php'));
}

$recursos = recursos_disponiveis();
$selecionados = [];
if ($vaga) {
    foreach ($recursos as $recurso) {
        if (in_array($recurso['nome'], $vaga['recursos'] ?? [], true)) $selecionados[] = (int) $recurso['id'];
    }
}

$pageTitle = $vaga ? 'Editar vaga' : 'Publicar vaga';
$pageDescription = 'Publique uma vaga com acessibilidade verificada e receba candidaturas qualificadas.';
$pageClass = 'dashboard-page';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container dashboard">
    <nav class="breadcrumb" aria-label="Você está em">
      <a href="home.php">Painel</a>
      <i class="bi bi-chevron-right" aria-hidden="true"></i>
      <span aria-current="page"><?= $vaga ? 'Editar vaga' : 'Publicar vaga' ?></span>
    </nav>

    <div class="dash-head">
      <div>
        <span class="eyebrow navy"><?= e($empresa->nome) ?></span>
        <h1><?= $vaga ? 'Editar vaga' : 'Publicar nova vaga' ?></h1>
        <p>Preencha as informações com clareza. Vagas com acessibilidade detalhada recebem mais candidaturas.</p>
      </div>
      <a class="btn btn-outline" href="home.php"><i class="bi bi-arrow-left" aria-hidden="true"></i> Voltar ao painel</a>
    </div>

    <form class="panel" action="<?= e($base) ?>controladores/rota.php?acao=publicarVaga" method="POST" data-validate style="max-width:54rem">
      <?= csrf_field() ?>
      <?php if ($vaga): ?><input type="hidden" name="id" value="<?= (int) $vaga['id'] ?>"><?php endif; ?>

      <div class="field">
        <label for="titulo">Título da vaga *</label>
        <input id="titulo" name="titulo" type="text" required minlength="4" maxlength="180" value="<?= e($vaga['titulo'] ?? '') ?>" placeholder="Ex.: Desenvolvedor(a) Web">
        <p class="error"></p>
      </div>

      <div class="form-row">
        <div class="field">
          <label for="localizacao">Localização</label>
          <input id="localizacao" name="localizacao" type="text" value="<?= e($vaga['localizacao'] ?? '') ?>" placeholder="Cidade - UF">
        </div>
        <div class="field">
          <label for="modalidade">Modalidade *</label>
          <select id="modalidade" name="modalidade" required>
            <?php foreach (['Presencial', 'Híbrido', 'Remoto'] as $mod): ?>
              <option value="<?= e($mod) ?>" <?= ($vaga['modalidade'] ?? 'Presencial') === $mod ? 'selected' : '' ?>><?= e($mod) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="field">
          <label for="tipoContrato">Tipo de contrato</label>
          <input id="tipoContrato" name="tipoContrato" type="text" value="<?= e($vaga['tipoContrato'] ?? '') ?>" placeholder="CLT, PJ, Estágio...">
        </div>
        <div class="field">
          <label for="nivel">Nível</label>
          <select id="nivel" name="nivel">
            <?php foreach (['', 'Estágio', 'Júnior', 'Pleno', 'Sênior', 'Especialista'] as $nivel): ?>
              <option value="<?= e($nivel) ?>" <?= ($vaga['nivel'] ?? '') === $nivel ? 'selected' : '' ?>><?= $nivel === '' ? 'Não informar' : e($nivel) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="field">
          <label for="salario">Salário (R$)</label>
          <input id="salario" name="salario" type="text" inputmode="decimal" value="<?= e($vaga['salario'] ?? '') ?>" placeholder="Ex.: 2500,00">
          <p class="hint">Deixe em branco para exibir “A combinar”.</p>
        </div>
        <div class="field">
          <label for="status">Status</label>
          <select id="status" name="status">
            <option value="Aberta" <?= ($vaga['status'] ?? 'Aberta') === 'Aberta' ? 'selected' : '' ?>>Aberta</option>
            <option value="Encerrada" <?= ($vaga['status'] ?? '') === 'Encerrada' ? 'selected' : '' ?>>Encerrada</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label for="descricao">Descrição da vaga *</label>
        <textarea id="descricao" name="descricao" required minlength="30" rows="6" placeholder="Descreva as atividades, o dia a dia e o que a pessoa fará."><?= e($vaga['descricao'] ?? '') ?></textarea>
        <p class="hint">Mínimo de 30 caracteres. Seja específico sobre responsabilidades.</p>
        <p class="error"></p>
      </div>

      <div class="form-row">
        <div class="field">
          <label for="requisitos">Requisitos</label>
          <textarea id="requisitos" name="requisitos" rows="4" placeholder="Um item por linha"><?= e($vaga['requisitos'] ?? '') ?></textarea>
        </div>
        <div class="field">
          <label for="beneficios">Benefícios</label>
          <textarea id="beneficios" name="beneficios" rows="4" placeholder="Um item por linha"><?= e($vaga['beneficios'] ?? '') ?></textarea>
        </div>
      </div>

      <fieldset style="margin-top:.5rem">
        <legend>Recursos de acessibilidade desta vaga</legend>
        <p class="hint" style="margin:0 0 .5rem">Marque tudo o que a empresa oferece de verdade. As informações serão exibidas na vaga e ajudam na verificação.</p>
        <div class="check-list">
          <?php foreach ($recursos as $recurso): ?>
            <label>
              <input type="checkbox" name="recursos[]" value="<?= (int) $recurso['id'] ?>" <?= in_array((int) $recurso['id'], $selecionados, true) ? 'checked' : '' ?>>
              <?= e($recurso['nome']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1rem">
        <button class="btn btn-green" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i> <?= $vaga ? 'Salvar alterações' : 'Publicar vaga' ?></button>
        <a class="btn btn-outline" href="home.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
