# IncluiTrabalho

Plataforma de vagas com acessibilidade verificada, feita em PHP + MySQL para rodar no XAMPP.

O site conecta pessoas com deficiência a empresas comprometidas com acessibilidade e reúne, em um só lugar, vagas, recursos de acessibilidade, candidaturas, perfis de empresas e avaliações de quem já trabalhou nelas.

## Requisitos

- PHP 8.0 ou superior (com PDO MySQL)
- MySQL 5.7+ ou MariaDB
- Apache (XAMPP, Laragon ou similar)

## Instalação rápida (XAMPP)

1. Copie a pasta do projeto para `C:\xampp\htdocs\IncluiTrabalho`.
2. Inicie **Apache** e **MySQL** no painel do XAMPP.
3. Crie o banco importando `gestao.sql` no phpMyAdmin:
   - Acesse <http://localhost/phpmyadmin>
   - Aba **Importar** → selecione `gestao.sql` → **Executar**
   - Se o banco `gestao` já existir com dados, importe somente `atualizar_banco.sql`.
4. (Opcional, recomendado) Popule os dados de demonstração:

   ```powershell
   C:\xampp\php\php.exe scripts\instalar_demo.php
   ```

   O script é idempotente: pode ser executado novamente sem duplicar registros.

5. Acesse <http://localhost/IncluiTrabalho/>.

A conexão padrão está em `conexao/conexao.php` (banco `gestao`, usuário `root`, sem senha). Ajuste se o seu MySQL usar outra configuração.

## Contas de demonstração

Senha de todas as contas: `demo1234`

| Perfil | E-mail |
| --- | --- |
| Candidata | `candidato@demo.com` |
| Empresa (TechSolutions) | `empresa@demo.com` |
| Empresa (Instituto Nova Inclusão) | `instituto@demo.com` |
| Empresa (ConectaTech) | `conecta@demo.com` |
| Empresa (Criativa Digital) | `criativa@demo.com` |

As empresas de demonstração usam a mesma senha. O logotipo de cada empresa é gerado a partir das iniciais.

## O que o site oferece

### Para candidatos

- Cadastro, login e edição de perfil.
- Busca de vagas com filtros por modalidade, nível, tipo de contrato, recursos de acessibilidade e texto livre.
- Página de detalhe da vaga com descrição, requisitos, benefícios, recursos de acessibilidade e empresa.
- Candidatura em um clique (com proteção CSRF).
- Vagas salvas/favoritas, com atualização via AJAX.
- Painel com candidaturas, situação de cada processo, vagas salvas e recomendações.
- Compartilhamento de vaga por link ou WhatsApp.
- Avaliação de empresas (nota, comentário e recursos encontrados).

### Para empresas

- Cadastro, login e edição do perfil público.
- Publicação, edição, encerramento, reabertura e exclusão de vagas.
- Seleção de recursos de acessibilidade por vaga.
- Painel com vagas abertas, candidaturas recebidas e atualização de status (Enviada, Em análise, Aprovada, Rejeitada).
- Página pública da empresa com vagas e avaliações.

### Recursos gerais

- Layout responsivo (mobile-first) e navegação por teclado.
- Painel de acessibilidade: alto contraste, fonte ampliada, espaçamento de texto, redução de movimento e destaque de links.
- HTML semântico, `skip link`, foco visível, rótulos ARIA e textos alternativos.
- Páginas institucionais: Sobre, Para empresas, Acessibilidade, Privacidade, Termos e Contato.
- Página 404 personalizada, `sitemap.php` e metadados Open Graph.
- Fontes e ícones hospedados localmente em `assets/fonts/`, sem dependência de CDN.
- Cache-busting automático de CSS/JS via `asset_versao()`.

## Estrutura do projeto

```
IncluiTrabalho/
├─ index.php, vagas.php, vaga.php, empresa.php   # páginas públicas
├─ para-empresas.php, sobre.php, acessibilidade.php
├─ privacidade.php, termos.php, contato.php, 404.php
├─ telas/            # autenticação, cadastros, dashboards e edição
├─ controladores/    # rota.php (ações, CSRF, login, candidaturas, vagas)
├─ includes/         # config, cabeçalho, rodapé, componentes e dados
├─ modelos/          # Candidato, Empresa e modelos legados
├─ DAOs/, DTOs/, servicos/   # camada legada preservada
├─ assets/           # style.css, app.js, fontes, ícones, imagens
├─ scripts/          # instalar_demo.php
├─ conexao/          # conexão PDO
├─ atualizar_banco.sql
└─ gestao.sql
```

## Fluxos principais

- **Candidatura:** `vaga.php?id=ID` → botão *Candidatar-se* (requer login de candidato).
- **Salvar vaga:** botão de coração na listagem ou no detalhe; persiste no banco e atualiza sem recarregar.
- **Publicar vaga:** login de empresa → *Publicar vaga* → preenchimento e envio.
- **Gerir candidaturas:** login de empresa → painel → alterar status na tabela de candidaturas.
- **Avaliar empresa:** login de candidato → página da empresa → formulário de avaliação.

## Segurança

- Senhas com `password_hash()` / `password_verify()`.
- Token CSRF em formulários e ações destrutivas.
- Consultas com prepared statements (PDO).
- Escapamento de saída com o helper `e()`.
- Sessão regenerada no login e no logout.

## Solução de problemas

- **Estilos antigos:** o CSS/JS usam `?v=<filemtime>`; faça um recarregamento forçado (`Ctrl+F5`).
- **Erro de conexão:** confira `conexao/conexao.php` e se o MySQL está ativo.
- **Tabelas ausentes:** importe `atualizar_banco.sql` no banco `gestao`.
- **Sem dados de exemplo:** execute `php scripts\instalar_demo.php`.
- **Acesso somente por `file://`:** não funciona; use sempre `http://localhost/IncluiTrabalho/`.

## Observações

- Os arquivos `index.html`, `vagas.html`, `vaga.html` e alguns HTMLs em `telas/` são apenas redirecionamentos para as versões PHP.
- A pasta `testes/` contém testes Selenium legados e não é necessária para executar o site.
