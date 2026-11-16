<?php
$pageTitle = 'Para empresas';
$pageDescription = 'Publique vagas com acessibilidade verificada, receba candidaturas qualificadas e fortaleça a inclusão na sua empresa.';
$pageClass = 'companies-page';
require __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';
require_once __DIR__ . '/includes/dados_empresas.php';

$estatisticas = estatisticas_site();
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-copy">
        <span class="eyebrow navy">Para empresas</span>
        <h1>Contrate talentos que vão <em>transformar</em> o seu negócio.</h1>
        <p class="hero-sub">Publique vagas com informações reais de acessibilidade e alcance profissionais qualificados que buscam um ambiente de trabalho respeitoso e inclusivo.</p>
        <div class="hero-actions">
          <a class="btn btn-green btn-lg" href="<?= e($base) ?>telas/salvarEmpresa.php">Cadastrar empresa grátis</a>
          <a class="btn btn-outline btn-lg" href="<?= e($base) ?>telas/autenticar.php">Já tenho conta</a>
        </div>
        <div class="trust-row">
          <span><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Publicação gratuita</span>
          <span><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Sem contrato de fidelidade</span>
          <span><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Suporte por e-mail</span>
        </div>
      </div>
      <div class="panel">
        <span class="eyebrow">Impacto da inclusão</span>
        <h2 style="margin-top:.8rem">Por que contratar com acessibilidade</h2>
        <div class="check-line"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i><div><strong>Equipes mais diversas</strong><span>Diferentes repertórios geram soluções melhores.</span></div></div>
        <div class="check-line"><i class="bi bi-people" aria-hidden="true"></i><div><strong>Mais engajamento</strong><span>Ambientes acessíveis retêm talentos por mais tempo.</span></div></div>
        <div class="check-line"><i class="bi bi-award" aria-hidden="true"></i><div><strong>Marca empregadora</strong><span>Seu perfil público mostra o compromisso real com a inclusão.</span></div></div>
        <div class="check-line"><i class="bi bi-shield-check" aria-hidden="true"></i><div><strong>Conformidade com a Lei 8.213</strong><span>Apoio para ampliar a contratação de pessoas com deficiência.</span></div></div>
        <a class="btn btn-outline" href="<?= e($base) ?>telas/salvarEmpresa.php" style="width:100%;margin-top:.6rem">Começar agora</a>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">Como funciona</span>
        <h2>Da publicação à contratação, em três passos</h2>
      </div>
      <div class="steps">
        <article class="step"><span class="step-num">1</span><i class="bi bi-building-add" aria-hidden="true"></i><h3>Cadastre a empresa</h3><p>Crie a conta, descreva o negócio e informe os canais de contato.</p></article>
        <article class="step"><span class="step-num">2</span><i class="bi bi-file-earmark-plus" aria-hidden="true"></i><h3>Publique a vaga</h3><p>Detalhe as atividades, requisitos e os recursos de acessibilidade disponíveis.</p></article>
        <article class="step"><span class="step-num">3</span><i class="bi bi-people" aria-hidden="true"></i><h3>Gerencie candidaturas</h3><p>Acompanhe perfis, atualize o status e converse com as pessoas candidatas.</p></article>
      </div>
    </div>
  </section>

  <section class="section section-soft">
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow navy">Planos</span>
        <h2>Comece de graça. Cresça quando fizer sentido.</h2>
        <p>Nenhum plano exige cartão de crédito nesta versão de demonstração.</p>
      </div>
      <div class="pricing-grid">
        <article class="plan">
          <span class="plan-name">Gratuito</span>
          <div class="price">R$ 0 <small>/mês</small></div>
          <p>Para empresas que estão começando a contratar com inclusão.</p>
          <ul>
            <li><i class="bi bi-check2" aria-hidden="true"></i> 1 vaga ativa por vez</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Perfil público da empresa</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Gestão de candidaturas</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Avaliações de candidatos</li>
          </ul>
          <a class="btn btn-outline" href="<?= e($base) ?>telas/salvarEmpresa.php">Começar grátis</a>
        </article>
        <article class="plan featured">
          <span class="plan-name">Inclusão</span>
          <div class="price">R$ 0 <small>/mês</small></div>
          <p>Para quem quer ampliar o alcance e destacar o compromisso.</p>
          <ul>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Vagas ilimitadas</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Selo de empresa verificada</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Destaque na página inicial</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Relatório de candidaturas</li>
          </ul>
          <a class="btn btn-light" href="<?= e($base) ?>telas/salvarEmpresa.php">Criar conta</a>
        </article>
        <article class="plan">
          <span class="plan-name">Parceiro</span>
          <div class="price">Sob consulta</div>
          <p>Para organizações com programas estruturados de diversidade.</p>
          <ul>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Tudo do plano Inclusão</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Consultoria de acessibilidade</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Conteúdo conjunto</li>
            <li><i class="bi bi-check2" aria-hidden="true"></i> Atendimento dedicado</li>
          </ul>
          <a class="btn btn-outline" href="<?= e($base) ?>contato.php">Falar com a equipe</a>
        </article>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-heading"><span class="eyebrow">Dúvidas</span><h2>Perguntas de empresas</h2></div>
      <div class="faq">
        <details class="faq-item"><summary>Quanto custa publicar uma vaga?</summary><div class="faq-body">Nesta versão, a publicação é gratuita. Você cria a conta, publica a vaga e gerencia as candidaturas sem custo.</div></details>
        <details class="faq-item"><summary>Como funciona a verificação de acessibilidade?</summary><div class="faq-body">A empresa declara os recursos disponíveis na vaga. Nossa equipe revisa as informações antes de conceder o selo de verificação, que fica visível para os candidatos.</div></details>
        <details class="faq-item"><summary>Posso editar ou encerrar uma vaga depois?</summary><div class="faq-body">Sim. No painel da empresa você pode editar qualquer vaga, encerrar candidaturas ou reabrir a oportunidade quando quiser.</div></details>
        <details class="faq-item"><summary>Como recebo as candidaturas?</summary><div class="faq-body">Todas as candidaturas aparecem no painel com nome, e-mail, telefone e formato de currículo. Você pode atualizar o status de cada processo para manter a pessoa informada.</div></details>
        <details class="faq-item"><summary>A vaga precisa ter salário informado?</summary><div class="faq-body">Não é obrigatório, mas vagas com faixa salarial clara recebem mais candidaturas. Se não informar, aparece “A combinar”.</div></details>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0">
    <div class="container">
      <div class="cta-band">
        <div><h2>Vamos construir um processo seletivo mais inclusivo?</h2><p>Cadastre sua empresa e publique a primeira vaga hoje.</p></div>
        <a class="btn btn-light btn-lg" href="<?= e($base) ?>telas/salvarEmpresa.php">Cadastrar empresa <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
