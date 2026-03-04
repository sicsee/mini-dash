## Mini Dash – Dashboard de Produtos e Estoque

### 1. Visão Geral

O **Mini Dash** é um pequeno painel administrativo para gestão de **produtos** e **estoque**, desenvolvido com **Laravel 12**.  
Ele oferece autenticação de usuários, um **dashboard** protegido e telas para cadastrar, listar, atualizar e remover produtos, além do controle de estoque.

Este projeto foi criado como exercício prático de desenvolvimento **full stack** com foco em:

- **Autenticação** e proteção de rotas.
- **CRUD** de recursos (produtos e estoque).
- Organização em **controllers**, **views Blade** e **rotas** seguindo o padrão do Laravel.

---

### 2. Funcionalidades Principais

- **Página inicial pública** (`/`): apresentação simples do sistema.
- **Cadastro de usuário** (`/register`): criação de conta.
- **Login** (`/login`): autenticação de usuários.
- **Logout** (`/logout`): encerramento de sessão.
- **Dashboard protegido** (`/dashboard`): acessível apenas para usuários autenticados.
- **Gestão de produtos** (`/dashboard/products`):
  - Listagem de produtos do usuário autenticado.
  - Criação, atualização e exclusão de produtos.
- **Gestão de estoque** (`/dashboard/stocks`):
  - Listagem e manutenção de registros de estoque vinculados aos produtos.

---

### 3. Tecnologias Utilizadas

- **PHP 8.2+** com **Laravel 12**.
- **Blade** para renderização de views.
- **Tailwind CSS** para estilização da interface.
- **Eloquent ORM** para interação com o banco de dados relacional (ex.: MySQL).
- **NPM / Vite** para build e assets front-end.

---

### 4. Como Rodar o Projeto Localmente

#### 4.1. Pré-requisitos

- PHP 8.2 ou superior.
- Composer.
- Node.js e NPM.
- Servidor de banco de dados (ex.: MySQL).

#### 4.2. Passos básicos

No diretório do projeto:

1. Instale as dependências PHP:

   ```bash
   composer install
   ```

2. Copie o arquivo de ambiente e configure o banco:

   ```bash
   cp .env.example .env
   # Edite .env e configure DB_*, APP_URL, etc.
   ```

3. Gere a chave da aplicação:

   ```bash
   php artisan key:generate
   ```

4. Rode as migrations:

   ```bash
   php artisan migrate
   ```

5. Instale as dependências front-end:

   ```bash
   npm install
   ```

6. Suba o servidor de desenvolvimento e o build front-end (em terminais separados):

   ```bash
   php artisan serve
   npm run dev
   ```

Ou, se preferir usar o script de desenvolvimento definido no `composer.json`:

```bash
composer run dev
```

---

### 5. Rotas Principais

- `GET /` → página inicial.
- `GET /register` / `POST /register` → cadastro de usuário.
- `GET /login` / `POST /login` → login.
- `POST /logout` → logout (apenas autenticado).
- `GET /dashboard` → dashboard com visão geral dos produtos do usuário.
- `resource /dashboard/products` → CRUD de produtos (exceto create/show/edit por view dedicada).
- `resource /dashboard/stocks` → CRUD de estoques (exceto create/show/edit por view dedicada).

---

### 6. Desenvolvedor

| Detalhe           | Informação                                                                 |
| ----------------- | -------------------------------------------------------------------------- |
| **Nome Completo** | Nicolas David Da Silva Godinho                                             |
| **LinkedIn**      | [https://www.linkedin.com/in/sicsee/](https://www.linkedin.com/in/sicsee/) |
| **GitHub**        | [https://github.com/sicsee/](https://github.com/sicsee/)                   |

---

### 7. Notas Finais

O projeto foi desenvolvido manualmente com foco em boas práticas do ecossistema Laravel, organização de código e uma base sólida para evolução futura do painel (novos módulos, relatórios, etc.).

