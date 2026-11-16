<?php
$pageTitle = 'Termos de uso';
$pageDescription = 'Conheça as regras de uso do IncluiTrabalho para candidatos e empresas.';
$pageClass = 'legal-page';
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container" style="padding-top:2rem;padding-bottom:4rem">
    <nav class="breadcrumb" aria-label="Você está em"><a href="<?= e($base) ?>index.php">Início</a><i class="bi bi-chevron-right" aria-hidden="true"></i><span aria-current="page">Termos de uso</span></nav>
    <article class="prose">
      <h1>Termos de uso</h1>
      <p class="updated">Última atualização: <?= date('d/m/Y') ?></p>

      <p>Ao usar o IncluiTrabalho, você concorda com estes termos. Se não concordar, não utilize a plataforma.</p>

      <h2>1. O que é o IncluiTrabalho</h2>
      <p>É uma plataforma que conecta pessoas com deficiência a empresas que divulgam vagas com informações de acessibilidade. O IncluiTrabalho não é empregador, não intermedeia contratos de trabalho e não garante contratação.</p>

      <h2>2. Cadastro e conta</h2>
      <ul>
        <li>Você deve fornecer informações verdadeiras, completas e atualizadas.</li>
        <li>É responsável por manter a confidencialidade da senha e por todas as atividades da conta.</li>
        <li>É proibido criar contas com dados falsos ou se passar por outra pessoa ou empresa.</li>
        <li>Contas de candidato são gratuitas. Empresas devem indicar um responsável pelo recrutamento.</li>
      </ul>

      <h2>3. Vagas e informações de acessibilidade</h2>
      <p>As empresas são responsáveis pelas informações que publicam, incluindo salário, requisitos e recursos de acessibilidade. O selo “Acessibilidade verificada” indica que as informações passaram por revisão, mas não substitui a confirmação durante o processo seletivo.</p>
      <p>Candidatos podem avaliar empresas. Avaliações devem refletir experiências reais e não podem conter ofensas, discriminação ou dados pessoais de terceiros.</p>

      <h2>4. Condutas proibidas</h2>
      <ul>
        <li>Publicar conteúdo falso, discriminatório, ilegal ou ofensivo.</li>
        <li>Usar a plataforma para spam, cobrança indevida de candidatos ou práticas de recrutamento abusivas.</li>
        <li>Tentar acessar contas, dados ou sistemas sem autorização.</li>
        <li>Copiar ou explorar comercialmente o conteúdo da plataforma sem permissão.</li>
      </ul>

      <h2>5. Conteúdo e propriedade intelectual</h2>
      <p>Você mantém os direitos sobre o conteúdo que publica e concede ao IncluiTrabalho licença para exibi-lo na plataforma. A marca, o layout e os textos institucionais pertencem ao IncluiTrabalho.</p>

      <h2>6. Suspensão e encerramento</h2>
      <p>Podemos suspender ou encerrar contas que violem estes termos, publiquem informações enganosas ou causem risco a outras pessoas. Você pode encerrar sua conta a qualquer momento pelo e-mail <a class="link" href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>.</p>

      <h2>7. Limitação de responsabilidade</h2>
      <p>O IncluiTrabalho não se responsabiliza por negociações, contratações ou danos decorrentes da relação direta entre candidatos e empresas. A plataforma é fornecida “como está”, com esforços contínuos de disponibilidade e segurança.</p>

      <h2>8. Alterações</h2>
      <p>Estes termos podem ser atualizados. Mudanças relevantes serão comunicadas no site. O uso continuado após a atualização indica concordância com a nova versão.</p>

      <h2>9. Contato</h2>
      <p>Dúvidas sobre estes termos: <a class="link" href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>.</p>
    </article>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
