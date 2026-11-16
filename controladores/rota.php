<?php
/**
 * Controlador central de ações (POST/GET) do IncluiTrabalho.
 * Mantém compatibilidade com as ações antigas do projeto acadêmico.
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/dados.php';
require_once __DIR__ . '/../includes/dados_empresas.php';

function rota_voltar(string $caminho, array $query = []): void
{
    $url = absolute_url($caminho);
    if ($query) {
        $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
    }
    redirect($url);
}

function rota_erro(string $mensagem, string $caminho, array $query = []): void
{
    flash_set('error', $mensagem);
    rota_voltar($caminho, $query);
}

function rota_ok(string $mensagem, string $caminho, array $query = []): void
{
    flash_set('success', $mensagem);
    rota_voltar($caminho, $query);
}

function rota_obj_candidato(array $row): Candidato
{
    $candidato = new Candidato();
    $candidato->setId((int) $row['id']);
    $candidato->setNome($row['nome']);
    $candidato->setEmail($row['email']);
    $candidato->setTelefone($row['telefone'] ?? '');
    $candidato->setFormatoCurriculo($row['formatoCurriculo'] ?? 'Texto');
    return $candidato;
}

function rota_obj_empresa(array $row): Empresa
{
    $empresa = new Empresa();
    $empresa->setId((int) $row['id']);
    $empresa->setNome($row['nome']);
    $empresa->setEmail($row['email']);
    $empresa->setTelefone($row['telefone'] ?? '');
    return $empresa;
}

function rota_destino_pos_login(string $padrao): string
{
    $destino = $_SESSION['redirect_apos_login'] ?? '';
    unset($_SESSION['redirect_apos_login']);
    if (is_string($destino) && $destino !== '') {
        return $destino;
    }
    $informado = post('redirect', get('redirect'));
    if ($informado !== '') {
        $base = absolute_url();
        if (str_starts_with($informado, $base) || (!preg_match('/^[a-z][a-z0-9+.-]*:/i', $informado) && !str_starts_with($informado, '//'))) {
            return str_starts_with($informado, 'http') ? $informado : absolute_url($informado);
        }
    }
    return absolute_url($padrao);
}

$acao = (string) ($_GET['acao'] ?? '');
$metodo = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($metodo === 'POST' && !csrf_valido()) {
    flash_set('error', 'Sua sessão expirou. Revise os dados e tente novamente.');
    $volta = $_SERVER['HTTP_REFERER'] ?? absolute_url('index.php');
    redirect($volta);
}

try {
    switch ($acao) {

        /* ------------------------------------------------------------
         | Autenticação de candidato
         * ---------------------------------------------------------- */
        case 'salvarCandidato': {
            $nome = post('nome');
            $email = mb_strtolower(post('email'));
            $senha = (string) ($_POST['senha'] ?? '');
            $telefone = post('telefone');
            $formato = post('formatoCurriculo', 'Texto');
            $cidade = post('cidade');

            if (mb_strlen($nome) < 3) rota_erro('Informe seu nome completo.', 'telas/cadastrarCandidato.php');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) rota_erro('Informe um e-mail válido.', 'telas/cadastrarCandidato.php');
            if (strlen($senha) < 8) rota_erro('A senha deve ter pelo menos 8 caracteres.', 'telas/cadastrarCandidato.php');

            $pdo = db();
            $stmt = $pdo->prepare('SELECT id FROM candidato WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) rota_erro('Já existe uma conta com este e-mail. Tente entrar.', 'telas/cadastrarCandidato.php');

            $stmt = $pdo->prepare('INSERT INTO candidato (nome, email, senha, telefone, formatoCurriculo, cidade) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $telefone ?: null, $formato ?: 'Texto', $cidade ?: null]);
            $novoId = (int) $pdo->lastInsertId();

            rota_ok('Conta criada com sucesso! Entre com seu e-mail e senha.', 'telas/autenticarCandidato.php', ['cadastro' => 'sucesso']);
        }

        case 'autenticarCandidato': {
            $email = mb_strtolower(post('email'));
            $senha = (string) ($_POST['senha'] ?? '');
            $pdo = db();
            $stmt = $pdo->prepare('SELECT * FROM candidato WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $row = $stmt->fetch();
            if (!$row || !password_verify($senha, $row['senha'])) {
                rota_erro('E-mail ou senha não conferem. Verifique os dados e tente novamente.', 'telas/autenticarCandidato.php');
            }
            session_regenerate_id(true);
            $_SESSION['candidato'] = rota_obj_candidato($row);
            flash_set('success', 'Bem-vindo(a) de volta, ' . explode(' ', trim($row['nome']))[0] . '!');
            redirect(rota_destino_pos_login('telas/homeCandidato.php'));
        }

        case 'editarCandidato': {
            $candidato = exigir_candidato();
            $nome = post('nome');
            $email = mb_strtolower(post('email'));
            $telefone = post('telefone');
            $formato = post('formatoCurriculo', 'Texto');
            $cidade = post('cidade');
            $sobre = post('sobre');

            if (mb_strlen($nome) < 3) rota_erro('Informe seu nome completo.', 'telas/editarCandidato.php');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) rota_erro('Informe um e-mail válido.', 'telas/editarCandidato.php');

            $pdo = db();
            $stmt = $pdo->prepare('SELECT id FROM candidato WHERE email = ? AND id <> ? LIMIT 1');
            $stmt->execute([$email, (int) $candidato->id]);
            if ($stmt->fetch()) rota_erro('Este e-mail já está em uso por outra conta.', 'telas/editarCandidato.php');

            $senha = (string) ($_POST['senha'] ?? '');
            if ($senha !== '' && strlen($senha) < 8) rota_erro('A nova senha deve ter pelo menos 8 caracteres.', 'telas/editarCandidato.php');

            if ($senha !== '') {
                $stmt = $pdo->prepare('UPDATE candidato SET nome=?, email=?, telefone=?, formatoCurriculo=?, cidade=?, sobre=?, senha=? WHERE id=?');
                $stmt->execute([$nome, $email, $telefone ?: null, $formato ?: 'Texto', $cidade ?: null, $sobre ?: null, password_hash($senha, PASSWORD_DEFAULT), (int) $candidato->id]);
            } else {
                $stmt = $pdo->prepare('UPDATE candidato SET nome=?, email=?, telefone=?, formatoCurriculo=?, cidade=?, sobre=? WHERE id=?');
                $stmt->execute([$nome, $email, $telefone ?: null, $formato ?: 'Texto', $cidade ?: null, $sobre ?: null, (int) $candidato->id]);
            }

            $stmt = $pdo->prepare('SELECT * FROM candidato WHERE id = ?');
            $stmt->execute([(int) $candidato->id]);
            $_SESSION['candidato'] = rota_obj_candidato($stmt->fetch());

            rota_ok('Perfil atualizado com sucesso.', 'telas/homeCandidato.php');
        }

        /* ------------------------------------------------------------
         | Autenticação de empresa
         * ---------------------------------------------------------- */
        case 'salvarEmpresa': {
            $nome = post('nome');
            $email = mb_strtolower(post('email'));
            $senha = (string) ($_POST['senha'] ?? '');
            $telefone = post('telefone');
            $cidade = post('cidade');
            $area = post('areaAtuacao');
            $descricao = post('descricao');
            $site = post('site');

            if (mb_strlen($nome) < 2) rota_erro('Informe o nome da empresa.', 'telas/salvarEmpresa.php');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) rota_erro('Informe um e-mail válido.', 'telas/salvarEmpresa.php');
            if (strlen($senha) < 8) rota_erro('A senha deve ter pelo menos 8 caracteres.', 'telas/salvarEmpresa.php');
            if ($site !== '' && !filter_var($site, FILTER_VALIDATE_URL)) rota_erro('Informe um site válido (com http:// ou https://).', 'telas/salvarEmpresa.php');

            $pdo = db();
            $stmt = $pdo->prepare('SELECT id FROM empresa WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            if ($stmt->fetch()) rota_erro('Já existe uma empresa com este e-mail. Tente entrar.', 'telas/salvarEmpresa.php');

            $stmt = $pdo->prepare('INSERT INTO empresa (nome, email, senha, telefone, cidade, areaAtuacao, descricao, site) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $telefone ?: null, $cidade ?: null, $area ?: null, $descricao ?: null, $site ?: null]);

            rota_ok('Cadastro realizado com sucesso! Entre com seu e-mail e senha.', 'telas/autenticar.php', ['cadastro' => 'sucesso']);
        }

        case 'autenticar': {
            $email = mb_strtolower(post('email'));
            $senha = (string) ($_POST['senha'] ?? '');
            $pdo = db();
            $stmt = $pdo->prepare('SELECT * FROM empresa WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $row = $stmt->fetch();
            if (!$row || !password_verify($senha, $row['senha'])) {
                rota_erro('E-mail ou senha não conferem. Verifique os dados e tente novamente.', 'telas/autenticar.php');
            }
            session_regenerate_id(true);
            $_SESSION['empresa'] = rota_obj_empresa($row);
            flash_set('success', 'Bem-vindo(a), ' . $row['nome'] . '!');
            redirect(rota_destino_pos_login('telas/home.php'));
        }

        case 'editarEmpresa': {
            $empresa = exigir_empresa();
            $nome = post('nome');
            $email = mb_strtolower(post('email'));
            $telefone = post('telefone');
            $cidade = post('cidade');
            $area = post('areaAtuacao');
            $descricao = post('descricao');
            $site = post('site');

            if (mb_strlen($nome) < 2) rota_erro('Informe o nome da empresa.', 'telas/editarEmpresa.php');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) rota_erro('Informe um e-mail válido.', 'telas/editarEmpresa.php');
            if ($site !== '' && !filter_var($site, FILTER_VALIDATE_URL)) rota_erro('Informe um site válido (com http:// ou https://).', 'telas/editarEmpresa.php');

            $pdo = db();
            $stmt = $pdo->prepare('SELECT id FROM empresa WHERE email = ? AND id <> ? LIMIT 1');
            $stmt->execute([$email, (int) $empresa->id]);
            if ($stmt->fetch()) rota_erro('Este e-mail já está em uso por outra empresa.', 'telas/editarEmpresa.php');

            $stmt = $pdo->prepare('UPDATE empresa SET nome=?, email=?, telefone=?, cidade=?, areaAtuacao=?, descricao=?, site=? WHERE id=?');
            $stmt->execute([$nome, $email, $telefone ?: null, $cidade ?: null, $area ?: null, $descricao ?: null, $site ?: null, (int) $empresa->id]);

            $empresa->setNome($nome);
            $empresa->setEmail($email);
            $empresa->setTelefone($telefone);
            $_SESSION['empresa'] = $empresa;

            rota_ok('Perfil da empresa atualizado com sucesso.', 'telas/home.php');
        }

        /* ------------------------------------------------------------
         | Sessão
         * ---------------------------------------------------------- */
        case 'sair':
        case 'sairCandidato':
        case 'sairEmpresa': {
            $_SESSION = [];
            session_regenerate_id(true);
            flash_set('success', 'Você saiu da sua conta.');
            rota_voltar('index.php');
        }

        /* ------------------------------------------------------------
         | Candidaturas e vagas salvas
         * ---------------------------------------------------------- */
        case 'candidatar': { rota_erro('Esta funcionalidade é apenas demonstrativa nesta versão com três tabelas.', 'index.php'); }

        case 'salvarVaga': { header('Content-Type: application/json; charset=utf-8'); http_response_code(422); echo json_encode(['ok'=>false,'mensagem'=>'Esta funcionalidade é apenas demonstrativa nesta versão com três tabelas.']); exit; }

        /* ------------------------------------------------------------
         | Empresa: publicar e gerenciar vagas
         * ---------------------------------------------------------- */
        case 'publicarVaga': { rota_erro('Esta funcionalidade é apenas demonstrativa nesta versão com três tabelas.', 'index.php'); }

        case 'alternarVaga': { rota_erro('Esta funcionalidade é apenas demonstrativa nesta versão com três tabelas.', 'index.php'); }

        case 'excluirVaga': { rota_erro('Esta funcionalidade é apenas demonstrativa nesta versão com três tabelas.', 'index.php'); }

        case 'atualizarCandidatura': { rota_erro('Esta funcionalidade é apenas demonstrativa nesta versão com três tabelas.', 'index.php'); }

        /* ------------------------------------------------------------
         | Avaliações de empresas
         * ---------------------------------------------------------- */
        case 'avaliar':
        case 'salvarAvaliacao': {
            $candidato = exigir_candidato();
            $empresaId = (int) post('empresaId');
            $nota = (int) post('nota', '0');
            $comentario = post('comentario');
            $recursos = isset($_POST['recursos']) && is_array($_POST['recursos']) ? $_POST['recursos'] : [];
            if (!empresa_buscar($empresaId)) rota_erro('Empresa não encontrada.', 'telas/busca.php');
            if ($nota < 1 || $nota > 5) rota_erro('Escolha uma nota entre 1 e 5.', 'empresa.php?id=' . $empresaId);
            avaliacao_salvar($empresaId, (int) $candidato->id, $nota, $comentario, $recursos);
            rota_ok('Avaliação registrada. Obrigado por compartilhar sua experiência!', 'empresa.php?id=' . $empresaId);
        }

        /* ------------------------------------------------------------
         * Contato e alertas
         * ---------------------------------------------------------- */
        case 'contato': {
            $nome = post('nome');
            $email = mb_strtolower(post('email'));
            $assunto = post('assunto', 'Contato pelo site');
            $mensagem = post('mensagem');
            if (mb_strlen($nome) < 3) rota_erro('Informe seu nome.', 'contato.php');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) rota_erro('Informe um e-mail válido.', 'contato.php');
            if (mb_strlen($mensagem) < 10) rota_erro('Escreva uma mensagem com pelo menos 10 caracteres.', 'contato.php');
            rota_erro('O formulário de contato é demonstrativo; nenhuma mensagem foi enviada.', 'contato.php');
        }

        case 'alerta': {
            $email = mb_strtolower(post('email'));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) rota_erro('Informe um e-mail válido.', 'vagas.php');
            rota_erro('Alertas são demonstrativos; nenhum alerta foi cadastrado.', 'vagas.php');
        }

        /* ------------------------------------------------------------
         | Compatibilidade com links antigos
         * ---------------------------------------------------------- */
        case 'buscarEmpresa': {
            redirect(absolute_url('telas/busca.php') . '?q=' . urlencode(get('pesquisa')));
        }
        case 'buscarAvaliacoes': {
            redirect(absolute_url('empresa.php') . '?id=' . (int) get('empresaId'));
        }
        case 'excluirCandidato': {
            $candidato = candidato_logado();
            if ($candidato && (int) $candidato->id === (int) get('id')) {
                db()->prepare('DELETE FROM candidato WHERE id = ?')->execute([(int) $candidato->id]);
                $_SESSION = [];
                session_destroy();
                redirect(absolute_url('index.php'));
            }
            rota_erro('Não foi possível excluir a conta.', 'index.php');
        }

        default:
            rota_erro('Ação não reconhecida.', 'index.php');
    }
} catch (PDOException $e) {
    error_log('IncluiTrabalho PDO: ' . $e->getMessage());
    $destinoErro = match ($acao ?? '') {
        'editarCandidato' => 'telas/editarCandidato.php',
        'editarEmpresa' => 'telas/editarEmpresa.php',
        default => 'index.php',
    };
    rota_erro('Não foi possível salvar as alterações. Verifique se o banco foi atualizado com o arquivo corrigir_perfil_banco.sql.', $destinoErro);
} catch (Throwable $e) {
    error_log('IncluiTrabalho: ' . $e->getMessage());
    rota_erro('Ocorreu um erro inesperado. Tente novamente.', 'index.php');
}
