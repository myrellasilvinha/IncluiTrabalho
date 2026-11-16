<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/dados.php';

/**
 * Componentes reutilizáveis de interface.
 */

function avatar_empresa(array $vaga, string $classeExtra = ''): string
{
    $nome = (string) ($vaga['empresaNome'] ?? 'Empresa');
    $id = (int) ($vaga['empresaId'] ?? 0);
    return '<span class="company-avatar ' . e(avatar_classe($id)) . ' ' . e($classeExtra) . '" aria-hidden="true">'
        . e(iniciais($nome)) . '</span>';
}

function estrelas($nota, string $rotulo = ''): string
{
    $nota = (float) $nota;
    $html = '<span class="stars" aria-hidden="true">';
    for ($i = 1; $i <= 5; $i++) {
        if ($nota >= $i - 0.25) {
            $html .= '<i class="bi bi-star-fill"></i>';
        } elseif ($nota >= $i - 0.75) {
            $html .= '<i class="bi bi-star-half"></i>';
        } else {
            $html .= '<i class="bi bi-star"></i>';
        }
    }
    $html .= '</span>';
    if ($rotulo !== '') {
        $html .= '<span class="sr-only">' . e($rotulo) . '</span>';
    }
    return $html;
}

function vaga_card(array $vaga, array $opcoes = []): string
{
    $base = $GLOBALS['base'] ?? '';
    $salvas = $opcoes['salvas'] ?? [];
    $logado = $opcoes['logado'] ?? false;
    $mostrarSalvar = $opcoes['mostrar_salvar'] ?? true;
    $compacto = $opcoes['compacto'] ?? false;

    $id = (int) $vaga['id'];
    $url = $base . 'vaga.php?id=' . $id;
    $recursos = $vaga['recursos'] ?? [];
    $fechada = ($vaga['status'] ?? 'Aberta') !== 'Aberta';
    $salva = in_array($id, array_map('intval', $salvas), true);

    $html = '<article class="job-card' . ($fechada ? ' is-closed' : '') . '">';
    $html .= avatar_empresa($vaga);
    $html .= '<div class="job-main">';
    $html .= '<h3 class="job-title"><a href="' . e($url) . '">' . e($vaga['titulo'] ?? 'Vaga') . '</a></h3>';
    $html .= '<p class="job-company"><strong>' . e($vaga['empresaNome'] ?? 'Empresa') . '</strong>';
    if (!empty($vaga['verificada'])) {
        $html .= ' <span class="badge badge-verified"><i class="bi bi-patch-check-fill" aria-hidden="true"></i> Verificada</span>';
    }
    if ($fechada) {
        $html .= ' <span class="badge badge-muted">Encerrada</span>';
    }
    $html .= '</p>';
    $html .= '<p class="job-meta">';
    if (!empty($vaga['localizacao'])) {
        $html .= '<span><i class="bi bi-geo-alt" aria-hidden="true"></i>' . e($vaga['localizacao']) . '</span>';
    }
    if (!empty($vaga['modalidade'])) {
        $html .= '<span><i class="bi ' . e(modalidade_icone($vaga['modalidade'])) . '" aria-hidden="true"></i>' . e($vaga['modalidade']) . '</span>';
    }
    if (!empty($vaga['tipoContrato'])) {
        $html .= '<span><i class="bi bi-briefcase" aria-hidden="true"></i>' . e($vaga['tipoContrato']) . '</span>';
    }
    if (!$compacto) {
        $html .= '<span><i class="bi bi-clock" aria-hidden="true"></i>' . e(tempo_relativo($vaga['dataPublicacao'] ?? null)) . '</span>';
    }
    $html .= '</p>';
    if (!$compacto && $recursos) {
        $html .= '<div class="job-tags">';
        foreach (array_slice($recursos, 0, 4) as $recurso) {
            $html .= '<span class="pill' . ($recurso === 'Trabalho remoto' ? ' pill-green' : '') . '"><i class="bi ' . e(recurso_icone($recurso)) . '" aria-hidden="true"></i>' . e(recurso_curto($recurso)) . '</span>';
        }
        if (count($recursos) > 4) {
            $html .= '<span class="pill">+' . (count($recursos) - 4) . '</span>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';

    $html .= '<div class="job-side">';
    $html .= '<span class="job-salary">' . e(formatar_salario($vaga['salario'] ?? null));
    if (!empty($vaga['salario'])) {
        $html .= '<small>por mês</small>';
    }
    $html .= '</span>';
    if (!$compacto) {
        $html .= '<span class="job-posted">' . e(ucfirst($vaga['modalidade'] ?? '')) . '</span>';
    }
    if (!$compacto) {
        $html .= '<div class="job-actions">';
        if ($mostrarSalvar) {
            $html .= '<button class="save-btn' . ($salva ? ' is-saved' : '') . '" type="button"'
                . ' data-salvar-vaga="' . $id . '"'
                . ' aria-pressed="' . ($salva ? 'true' : 'false') . '"'
                . ' aria-label="' . ($salva ? 'Remover vaga dos favoritos' : 'Salvar vaga nos favoritos') . '">'
                . '<i class="bi ' . ($salva ? 'bi-heart-fill' : 'bi-heart') . '" aria-hidden="true"></i></button>';
        }
        $html .= '<a class="btn btn-sm" href="' . e($url) . '">Ver vaga</a>';
        $html .= '</div>';
    }
    $html .= '</div></article>';

    return $html;
}

function paginacao_info(int $total, int $exibidos): string
{
    if ($total <= 0) return 'Nenhuma vaga encontrada';
    if ($exibidos >= $total) {
        return $total . ($total === 1 ? ' vaga encontrada' : ' vagas encontradas');
    }
    return 'Mostrando ' . $exibidos . ' de ' . $total . ' vagas';
}

function rotulo_modalidade(string $modalidade): string
{
    return $modalidade === 'Híbrido' ? 'Híbrido' : $modalidade;
}
