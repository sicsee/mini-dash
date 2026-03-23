# 🚀 Mini Dash: Gestão Inteligente de Ativos e Vendas

## 1. Visão Geral do Projeto

O **Mini Dash** é um ecossistema ERP minimalista de alta performance, projetado para oferecer controle total sobre o fluxo comercial de pequenas operações. O foco central do projeto é a **clareza operacional**, transformando dados complexos de estoque, clientes e vendas em uma interface limpa, intuitiva e totalmente responsiva (Mobile-First).

Este projeto foi desenvolvido como um portfólio de alto nível, demonstrando domínio avançado em **Laravel 12**, arquitetura de sistemas escalável e design de interface focado na estética contemporânea.

### 💎 Diferenciais de UX

- **Design Cinematográfico:** Landing Page com efeitos de Glassmorphism e tipografia refinada.
- **Interface Híbrida:** Tabelas densas para desktop que se transformam em Cards táteis no mobile.
- **Feedback Premium:** Sistema de notificações (Toasts) com auto-hide e animações suaves via Alpine.js.

---

## 2. Funcionalidades Principais

- **Dashboard Executivo:** Visão geral do faturamento total (vendas concluídas) e volume de ativos.
- **Gestão de Estoque (Inventory):** Controle preciso de quantidades com alertas visuais pulsantes para itens abaixo do nível crítico.
- **Catálogo de Produtos:** CRUD completo de itens com organização por proprietário.
- **CRM Integrado:** Gestão de base de clientes com identificação visual por avatares minimalistas.
- **Fluxo de Vendas:** Registro de transações financeiras integradas ao sistema.
- **Segurança:** Proteção total de rotas e isolamento de dados por usuário (Multi-tenancy básico).

---

## 3. Stack Tecnológica

O projeto utiliza as ferramentas mais modernas do ecossistema PHP:

- **Laravel 12:** Framework core para lógica de negócio e segurança.
- **Alpine.js:** Reatividade leve para modais, menus laterais (Drawers) e interações de UI.
- **Tailwind CSS:** Estilização customizada com foco em design minimalista ("Luxury Dark/Light mode").
- **Eloquent ORM:** Manipulação otimizada de banco de dados com Query Scopes para métricas financeiras.
- **Lucide Icons:** Conjunto de ícones vetoriais para uma interface limpa e intuitiva.
- **Vite:** Build de ativos ultra-rápido para performance máxima.

---

## 4. Como Rodar o Projeto Localmente

#### 4.1. Pré-requisitos

- PHP 8.2 ou superior.
- Composer.
- Node.js & NPM.
- Servidor MySQL.

#### 4.2. Instalação

1.  **Clone o repositório:**

    ```bash
    git clone [https://github.com/sicsee/mini-dash.git](https://github.com/sicsee/mini-dash.git)
    cd mini-dash
    ```

2.  **Dependências e Configuração:**

    ```bash
    composer install
    npm install
    cp .env.example .env
    php artisan key:generate
    ```

3.  **Banco de Dados:**
    _Crie um banco no MySQL e configure as credenciais no seu `.env`._

    ```bash
    php artisan migrate
    ```

4.  **Execução (Ambiente de Desenvolvimento):**
    Abra dois terminais:

    ```bash
    # Terminal 1
    php artisan serve

    # Terminal 2
    npm run dev
    ```

---

## 5. Desenvolvedor

Apresentação do responsável pela arquitetura e design.

| Detalhe           | Informação                                                                 |
| ----------------- | -------------------------------------------------------------------------- |
| **Nome Completo** | Nicolas David Da Silva Godinho                                             |
| **LinkedIn**      | [https://www.linkedin.com/in/sicsee/](https://www.linkedin.com/in/sicsee/) |
| **GitHub**        | [https://github.com/sicsee/](https://github.com/sicsee/)                   |

---

## 6. Aprendizados e Desafios

- **Refatoração Responsiva:** Superação do desafio de exibir tabelas de dados complexas em telas de smartphones sem perda de usabilidade.
- **Global State com Alpine.js:** Gerenciamento de estados de UI (como menus laterais e modais) sem a necessidade de frameworks pesados de JS.
- **Query Optimization:** Uso de somatórios condicionais no banco de dados para garantir que o dashboard reflita apenas dados financeiros reais (vendas concluídas).
- **Framework:** Primeiro projeto desenvolvido com Laravel.
