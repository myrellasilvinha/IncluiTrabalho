<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados.php';
require_once __DIR__ . '/../includes/dados_empresas.php';
require_once __DIR__ . '/../includes/componentes.php';

$candidato = exigir_candidato();
$candidatoId = (int) $candidato->id;

$pdo = db_tentar();
$perfil = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM candidato WHERE id = ? LIMIT 1');
        $stmt->execute([$candidatoId]);
        $perfil = $stmt->fetch();
    } catch (Throwable $e) {
    }
}
$perfil = $perfil ?: ['nome' => $candidato->nome, 'email' => $candidato->email, 'telefone' => '', 'cidade' => '', 'sobre' => '', 'formatoCurriculo' => 'Texto'];

$candidaturas = candidaturas_candidato($candidatoId);
$salvas = vagas_salvas($candidatoId);
$recursosCandidato = recursos_candidato($candidatoId);
$recursos = recursos_disponiveis();

$completude = 40;
if (!empty($perfil['telefone'])) $completude += 15;
if (!empty($perfil['cidade'])) $completude += 15;
if (!empty($perfil['sobre'])) $completude += 15;
if ($recursosCandidato) $completude += 15;

$recomendadas = array_slice(array_values(array_filter(vagas_listar(['ordem' => 'recentes']), function ($v) use ($salvas, $candidaturas) {
    $idsCandidaturas = array_map(fn($c) => (int) $c['vagaId'], $candidaturas);
    return !in_array((int) $v['id'], $idsCandidaturas, true);
})), 0, 3);
$idsSalvas = array_map(fn($v) => (int) $v['id'], $salvas);

