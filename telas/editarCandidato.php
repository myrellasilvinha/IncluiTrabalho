<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados.php';

$candidato = exigir_candidato();
$candidatoId = (int) $candidato->id;
$pdo = db();
$stmt = $pdo->prepare('SELECT * FROM candidato WHERE id = ? LIMIT 1');
$stmt->execute([$candidatoId]);
$perfil = $stmt->fetch();
if (!$perfil) {
    redirect(absolute_url('controladores/rota.php?acao=sairCandidato'));
}

$recursos = recursos_disponiveis();
$selecionados = recursos_candidato($candidatoId);

$pageTitle = 'Editar perfil';
$pageDescription = 'Atualize seus dados, currículo e recursos de acessibilidade no IncluiTrabalho.';
$pageClass = 'dashboard-page';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container dashboard">
    <nav class="breadcrumb" aria-label="Você está em">
      <a href="homeCandidato.php">Minha área</a>
      <i class="bi bi-chevron-right" aria-hidden="true"></i>
      <span aria-current="page">Editar perfil</span>
    </nav>

    <div class="dash-head">
      <div>
        <span class="eyebrow">Área do candidato</span>
        <h1>Editar perfil</h1>
        <p>Mantenha seus dados atualizados para aumentar suas chances nas candidaturas.</p>
      </div>
      <a class="btn btn-outline" href="homeCandidato.php"><i class="bi bi-arrow-left" aria-hidden="true"></i> Voltar</a>
    </div>

    <form class="panel" action="<?= e($base) ?>controladores/rota.php?acao=editarCandidato" method="POST" data-validate style="max-width:48rem">
      <?= csrf_field() ?>
      <div class="field">
        <label for="nome">Nome completo *</label>
        <input id="nome" name="nome" type="text" required minlength="3" value="<?= e($perfil['nome']) ?>" autocomplete="name">
        <p class="error"></p>
      </div>
      <div class="form-row">
        <div class="field">
          <label for="email">E-mail *</label>
          <input id="email" name="email" type="email" required value="<?= e($perfil['email']) ?>" autocomplete="email">
          <p class="error"></p>
        </div>
        <div class="field">
          <label for="telefone">Telefone</label>
          <input id="telefone" name="telefone" type="tel" value="<?= e($perfil['telefone'] ?? '') ?>" autocomplete="tel">
        </div>
      </div>
      <div class="form-row">
        <div class="field">
          <label for="cidade">Cidade e estado</label>
          <input id="cidade" name="cidade" type="text" value="<?= e($perfil['cidade'] ?? '') ?>" placeholder="Rio Branco - AC">
        </div>
        <div class="field">
          <label for="formatoCurriculo">Formato do currículo</label>
          <select id="formatoCurriculo" name="formatoCurriculo">
            <?php foreach (['Texto', 'Libras', 'Audio'] as $formato): ?>
              <option value="<?= e($formato) ?>" <?= ($perfil['formatoCurriculo'] ?? 'Texto') === $formato ? 'selected' : '' ?>>
                <?= $formato === 'Libras' ? 'Vídeo em Libras' : ($formato === 'Audio' ? 'Áudio' : 'Texto (PDF ou documento)') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="sobre">Resumo profissional</label>
        <textarea id="sobre" name="sobre" rows="4" placeholder="Conte sua experiência, áreas de interesse e o que você busca."><?= e($perfil['sobre'] ?? '') ?></textarea>
        <p class="hint">Visível apenas para empresas quando você se candidata.</p>
      </div>

      <fieldset>
        <legend>Recursos de acessibilidade que você precisa</legend>
        <p class="hint" style="margin:0 0 .4rem">Usamos essas informações para sugerir vagas compatíveis.</p>
        <div class="check-list">
          <?php foreach ($recursos as $recurso): ?>
            <label>
              <input type="checkbox" name="recursos[]" value="<?= (int) $recurso['id'] ?>" <?= in_array((int) $recurso['id'], $selecionados, true) ? 'checked' : '' ?>>
              <?= e($recurso['nome']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </fieldset>

      <div class="field">
        <label for="senha">Nova senha</label>
        <div class="password-field">
          <input id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" placeholder="Deixe em branco para manter a atual">
          <button class="password-toggle" type="button" data-password-toggle="senha" aria-label="Mostrar senha" aria-pressed="false"><i class="bi bi-eye" aria-hidden="true"></i></button>
        </div>
        <div class="strength" data-strength-for="senha" aria-hidden="true"><span></span></div>
        <p class="hint">Mínimo de 8 caracteres, se for alterar. <span data-strength-label="senha"></span></p>
      </div>

      <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:1rem">
        <button class="btn btn-green" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i> Salvar alterações</button>
        <a class="btn btn-outline" href="homeCandidato.php">Cancelar</a>
      </div>
    </form>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
