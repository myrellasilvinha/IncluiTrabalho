<?php
$pageTitle = 'Contato';
$pageDescription = 'Fale com a equipe do IncluiTrabalho, tire dúvidas ou reporte problemas de acessibilidade.';
$pageClass = 'contact-page';
require __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados_empresas.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <section class="page-intro">
    <div class="container">
      <nav class="breadcrumb" aria-label="Você está em"><a href="<?= e($base) ?>index.php">Início</a><i class="bi bi-chevron-right" aria-hidden="true"></i><span aria-current="page">Contato</span></nav>
      <span class="eyebrow">Estamos aqui para ajudar</span>
      <h1>Fale com a gente</h1>
      <p>Dúvidas sobre vagas, cadastro, acessibilidade ou parcerias? Preencha o formulário ou envie um e-mail.</p>
    </div>
  </section>

  <div class="container" style="padding-top:2rem;padding-bottom:4rem">
    <div class="dash-grid" style="grid-template-columns:minmax(0,1.4fr) minmax(0,1fr)">
      <form class="panel" action="<?= e($base) ?>controladores/rota.php?acao=contato" method="POST" data-validate>
        <?= csrf_field() ?>
        <h2>Enviar mensagem</h2>
        <p>Respondemos em até 2 dias úteis.</p>
        <div class="form-row">
          <div class="field">
            <label for="nome">Nome *</label>
            <input id="nome" name="nome" type="text" required minlength="3" autocomplete="name">
            <p class="error"></p>
          </div>
          <div class="field">
            <label for="email">E-mail *</label>
            <input id="email" name="email" type="email" required autocomplete="email">
            <p class="error"></p>
          </div>
        </div>
        <div class="field">
          <label for="assunto">Assunto</label>
          <select id="assunto" name="assunto">
            <option>Dúvida sobre vagas</option>
            <option>Dúvida sobre cadastro</option>
            <option>Problema de acessibilidade</option>
            <option>Parceria com empresa</option>
            <option>Imprensa</option>
            <option>Outro assunto</option>
          </select>
        </div>
        <div class="field">
          <label for="mensagem">Mensagem *</label>
          <textarea id="mensagem" name="mensagem" rows="5" required minlength="10" placeholder="Conte como podemos ajudar."></textarea>
          <p class="error"></p>
        </div>
        <button class="btn btn-green" type="submit"><i class="bi bi-send" aria-hidden="true"></i> Enviar mensagem</button>
      </form>

      <aside style="display:grid;gap:1rem">
        <div class="panel">
          <h2 style="font-size:1.1rem">Canais diretos</h2>
          <div class="check-line"><i class="bi bi-envelope" aria-hidden="true"></i><div><strong>E-mail</strong><span><a class="link" href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a></span></div></div>
          <div class="check-line"><i class="bi bi-clock" aria-hidden="true"></i><div><strong>Atendimento</strong><span>Segunda a sexta, das 8h às 18h (horário de Brasília).</span></div></div>
          <div class="check-line"><i class="bi bi-universal-access-circle" aria-hidden="true"></i><div><strong>Acessibilidade</strong><span>Atendimento em Libras sob agendamento por e-mail.</span></div></div>
        </div>

        <div class="panel">
          <h2 style="font-size:1.1rem">Receba alertas de vagas</h2>
          <p style="font-size:.85rem">Cadastre seu e-mail e seja avisado quando surgirem vagas na sua área.</p>
          <form action="<?= e($base) ?>controladores/rota.php?acao=alerta" method="POST" data-validate style="display:grid;gap:.6rem;margin-top:.6rem">
            <?= csrf_field() ?>
            <div class="field">
              <label for="alerta-email">E-mail *</label>
              <input id="alerta-email" name="email" type="email" required autocomplete="email" placeholder="voce@exemplo.com">
              <p class="error"></p>
            </div>
            <div class="field">
              <label for="alerta-cidade">Cidade</label>
              <input id="alerta-cidade" name="cidade" type="text" placeholder="Rio Branco - AC">
            </div>
            <div class="field">
              <label for="alerta-modalidade">Modalidade</label>
              <select id="alerta-modalidade" name="modalidade">
                <option value="">Todas</option>
                <option>Presencial</option>
                <option>Híbrido</option>
                <option>Remoto</option>
              </select>
            </div>
            <button class="btn btn-outline" type="submit">Criar alerta</button>
          </form>
        </div>
      </aside>
    </div>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
