<?php
$base = '../';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados.php';
require_once __DIR__ . '/../includes/dados_empresas.php';
require_once __DIR__ . '/../includes/componentes.php';

$empresaSessao = exigir_empresa();
$empresaId = (int) $empresaSessao->id;

$pdo = db_tentar();
$empresa = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM empresa WHERE id = ? LIMIT 1');
        $stmt->execute([$empresaId]);
        $empresa = $stmt->fetch();
    } catch (Throwable $e) {
    }
}
$empresa = $empresa ?: ['nome' => $empresaSessao->nome, 'email' => $empresaSessao->email, 'telefone' => '', 'cidade' => '', 'descricao' => '', 'site' => '', 'areaAtuacao' => '', 'verificada' => 0];

$vagas = vaga_do_empresa($empresaId);
$candidaturas = candidaturas_empresa($empresaId);
$vagasAbertas = count(array_filter($vagas, fn($v) => $v['status'] === 'Aberta'));
$novas = count(array_filter($candidaturas, fn($c) => $c['status'] === 'Enviada'));

$pageTitle = 'Painel da empresa';
$pageDescription = 'Publique vagas, acompanhe candidaturas e gerencie o perfil da sua empresa no IncluiTrabalho.';
$pageClass = 'dashboard-page';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container dashboard">
    <div class="dash-head">
      <div>
        <span class="eyebrow navy">Painel da empresa</span>
        <h1><?= e($empresa['nome']) ?></h1>
        <p>Gerencie vagas, candidaturas e o perfil público da empresa.</p>
      </div>
      <div style="display:flex;gap:.5rem;flex-wrap:wrap">
        <a class="btn btn-outline" href="<?= e($base) ?>empresa.php?id=<?= $empresaId ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i> Ver perfil público</a>
        <a class="btn btn-green" href="publicarVaga.php"><i class="bi bi-plus-lg" aria-hidden="true"></i> Publicar vaga</a>
      </div>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <span class="ico"><i class="bi bi-briefcase" aria-hidden="true"></i></span>
        <div><span class="stat-value"><?= $vagasAbertas ?></span><span class="stat-label">Vagas abertas</span></div>
      </div>
      <div class="stat-card">
        <span class="ico"><i class="bi bi-people" aria-hidden="true"></i></span>
        <div><span class="stat-value"><?= count($candidaturas) ?></span><span class="stat-label">Candidaturas recebidas</span></div>
      </div>
      <div class="stat-card">
        <span class="ico"><i class="bi bi-bell" aria-hidden="true"></i></span>
        <div><span class="stat-value"><?= $novas ?></span><span class="stat-label">Novas para analisar</span></div>
      </div>
    </div>

    <section class="list-card" style="margin-bottom:1rem" aria-labelledby="minhas-vagas">
      <div class="list-card-head">
        <h2 id="minhas-vagas">Minhas vagas</h2>
        <a class="btn btn-outline btn-sm" href="publicarVaga.php"><i class="bi bi-plus-lg" aria-hidden="true"></i> Nova vaga</a>
      </div>
      <?php if ($vagas): ?>
        <div class="table-wrap">
          <table>
            <caption class="sr-only">Vagas publicadas pela empresa</caption>
            <thead>
              <tr><th scope="col">Vaga</th><th scope="col">Modalidade</th><th scope="col">Candidaturas</th><th scope="col">Status</th><th scope="col">Ações</th></tr>
            </thead>
            <tbody>
              <?php foreach ($vagas as $vaga): ?>
                <tr>
                  <td>
                    <strong><a class="link" href="<?= e($base) ?>vaga.php?id=<?= (int) $vaga['id'] ?>"><?= e($vaga['titulo']) ?></a></strong>
                    <div class="muted" style="font-size:.74rem"><?= e($vaga['localizacao'] ?: 'Local a combinar') ?> · <?= e(tempo_relativo($vaga['dataPublicacao'])) ?></div>
                  </td>
                  <td><?= e($vaga['modalidade']) ?></td>
                  <td><?= (int) $vaga['candidaturas'] ?></td>
                  <td><span class="status-pill <?= $vaga['status'] === 'Aberta' ? 'aprovada' : 'rejeitada' ?>"><?= e($vaga['status']) ?></span></td>
                  <td>
                    <div style="display:flex;gap:.3rem;flex-wrap:wrap">
                      <a class="btn btn-outline btn-sm" href="publicarVaga.php?id=<?= (int) $vaga['id'] ?>">Editar</a>
                      <form class="inline-form" method="POST" action="<?= e($base) ?>controladores/rota.php?acao=alternarVaga">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $vaga['id'] ?>">
                        <button class="btn btn-outline btn-sm" type="submit"><?= $vaga['status'] === 'Aberta' ? 'Encerrar' : 'Reabrir' ?></button>
                      </form>
                      <form class="inline-form" method="POST" action="<?= e($base) ?>controladores/rota.php?acao=excluirVaga">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $vaga['id'] ?>">
                        <button class="btn btn-ghost btn-sm" type="submit" data-confirm="Excluir esta vaga? Esta ação não pode ser desfeita."><i class="bi bi-trash" aria-hidden="true"></i><span class="sr-only">Excluir vaga</span></button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="empty">
          <i class="bi bi-briefcase" aria-hidden="true"></i>
          <h3>Nenhuma vaga publicada</h3>
          <p>Publique a primeira vaga e comece a receber candidaturas de profissionais qualificados.</p>
          <a class="btn btn-green" href="publicarVaga.php">Publicar minha primeira vaga</a>
        </div>
      <?php endif; ?>
    </section>

    <div class="dash-grid">
      <section class="list-card" aria-labelledby="candidaturas-recebidas">
        <div class="list-card-head">
          <h2 id="candidaturas-recebidas">Candidaturas recebidas</h2>
          <span class="badge badge-soft"><?= count($candidaturas) ?> no total</span>
        </div>
        <?php if ($candidaturas): ?>
          <div class="table-wrap">
            <table>
              <caption class="sr-only">Candidaturas recebidas pela empresa</caption>
              <thead>
                <tr><th scope="col">Candidato</th><th scope="col">Vaga</th><th scope="col">Data</th><th scope="col">Status</th></tr>
              </thead>
              <tbody>
                <?php foreach ($candidaturas as $candidatura): ?>
                  <tr>
                    <td>
                      <strong><?= e($candidatura['candidatoNome']) ?></strong>
                      <div class="muted" style="font-size:.74rem"><a class="link" href="mailto:<?= e($candidatura['candidatoEmail']) ?>"><?= e($candidatura['candidatoEmail']) ?></a><?= $candidatura['candidatoTelefone'] ? ' · ' . e($candidatura['candidatoTelefone']) : '' ?></div>
                      <div class="muted" style="font-size:.72rem">Currículo: <?= e($candidatura['candidatoCurriculo'] ?: 'Texto') ?></div>
                    </td>
                    <td><?= e($candidatura['vagaTitulo']) ?></td>
                    <td><?= e(formatar_data($candidatura['dataCandidatura'])) ?></td>
                    <td>
                      <form class="inline-form" method="POST" action="<?= e($base) ?>controladores/rota.php?acao=atualizarCandidatura">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $candidatura['id'] ?>">
                        <label class="sr-only" for="status-<?= (int) $candidatura['id'] ?>">Status da candidatura de <?= e($candidatura['candidatoNome']) ?></label>
                        <select id="status-<?= (int) $candidatura['id'] ?>" name="status">
                          <?php foreach (['Enviada', 'Em análise', 'Aprovada', 'Rejeitada'] as $status): ?>
                            <option value="<?= e($status) ?>" <?= $candidatura['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                          <?php endforeach; ?>
                        </select>
                        <button class="btn btn-outline btn-sm" type="submit">Salvar</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="empty" style="border:0;padding:1.5rem">
            <i class="bi bi-people" aria-hidden="true"></i>
            <h3>Nenhuma candidatura ainda</h3>
            <p>Quando alguém se candidatar, os dados aparecerão aqui.</p>
          </div>
        <?php endif; ?>
      </section>

      <aside style="display:grid;gap:1rem">
        <div class="list-card profile-card">
          <span class="company-avatar avatar-lg <?= e(avatar_classe($empresaId)) ?>" aria-hidden="true"><?= e(iniciais($empresa['nome'])) ?></span>
          <h2><?= e($empresa['nome']) ?></h2>
          <p><i class="bi bi-envelope" aria-hidden="true"></i> <?= e($empresa['email']) ?></p>
          <?php if (!empty($empresa['cidade'])): ?><p><i class="bi bi-geo-alt" aria-hidden="true"></i> <?= e($empresa['cidade']) ?></p><?php endif; ?>
          <?php if (!empty($empresa['areaAtuacao'])): ?><p><i class="bi bi-tag" aria-hidden="true"></i> <?= e($empresa['areaAtuacao']) ?></p><?php endif; ?>
          <?php if (!empty($empresa['verificada'])): ?><span class="badge badge-verified"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Empresa verificada</span><?php endif; ?>
          <a class="btn btn-outline btn-sm" href="editarEmpresa.php"><i class="bi bi-pencil" aria-hidden="true"></i> Editar perfil</a>
          <a class="btn btn-ghost btn-sm" href="<?= e($base) ?>controladores/rota.php?acao=sair">Sair da conta</a>
        </div>

        <div class="list-card">
          <div class="list-card-head"><h2>Dicas para contratar</h2></div>
          <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Descreva a acessibilidade</strong><span>Vagas com recursos informados recebem mais candidaturas.</span></div></div>
          <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Responda rápido</strong><span>Atualize o status para manter o candidato informado.</span></div></div>
          <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Complete o perfil</strong><span>Empresas com descrição e site transmitem mais confiança.</span></div></div>
        </div>
      </aside>
    </div>
  </div>
</main>
<?php require __DIR__ . '/../includes/rodape.php'; ?>
