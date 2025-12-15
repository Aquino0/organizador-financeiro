# Como rodar o projeto usando MAMP no Mac

Como você não tem o PHP instalado, usar o **MAMP** (Macintosh, Apache, MySQL, PHP) é a solução mais fácil. Ele cria um servidor local completo para você.

## Passo 1: Instalar o MAMP
1. Acesse o site oficial: [https://www.mamp.info/en/downloads/](https://www.mamp.info/en/downloads/)
2. Baixe a versão **MAMP & MAMP PRO** (você usará a versão gratuita, que vem junto).
3. Instale o programa normalmente (arraste para Applications ou siga o instalador pkg).

## Passo 2: Configurar a Pasta do Projeto
Para não precisar mover seus arquivos e perder a organização, vamos apontar o MAMP para a sua pasta na Mesa (Desktop).

1. Abra o **MAMP** (ícone cinza, evite o MAMP PRO se não quiser pagar).
2. Vá em **Preferences** (ícone de engrenagem) ou no menu `MAMP > Preferences`.
3. Clique na aba **Server** (ou **Web Server**).
4. Em **Document Root**, clique em **Choose...** (ou selecionar pasta).
5. Navegue e selecione a pasta do projeto:
   `/Users/aquino/Desktop/CRM FINANCEIRO`
6. Clique em **OK**.

## Passo 3: Iniciar o Servidor
1. Na tela principal do MAMP, clique no botão **Start Servers**.
2. As luzes de "Apache Server" e "MySQL Server" devem ficar verdes.
3. Uma página deve abrir automaticamente no navegador. Se não abrir, clique em **WebStart**.

## Passo 4: Configurar o Banco de Dados
1. Na página WebStart do MAMP, procure pelo link **phpMyAdmin** (geralmente no menu superior ou em Tools).
2. No phpMyAdmin:
   - Clique em **New** (lado esquerdo) para criar um banco.
   - Nome do banco: `ia_finance_crm`
   - Clique em **Create**.
3. Com o banco `ia_finance_crm` selecionado, clique na aba **Import** (superior).
4. Clique em **Choose File** e selecione o arquivo `database.sql` que está dentro da pasta do projeto (`/Users/aquino/Desktop/CRM FINANCEIRO/database.sql`).
5. Clique em **Go** (ou Executar) lá embaixo.

## Passo 5: Ajustar Conexão no Código
O MAMP geralmente usa:
- **Porta MySQL:** 8889 (padrão MAMP)
- **Usuário:** root
- **Senha:** root (diferente do padrão vazio)

Vou atualizar automaticamente o arquivo `src/db.php` para funcionar com o MAMP padrão para você no próximo passo.

## Passo 6: Acessar o Projeto
Agora acesse: [http://localhost:8888](http://localhost:8888)
---
**Nota:** Se o MAMP perguntar sobre portas, use as portas padrão do MAMP (Apache 8888, MySQL 8889).
