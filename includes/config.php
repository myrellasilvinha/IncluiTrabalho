<?php
/**
 * Configuração, sessão e funções compartilhadas do IncluiTrabalho.
 * Este arquivo não deve produzir saída antes do envio de cabeçalhos.
 */

require_once __DIR__ . '/../modelos/Candidato.php';
require_once __DIR__ . '/../modelos/Empresa.php';

$base = $base ?? '';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

if (!defined('SITE_NAME')) define('SITE_NAME', 'IncluiTrabalho');
if (!defined('SITE_TAGLINE')) define('SITE_TAGLINE', 'Vagas com acessibilidade verificada.');
if (!defined('SITE_EMAIL')) define('SITE_EMAIL', 'contato@incluitrabalho.com.br');

function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function post(string $chave, $padrao = ''): string
{
    return isset($_POST[$chave]) ? trim((string) $_POST[$chave]) : $padrao;
}

function get(string $chave, $padrao = ''): string
{
    return isset($_GET[$chave]) ? trim((string) $_GET[$chave]) : $padrao;
}

function site_base_path(): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
    $dir = rtrim(dirname($script), '/');
    foreach (['/telas', '/controladores'] as $suffix) {
        if (substr($dir, -strlen($suffix)) === $suffix) {
            $dir = substr($dir, 0, -strlen($suffix));
        }
    }
    return $dir === '/' ? '' : $dir;
}

function site_scheme(): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') == 443);
    return $https ? 'https' : 'http';
}

function absolute_url(string $caminho = ''): string
{
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return site_scheme() . '://' . $host . site_base_path() . '/' . ltrim($caminho, '/');
}

function current_url(): string
{
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return site_scheme() . '://' . $host . ($_SERVER['REQUEST_URI'] ?? '/');
}

function asset_url(string $caminho): string
{
    $base = $GLOBALS['base'] ?? '';
    return $base . 'assets/' . ltrim($caminho, '/');
}

/**
 * Versão do arquivo estático para evitar cache antigo após atualizações.
 */
function asset_versao(string $caminho): string
{
    $arquivo = __DIR__ . '/../assets/' . ltrim($caminho, '/');
    return is_file($arquivo) ? '?v=' . filemtime($arquivo) : '';
}

function url(string $caminho = ''): string
{
    $base = $GLOBALS['base'] ?? '';
    return $base . ltrim($caminho, '/');
}

function redirect(string $destino): void
{
    header('Location: ' . $destino);
    exit;
}

function safe_redirect(string $destino, string $padrao = 'index.php'): string
{
    $destino = trim($destino);
    if ($destino === '' || preg_match('/^[a-z][a-z0-9+.-]*:/i', $destino) || str_starts_with($destino, '//')) {
        return $padrao;
    }
    return $destino;
}

function flash_set(string $tipo, string $mensagem): void
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'mensagem' => $mensagem];
}

