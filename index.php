<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/dados.php';
require_once __DIR__ . '/includes/dados_empresas.php';
require_once __DIR__ . '/includes/componentes.php';

$pageTitle = SITE_NAME;
$pageDescription = 'Encontre vagas com acessibilidade verificada em empresas inclusivas. Busque por cidade, modalidade e recursos de acessibilidade.';
$pageClass = 'home-page';

$estatisticas = estatisticas_site();
$vagasDestaque = array_slice(vagas_listar(['ordem' => 'recentes']), 0, 4);
$empresas = empresas_destaque(3);
$salvas = candidato_logado() ? vagas_salvas_ids((int) candidato_logado()->id) : [];

$recursos = recursos_disponiveis();
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => SITE_NAME,
    'url' => absolute_url(),
    'description' => $pageDescription,
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => absolute_url('vagas.php') . '?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
];

require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <section class="hero">
    <div class="container hero-grid">
      <div class="hero-copy">
        <span class="eyebrow">Vagas com acessibilidade verificada</span>
        <h1>Oportunidades reais para quem quer <em>trabalhar</em> com dignidade.</h1>
        <p class="hero-sub">Conectamos pessoas com deficiência a empresas comprometidas com inclusão. Veja os recursos de acessibilidade de cada vaga antes de se candidatar.</p>

        <form class="search-card" action="<?= e($base) ?>vagas.php" method="GET" role="search" aria-label="Buscar vagas">
          <div class="search-field">
            <i class="bi bi-search" aria-hidden="true"></i>
            <label class="sr-only" for="busca-cargo">Cargo ou palavra-chave</label>
            <input id="busca-cargo" name="q" type="search" placeholder="Cargo, palavra-chave ou empresa" autocomplete="off">
          </div>
          <div class="search-field">
            <i class="bi bi-geo-alt" aria-hidden="true"></i>
            <label class="sr-only" for="busca-cidade">Cidade</label>
            <input id="busca-cidade" name="cidade" type="search" placeholder="Cidade ou estado" autocomplete="off">
          </div>
          <button class="btn btn-green" type="submit">Buscar vagas</button>
        </form>
        <p class="search-hint">Ex.: desenvolvedor, administrativo, remoto, Rio Branco.</p>

        <div class="hero-chips" aria-label="Buscas populares">
          <span class="hero-chips-label">Populares:</span>
          <a class="chip" href="<?= e($base) ?>vagas.php?modalidade=Remoto"><i class="bi bi-house-door" aria-hidden="true"></i> Remoto</a>
          <a class="chip" href="<?= e($base) ?>vagas.php?tipo=CLT"><i class="bi bi-briefcase" aria-hidden="true"></i> CLT</a>
          <a class="chip" href="<?= e($base) ?>vagas.php?verificada=1"><i class="bi bi-patch-check" aria-hidden="true"></i> Verificadas</a>
          <a class="chip" href="<?= e($base) ?>vagas.php?recursos%5B%5D=Intérprete+de+Libras"><i class="bi bi-hand-index-thumb" aria-hidden="true"></i> Libras</a>
        </div>

        <div class="trust-row">
          <span><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Recursos verificados</span>
          <span><i class="bi bi-shield-lock" aria-hidden="true"></i> Dados protegidos (LGPD)</span>
          <span><i class="bi bi-universal-access-circle" aria-hidden="true"></i> Site acessível WCAG 2.1</span>
        </div>
      </div>

      <div class="hero-art" aria-hidden="true">
        <div class="orb"></div>
        <?php if ($vagasDestaque): $preview = $vagasDestaque[0]; ?>
        <div class="preview-card">
          <span class="badge badge-verified" style="right: 6rem;"><i class="bi bi-patch-check-fill"></i> Acessibilidade verificada</span>
          <div class="preview-head">
            <?= avatar_empresa($preview) ?>
            <div>
              <h2><?= e($preview['titulo']) ?></h2>
              <small><?= e($preview['empresaNome']) ?> · <?= e($preview['modalidade']) ?></small>
            </div>
          </div>
          <div class="preview-list">
            <?php foreach (array_slice($preview['recursos'] ?? [], 0, 4) as $recurso): ?>
              <div><i class="bi <?= e(recurso_icone($recurso)) ?>"></i> <?= e($recurso) ?></div>
            <?php endforeach; ?>
            <div class="off"><i class="bi bi-dash-circle"></i> Sem barreiras de acesso</div>
          </div>
          <div class="preview-foot">
            <div>
              <strong><?= e(formatar_salario($preview['salario'])) ?></strong>
              <small><?= e($preview['tipoContrato'] ?: 'Combinação a definir') ?></small>
            </div>
            <span class="badge badge-verified"><i class="bi bi-check-circle"></i> Aberta</span>
          </div>
        </div>
        <div class="floating-note"><i class="bi bi-briefcase"></i> <?= $estatisticas['vagas'] ?> vagas abertas agora</div>
        <div class="floating-avatars">
          <span class="avatar-stack">
            <span class="avatar-1">MA</span><span class="avatar-2">JS</span><span class="avatar-3">LC</span><span class="avatar-4">+</span>
          </span>
          <?= number_format($estatisticas['empresas'], 0, ',', '.') ?> empresas inclusivas
        </div>
        <?php else: ?>
        <div class="preview-card">
          <div class="preview-head">
            <span class="company-avatar avatar-1">IT</span>
            <div><h2>Sua próxima vaga</h2><small>Publicada por empresas inclusivas</small></div>
          </div>
          <div class="preview-list">
            <div><i class="bi bi-check2-circle"></i> Acessibilidade informada</div>
            <div><i class="bi bi-check2-circle"></i> Candidatura em poucos cliques</div>
            <div><i class="bi bi-check2-circle"></i> Acompanhe cada etapa</div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">Feito para incluir</span>
        <h2>Procurar emprego não precisa ser uma barreira</h2>
        <p>Informação clara sobre acessibilidade, candidatura simples e acompanhamento de cada etapa do processo.</p>
      </div>
      <div class="feature-grid">
        <article class="feature">
          <span class="ico"><i class="bi bi-patch-check" aria-hidden="true"></i></span>
          <h3>Vagas verificadas</h3>
          <p>Cada vaga passa por checagem dos recursos de acessibilidade informados pela empresa.</p>
          <a class="link" href="<?= e($base) ?>vagas.php?verificada=1">Ver vagas verificadas <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </article>
        <article class="feature">
          <span class="ico"><i class="bi bi-universal-access-circle" aria-hidden="true"></i></span>
          <h3>Acessibilidade detalhada</h3>
          <p>Saiba antes de se candidatar se há Libras, rampa, banheiro adaptado, tecnologia assistiva e mais.</p>
          <a class="link" href="<?= e($base) ?>acessibilidade.php">Conhecer recursos <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </article>
        <article class="feature">
          <span class="ico"><i class="bi bi-send-check" aria-hidden="true"></i></span>
          <h3>Candidatura simples</h3>
          <p>Um perfil, várias candidaturas. Acompanhe o status de cada processo dentro da sua conta.</p>
          <a class="link" href="<?= e($base) ?>telas/cadastrarCandidato.php">Criar meu perfil <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </article>
        <article class="feature">
          <span class="ico"><i class="bi bi-buildings" aria-hidden="true"></i></span>
          <h3>Empresas inclusivas</h3>
          <p>Conheça as empresas parceiras, suas avaliações e o compromisso real com a inclusão.</p>
          <a class="link" href="<?= e($base) ?>telas/busca.php">Explorar empresas <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </article>
      </div>
    </div>
  </section>

  <section class="section section-soft" id="vagas-destaque">
    <div class="container">
      <div class="section-head">
        <div>
          <span class="eyebrow navy">Oportunidades abertas</span>
          <h2>Vagas em destaque</h2>
          <p>Selecionadas entre as mais recentes, com recursos de acessibilidade informados.</p>
        </div>
        <a class="btn btn-outline" href="<?= e($base) ?>vagas.php">Ver todas as vagas <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
      </div>

      <?php if ($vagasDestaque): ?>
        <div class="jobs-grid">
          <?php foreach ($vagasDestaque as $vaga): ?>
            <?= vaga_card($vaga, ['salvas' => $salvas, 'logado' => (bool) candidato_logado()]) ?>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty">
          <i class="bi bi-briefcase" aria-hidden="true"></i>
          <h3>Ainda não há vagas publicadas</h3>
          <p>Estamos preparando as primeiras oportunidades. Cadastre seu currículo para ser avisado.</p>
          <a class="btn btn-green" href="<?= e($base) ?>telas/cadastrarCandidato.php">Cadastrar currículo</a>
        </div>
      <?php endif; ?>

      <div class="info-note" style="margin-top:1.2rem;display:flex;gap:.6rem;align-items:flex-start">
        <i class="bi bi-info-circle" aria-hidden="true" style="margin-top:.15rem"></i>
        <span>As vagas exibidas nesta versão de demonstração são exemplos para apresentar a experiência da plataforma.</span>
      </div>
    </div>
  </section>

  <section class="section" id="como-funciona">
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">Como funciona</span>
        <h2>Três passos para a sua próxima oportunidade</h2>
      </div>
      <div class="steps">
        <article class="step">
          <span class="step-num">1</span>
          <i class="bi bi-person-plus" aria-hidden="true"></i>
          <h3>Crie seu perfil</h3>
          <p>Cadastre seus dados e informe quais recursos de acessibilidade você precisa no trabalho.</p>
        </article>
        <article class="step">
          <span class="step-num">2</span>
          <i class="bi bi-search" aria-hidden="true"></i>
          <h3>Encontre a vaga certa</h3>
          <p>Filtre por cidade, modalidade, tipo de contrato e recursos de acessibilidade.</p>
        </article>
        <article class="step">
          <span class="step-num">3</span>
          <i class="bi bi-send-check" aria-hidden="true"></i>
          <h3>Candidate-se e acompanhe</h3>
          <p>Envie sua candidatura em um clique e acompanhe o retorno da empresa na sua área.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="section section-soft">
    <div class="container">
      <div class="section-head">
        <div>
          <span class="eyebrow navy">Quem contrata com inclusão</span>
          <h2>Empresas parceiras</h2>
          <p>Organizações que informam seus recursos de acessibilidade e recebem candidaturas qualificadas.</p>
        </div>
        <a class="btn btn-outline" href="<?= e($base) ?>telas/busca.php">Ver todas <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
      </div>
      <div class="company-grid">
        <?php foreach ($empresas as $empresa): ?>
          <article class="company-card">
            <div class="company-card-head">
              <span class="company-avatar <?= e(avatar_classe($empresa['id'])) ?>" aria-hidden="true"><?= e(iniciais($empresa['nome'])) ?></span>
              <div>
                <h2><?= e($empresa['nome']) ?></h2>
                <small><i class="bi bi-geo-alt" aria-hidden="true"></i> <?= e($empresa['cidade'] ?: 'Brasil') ?></small>
              </div>
            </div>
            <p><?= e(resumir($empresa['descricao'] ?: 'Empresa parceira do IncluiTrabalho.', 120)) ?></p>
            <div class="meta-row">
              <span><i class="bi bi-briefcase" aria-hidden="true"></i> <?= (int) $empresa['totalVagas'] ?> vaga(s)</span>
              <?php if ($empresa['totalAvaliacoes'] > 0): ?>
                <span class="rating"><?= estrelas($empresa['media'], 'Nota ' . $empresa['media'] . ' de 5') ?> <?= e($empresa['media']) ?></span>
              <?php else: ?>
                <span><i class="bi bi-star" aria-hidden="true"></i> Sem avaliações</span>
              <?php endif; ?>
            </div>
            <a class="btn btn-outline btn-sm" href="<?= e($base) ?>empresa.php?id=<?= (int) $empresa['id'] ?>">Conhecer empresa</a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container split">
      <div>
        <span class="eyebrow">Para empresas</span>
        <h2>Contratar com inclusão é mais simples do que parece</h2>
        <p>Publique suas vagas, informe os recursos de acessibilidade disponíveis e receba candidaturas de pessoas qualificadas. Nossa verificação ajuda a transmitir confiança e a atrair os melhores talentos.</p>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Publicação gratuita</strong><span>Cadastre sua empresa e publique a primeira vaga sem custo.</span></div></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Gestão de candidaturas</strong><span>Acompanhe perfis e atualize o status de cada processo.</span></div></div>
        <div class="check-line"><i class="bi bi-check-circle-fill" aria-hidden="true"></i><div><strong>Visibilidade para a inclusão</strong><span>Sua empresa aparece na busca de quem procura um ambiente acessível.</span></div></div>
        <div class="hero-actions">
          <a class="btn btn-green" href="<?= e($base) ?>telas/salvarEmpresa.php">Cadastrar empresa</a>
          <a class="btn btn-outline" href="<?= e($base) ?>para-empresas.php">Saber mais</a>
        </div>
      </div>
      <div class="panel">
        <h2>O que as empresas podem informar</h2>
        <p>Recursos que fazem diferença no dia a dia de quem trabalha:</p>
        <div class="access-grid">
          <?php foreach (array_slice($recursos, 0, 6) as $recurso): ?>
            <div class="access-item is-on">
              <i class="bi <?= e(recurso_icone($recurso['nome'])) ?>" aria-hidden="true"></i>
              <strong><?= e(recurso_curto($recurso['nome'])) ?></strong>
              <p><?= e($recurso['descricao'] ?: recurso_descricao($recurso['nome'])) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-soft">
    <div class="container">
      <div class="section-heading">
        <span class="eyebrow">Dúvidas frequentes</span>
        <h2>Perguntas que recebemos</h2>
      </div>
      <div class="faq">
        <details class="faq-item">
          <summary>O IncluiTrabalho cobra alguma taxa de candidatos?</summary>
          <div class="faq-body">Não. Para quem busca vaga, o uso da plataforma é totalmente gratuito: criar perfil, salvar vagas, se candidatar e acompanhar processos.</div>
        </details>
        <details class="faq-item">
          <summary>O que significa uma vaga verificada?</summary>
          <div class="faq-body">A empresa declarou os recursos de acessibilidade disponíveis e esses dados foram conferidos pela nossa equipe. O selo indica que as informações foram revisadas antes da publicação.</div>
        </details>
        <details class="faq-item">
          <summary>Preciso informar minha deficiência no cadastro?</summary>
          <div class="faq-body">O cadastro pede apenas os recursos de acessibilidade de que você precisa. Você escolhe o que compartilhar e pode editar essas informações quando quiser.</div>
        </details>
        <details class="faq-item">
          <summary>Como uma empresa publica uma vaga?</summary>
          <div class="faq-body">Basta criar uma conta de empresa, preencher o perfil da organização e publicar a vaga informando localização, contrato, salário e recursos de acessibilidade. O painel permite editar e encerrar vagas a qualquer momento.</div>
        </details>
        <details class="faq-item">
          <summary>O site é acessível para tecnologias assistivas?</summary>
          <div class="faq-body">Sim. O IncluiTrabalho segue as diretrizes WCAG 2.1 nível AA, com navegação por teclado, contraste adequado, textos alternativos e ferramentas de ajuste visual no canto da tela.</div>
        </details>
      </div>
    </div>
  </section>

  <section class="section" style="padding-top:0">
    <div class="container">
      <div class="cta-band">
        <div>
          <h2>Sua próxima oportunidade pode estar aqui</h2>
          <p>Crie seu perfil gratuitamente e receba vagas compatíveis com o que você precisa.</p>
        </div>
        <a class="btn btn-light btn-lg" href="<?= e($base) ?>telas/cadastrarCandidato.php">Criar conta grátis <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
