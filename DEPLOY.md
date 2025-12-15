# Guia de Instalação e Deploy - IAFinanceCRM

## Requisitos
* PHP 7.4 ou superior
* MySQL 5.7 ou superior (ou MariaDB)
* Servidor Web (Apache, Nginx)

## Passo 1: Configuração do Banco de Dados
1. Acesse seu gerenciador de banco de dados (phpMyAdmin, Workbench, ou terminal).
2. Crie um novo banco de dados (ex: `ia_finance_crm`).
3. Importe o arquivo `database.sql` localizado na raiz do projeto.
   - Este script criará as tabelas `users`, `receitas`, `despesas` e criará um usuário admin padrão.

**Usuário Admin Padrão:**
* Email: `admin@empresa.com`
* Senha: `admin123`

## Passo 2: Configuração da Conexão
1. Abra o arquivo `src/db.php`.
2. Edite as variáveis de conexão conforme seu ambiente:
   ```php
   $host = 'localhost';
   $db   = 'ia_finance_crm'; // Nome do banco criado
   $user = 'seu_usuario';    // Usuário do banco
   $pass = 'sua_senha';      // Senha do banco
   ```

## Passo 3: Deploy na Hospedagem (Ex: cPanel, Hostinger)
1. Faça upload de todos os arquivos para a pasta pública (ex: `public_html`).
2. Certifique-se de que a estrutura de pastas (`api/`, `src/`, `assets/`) foi mantida.
3. Importe o `database.sql` no banco de dados da hospedagem.
4. Ajuste o `src/db.php` com as credenciais da hospedagem.

## Passo 4: Ativando PWA (App Mobile)
O sistema já está configurado como PWA. Para instalar no celular:
1. Acesse o site pelo Chrome (Android) ou Safari (iOS).
2. No Android: Toque em "Adicionar à tela inicial" no banner que aparecer ou no menu do navegador.
3. No iOS: Toque no botão "Compartilhar" e depois em "Adicionar à Tela de Início".

## Testes
1. Tente logar com o admin padrão.
2. Cadastre uma nova receita e uma nova despesa.
3. Verifique se o Dashboard atualiza os valores e o gráfico.
4. Teste a responsividade em um celular (devtools ou real).

## Estrutura de Pastas
* `/api` - Endpoints JSON (Backend)
* `/src` - Lógica de conexão, auth e layout
* `/assets` - Imagens e ícones
* `index.php` - Tela de Login
* `dashboard.php` - Painel Principal
