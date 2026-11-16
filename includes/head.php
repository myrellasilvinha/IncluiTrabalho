<?php
/**
 * Cabeçalho HTML compartilhado.
 * Variáveis opcionais antes do include:
 *   $pageTitle, $pageDescription, $pageClass, $canonical, $ogImage, $jsonLd (array ou string)
 */
if (!function_exists('e')) {
    require_once __DIR__ . '/config.php';
}
$base = $base ?? '';
$pageTitle = $pageTitle ?? SITE_NAME;
$pageDescription = $pageDescription ?? 'Vagas com acessibilidade verificada, empresas inclusivas e candidaturas simples para pessoas com deficiência.';
$pageClass = $pageClass ?? '';
$canonical = $canonical ?? current_url();
$ogImage = $ogImage ?? absolute_url('assets/og-image.png');
$pageTitleFull = $pageTitle === SITE_NAME ? SITE_NAME . ' — ' . SITE_TAGLINE : $pageTitle . ' | ' . SITE_NAME;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#15345b">
<meta name="csrf-token" content="<?= e(csrf_token()) ?>">
<title><?= e($pageTitleFull) ?></title>
<meta name="description" content="<?= e($pageDescription) ?>">
<link rel="canonical" href="<?= e($canonical) ?>">
<link rel="icon" type="image/svg+xml" href="<?= e($base) ?>assets/favicon.svg">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:title" content="<?= e($pageTitleFull) ?>">
<meta property="og:description" content="<?= e($pageDescription) ?>">
<meta property="og:url" content="<?= e($canonical) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="pt_BR">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($pageTitleFull) ?>">
<meta name="twitter:description" content="<?= e($pageDescription) ?>">
<meta name="twitter:image" content="<?= e($ogImage) ?>">
<link rel="preload" href="<?= e($base) ?>assets/fonts/dm-sans-latin.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e($base) ?>assets/bootstrap-icons.css<?= e(asset_versao('bootstrap-icons.css')) ?>">
<link rel="stylesheet" href="<?= e($base) ?>assets/style.css<?= e(asset_versao('style.css')) ?>">
<?php if (!empty($jsonLd)): ?>
<?= is_array($jsonLd) ? json_ld($jsonLd) : $jsonLd ?>
<?php endif; ?>
<script>document.documentElement.classList.add('js');</script>
</head>
<body class="<?= e($pageClass) ?>">
<a class="skip" href="#conteudo">Pular para o conteúdo</a>