$pageTitle = 'Minha área';
$pageDescription = 'Acompanhe candidaturas, vagas salvas e seu perfil no IncluiTrabalho.';
$pageClass = 'dashboard-page';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container dashboard">
    <div class="dash-head">
      <div>
        <span class="eyebrow">Área do candidato</span>
        <h1>Olá, <?= e(explode(' ', trim($perfil['nome']))[0]) ?></h1>
        <p>Aqui você acompanha suas candidaturas, vagas salvas e o seu perfil.</p>
      </div>
      <a class="btn btn-green" href="<?= e($base) ?>vagas.php"><i class="bi bi-search" aria-hidden="true"></i> Buscar novas vagas</a>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <span class="ico"><i class="bi bi-send-check" aria-hidden="true"></i></span>
        <div><span class="stat-value"><?= count($candidaturas) ?></span><span class="stat-label">Candidaturas enviadas</span></div>
      </div>
      <div class="stat-card">
        <span class="ico"><i class="bi bi-heart" aria-hidden="true"></i></span>
        <div><span class="stat-value"><?= count($salvas) ?></span><span class="stat-label">Vagas salvas</span></div>
      </div>
      <div class="stat-card">
        <span class="ico"><i class="bi bi-person-check" aria-hidden="true"></i></span>
        <div><span class="stat-value"><?= $completude ?>%</span><span class="stat-label">Perfil completo</span></div>
      </div>
    </div>

    <div class="dash-grid">
      <div style="display:grid;gap:1rem">
        <section class="list-card" aria-labelledby="minhas-candidaturas">
          <div class="list-card-head">
            <h2 id="minhas-candidaturas">Minhas candidaturas</h2>
            <a class="link" href="<?= e($base) ?>vagas.php">Ver vagas</a>
          </div>
          <?php if ($candidaturas): ?>
            <?php foreach (array_slice($candidaturas, 0, 6) as $candidatura): ?>
              <div class="application-row">
                <div>
                  <h3><a href="<?= e($base) ?>vaga.php?id=<?= (int) $candidatura['vagaId'] ?>"><?= e($candidatura['titulo']) ?></a></h3>
                  <p><?= e($candidatura['empresaNome']) ?> · <?= e($candidatura['localizacao'] ?: $candidatura['modalidade']) ?> · enviada <?= e(tempo_relativo($candidatura['dataCandidatura'])) ?></p>
                </div>
                <span class="status-pill <?= e(status_candidatura_classe($candidatura['status'])) ?>"><i class="bi bi-clock-history" aria-hidden="true"></i> <?= e($candidatura['status']) ?></span>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty" style="border:0;padding:1.5rem">
              <i class="bi bi-send" aria-hidden="true"></i>
              <h3>Nenhuma candidatura ainda</h3>
              <p>Encontre uma vaga com acessibilidade e candidate-se em poucos cliques.</p>
              <a class="btn btn-green btn-sm" href="<?= e($base) ?>vagas.php">Buscar vagas</a>
            </div>
          <?php endif; ?>
        </section>

        <section class="list-card" aria-labelledby="vagas-salvas">
          <div class="list-card-head">
            <h2 id="vagas-salvas">Vagas salvas</h2>
            <a class="link" href="<?= e($base) ?>vagas.php">Explorar</a>
          </div>
          <?php if ($salvas): ?>
            <div class="jobs-grid">
              <?php foreach ($salvas as $vaga): ?>
                <?= vaga_card($vaga, ['salvas' => $idsSalvas, 'logado' => true]) ?>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="empty" style="border:0;padding:1.5rem">
              <i class="bi bi-heart" aria-hidden="true"></i>
              <h3>Você ainda não salvou vagas</h3>
              <p>Use o coração nas vagas para guardá-las e voltar depois.</p>
            </div>
          <?php endif; ?>
        </section>
      </div>

      <aside style="display:grid;gap:1rem">
        <div class="list-card profile-card">
          <span class="company-avatar avatar-lg <?= e(avatar_classe($candidatoId)) ?>" aria-hidden="true"><?= e(iniciais($perfil['nome'])) ?></span>
          <h2><?= e($perfil['nome']) ?></h2>
          <p><i class="bi bi-envelope" aria-hidden="true"></i> <?= e($perfil['email']) ?></p>
          <?php if (!empty($perfil['cidade'])): ?><p><i class="bi bi-geo-alt" aria-hidden="true"></i> <?= e($perfil['cidade']) ?></p><?php endif; ?>
          <p><i class="bi bi-file-earmark-person" aria-hidden="true"></i> Currículo em <?= e($perfil['formatoCurriculo'] ?: 'Texto') ?></p>
          <?php if ($recursosCandidato): ?>
            <div class="job-tags">
              <?php foreach (array_slice($recursosCandidato, 0, 4) as $recursoId): ?>
                <?php foreach ($recursos as $recurso): if ((int) $recurso['id'] !== (int) $recursoId) continue; ?>
                  <span class="pill pill-green"><?= e($recurso['nome']) ?></span>
                <?php endforeach; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <a class="btn btn-outline btn-sm" href="editarCandidato.php"><i class="bi bi-pencil" aria-hidden="true"></i> Editar perfil</a>
          <a class="btn btn-ghost btn-sm" href="<?= e($base) ?>controladores/rota.php?acao=sair">Sair da conta</a>
        </div>

        <div class="list-card">
          <div class="list-card-head"><h2>Complete seu perfil</h2><span class="badge badge-soft"><?= $completude ?>%</span></div>
          <div class="strength" style="margin-bottom:.6rem"><span style="width:<?= $completude ?>%;background:var(--green)"></span></div>
          <?php if (empty($perfil['telefone'])): ?><div class="check-line"><i class="bi bi-circle" aria-hidden="true"></i><div><strong>Adicione um telefone</strong><span>Facilita o contato da empresa.</span></div></div><?php endif; ?>
          <?php if (empty($perfil['sobre'])): ?><div class="check-line"><i class="bi bi-circle" aria-hidden="true"></i><div><strong>Escreva um resumo</strong><span>Conte sua experiência em poucas linhas.</span></div></div><?php endif; ?>
          <?php if (!$recursosCandidato): ?><div class="check-line"><i class="bi bi-circle" aria-hidden="true"></i><div><strong>Informe recursos</strong><span>Ajuda a filtrar vagas compatíveis.</span></div></div><?php endif; ?>
          <a class="btn btn-outline btn-sm" href="editarCandidato.php" style="width:100%">Atualizar perfil</a>
        </div>
      </aside>
    </div>

    <?php if ($recomendadas): ?>
      <section style="margin-top:1.6rem">
        <div class="section-head">
          <div><h2>Recomendadas para você</h2><p>Vagas abertas que ainda não estão nas suas candidaturas.</p></div>
          <a class="btn btn-outline btn-sm" href="<?= e($base) ?>vagas.php">Ver todas</a>
        </div>
        <div class="jobs-grid">
          <?php foreach ($recomendadas as $vaga): ?>
            <?= vaga_card($vaga, ['salvas' => $idsSalvas, 'logado' => true]) ?>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
