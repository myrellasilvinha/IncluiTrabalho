<?php
$pageTitle = 'Política de privacidade';
$pageDescription = 'Saiba como o IncluiTrabalho coleta, usa e protege seus dados pessoais, em conformidade com a LGPD.';
$pageClass = 'legal-page';
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/cabecalho.php';
?>
<main id="conteudo">
  <div class="container" style="padding-top:2rem;padding-bottom:4rem">
    <nav class="breadcrumb" aria-label="Você está em"><a href="<?= e($base) ?>index.php">Início</a><i class="bi bi-chevron-right" aria-hidden="true"></i><span aria-current="page">Privacidade</span></nav>
    <article class="prose">
      <h1>Política de privacidade</h1>
      <p class="updated">Última atualização: <?= date('d/m/Y') ?></p>

      <p>O IncluiTrabalho trata dados pessoais com transparência e respeito à Lei Geral de Proteção de Dados (Lei nº 13.709/2018). Esta política explica quais dados coletamos, por quê e quais são os seus direitos.</p>

      <h2>1. Dados que coletamos</h2>
      <ul>
        <li><strong>Dados de cadastro:</strong> nome, e-mail, telefone, cidade, senha (armazenada de forma criptografada) e formato de currículo.</li>
        <li><strong>Dados de perfil:</strong> resumo profissional e recursos de acessibilidade que você escolhe informar.</li>
        <li><strong>Dados de uso:</strong> candidaturas enviadas, vagas salvas e interações com empresas.</li>
        <li><strong>Mensagens:</strong> conteúdo enviado pelo formulário de contato.</li>
      </ul>
      <p>Não solicitamos dados sensíveis sobre deficiência no cadastro. Você informa apenas os recursos de acessibilidade que precisa, e essa informação é opcional.</p>

      <h2>2. Como usamos os dados</h2>
      <ul>
        <li>Permitir a criação e o acesso à sua conta.</li>
        <li>Viabilizar candidaturas e o contato entre candidatos e empresas.</li>
        <li>Sugerir vagas compatíveis com os recursos informados.</li>
        <li>Responder mensagens e melhorar a plataforma.</li>
        <li>Cumprir obrigações legais e prevenir fraudes.</li>
      </ul>

      <h2>3. Compartilhamento</h2>
      <p>Seus dados de perfil e candidatura são compartilhados apenas com a empresa à qual você se candidatou. Não vendemos dados pessoais. O compartilhamento com prestadores de serviço ocorre apenas quando necessário para operar a plataforma, sempre com obrigações de confidencialidade.</p>

      <h2>4. Base legal</h2>
      <p>O tratamento ocorre com base na execução de contrato (uso da plataforma), no consentimento (dados opcionais de acessibilidade e alertas por e-mail), no cumprimento de obrigação legal e no legítimo interesse para segurança e melhoria do serviço.</p>

      <h2>5. Retenção e segurança</h2>
      <p>Mantemos os dados enquanto sua conta estiver ativa ou conforme a necessidade legal. Adotamos medidas técnicas como senhas criptografadas, controle de sessão e proteção contra requisições falsas (CSRF). Nenhum sistema é totalmente imune a incidentes, mas trabalhamos para responder rapidamente a qualquer ocorrência.</p>

      <h2>6. Seus direitos</h2>
      <p>Você pode solicitar confirmação de tratamento, acesso, correção, anonimização, portabilidade ou exclusão dos seus dados. Para exercer esses direitos, envie um e-mail para <a class="link" href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>. Responderemos no prazo legal.</p>

      <h2>7. Cookies</h2>
      <p>Usamos cookies essenciais para manter sua sessão e armazenar preferências de acessibilidade. Cookies de análise só são ativados com o seu consentimento, registrado no aviso exibido na primeira visita.</p>

      <h2>8. Contato do controlador</h2>
      <p>Controlador: IncluiTrabalho. E-mail: <a class="link" href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>. Esta política pode ser atualizada; a data acima indica a versão vigente.</p>
    </article>
  </div>
</main>
<?php require __DIR__ . '/includes/rodape.php'; ?>