function flash_get(): array
{
    $mensagens = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $mensagens;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valido(): bool
{
    $enviado = $_POST['_csrf'] ?? '';
    return is_string($enviado) && $enviado !== '' && hash_equals(csrf_token(), $enviado);
}

function exigir_csrf(string $destino): void
{
    if (!csrf_valido()) {
        flash_set('error', 'Sua sessão expirou. Tente novamente.');
        redirect($destino);
    }
}

function candidato_logado(): ?object
{
    return $_SESSION['candidato'] ?? null;
}

function empresa_logada(): ?object
{
    return $_SESSION['empresa'] ?? null;
}

function exigir_candidato(string $destino = 'telas/autenticarCandidato.php'): object
{
    $candidato = candidato_logado();
    if (!$candidato) {
        $_SESSION['redirect_apos_login'] = current_url();
        flash_set('info', 'Entre na sua conta para continuar.');
        redirect(absolute_url($destino));
    }
    return $candidato;
}

function exigir_empresa(string $destino = 'telas/autenticar.php'): object
{
    $empresa = empresa_logada();
    if (!$empresa) {
        $_SESSION['redirect_apos_login'] = current_url();
        flash_set('info', 'Entre com a conta da empresa para continuar.');
        redirect(absolute_url($destino));
    }
    return $empresa;
}

function formatar_salario($valor): string
{
    if ($valor === null || $valor === '' || (float) $valor <= 0) {
        return 'A combinar';
    }
    $valor = (float) $valor;
    if (floor($valor) == $valor) {
        return 'R$ ' . number_format($valor, 0, ',', '.');
    }
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function tempo_relativo(?string $data): string
{
    if (!$data) return '';
    $timestamp = strtotime($data);
    if (!$timestamp) return '';
    $diff = time() - $timestamp;
    if ($diff < 60) return 'agora mesmo';
    if ($diff < 3600) return 'há ' . floor($diff / 60) . ' min';
    if ($diff < 86400) return 'há ' . floor($diff / 3600) . ' h';
    if ($diff < 604800) {
        $dias = floor($diff / 86400);
        return 'há ' . $dias . ($dias == 1 ? ' dia' : ' dias');
    }
    return date('d/m/Y', $timestamp);
}

function formatar_data(?string $data): string
{
    if (!$data) return '';
    $ts = strtotime($data);
    return $ts ? date('d/m/Y', $ts) : '';
}

function iniciais(string $nome): string
{
    $partes = preg_split('/\s+/', trim($nome)) ?: [];
    $partes = array_values(array_filter($partes));
    if (count($partes) === 0) return '?';
    if (count($partes) === 1) return mb_strtoupper(mb_substr($partes[0], 0, 2));
    return mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr(end($partes), 0, 1));
}

function avatar_classe($id): string
{
    $n = (int) $id;
    return 'avatar-' . (($n % 6) + 1);
}

function modalidade_icone(string $modalidade): string
{
    return match (mb_strtolower($modalidade)) {
        'remoto' => 'bi-house-door',
        'híbrido', 'hibrido' => 'bi-arrow-left-right',
        default => 'bi-building',
    };
}

function recurso_mapa(): array
{
    return [
        'Trabalho remoto' => [
            'icone' => 'bi-house-door',
            'curto' => 'Remoto',
            'descricao' => 'Possibilidade de executar as atividades remotamente.',
        ],
        'Acesso sem barreiras físicas' => [
            'icone' => 'bi-universal-access-circle',
            'curto' => 'Sem barreiras',
            'descricao' => 'Circulação acessível no ambiente de trabalho.',
        ],
        'Materiais digitais acessíveis' => [
            'icone' => 'bi-file-earmark-text',
            'curto' => 'Materiais acessíveis',
            'descricao' => 'Conteúdos compatíveis com tecnologias assistivas.',
        ],
        'Intérprete de Libras' => [
            'icone' => 'bi-hand-index-thumb',
            'curto' => 'Libras',
            'descricao' => 'Apoio de interpretação em Libras.',
        ],
        'Banheiro adaptado' => [
            'icone' => 'bi-badge-wc',
            'curto' => 'Banheiro adaptado',
            'descricao' => 'Sanitários acessíveis.',
        ],
        'Sinalização em braile' => [
            'icone' => 'bi-grid-3x3-gap',
            'curto' => 'Braile',
            'descricao' => 'Identificação tátil e em braile.',
        ],
        'Tecnologia assistiva' => [
            'icone' => 'bi-laptop',
            'curto' => 'Tecnologia assistiva',
            'descricao' => 'Equipamentos ou softwares de apoio.',
        ],
        'Horário flexível' => [
            'icone' => 'bi-clock-history',
            'curto' => 'Horário flexível',
            'descricao' => 'Flexibilidade de horário quando disponível.',
        ],
    ];
}

function recurso_info(string $nome): array
{
    $mapa = recurso_mapa();
    return $mapa[$nome] ?? [
        'icone' => 'bi-check2-circle',
        'curto' => $nome,
        'descricao' => 'Recurso informado pela empresa.',
    ];
}

function recurso_icone(string $nome): string
{
    return recurso_info($nome)['icone'];
}

function recurso_curto(string $nome): string
{
    return recurso_info($nome)['curto'];
}

function recurso_descricao(string $nome): string
{
    return recurso_info($nome)['descricao'];
}

function status_candidatura_classe(string $status): string
{
    return match (mb_strtolower($status)) {
        'em análise', 'em analise' => 'analise',
        'aprovada' => 'aprovada',
        'rejeitada' => 'rejeitada',
        default => 'enviada',
    };
}

function json_ld(array $dados): string
{
    return '<script type="application/ld+json">' . json_encode(
        $dados,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) . '</script>';
}

function resumir(string $texto, int $limite = 150): string
{
    $texto = trim(preg_replace('/\s+/', ' ', $texto));
    if (mb_strlen($texto) <= $limite) return $texto;
    return rtrim(mb_substr($texto, 0, $limite - 1)) . '…';
}
