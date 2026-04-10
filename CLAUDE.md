# Mini Dash — Project Reference

## Tech Stack
- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Blade + Tailwind CSS v4 + Alpine.js + Livewire 4
- **Build**: Vite 7
- **Database**: SQLite
- **Testing**: Pest 4
- **Locale**: pt-BR | **Timezone**: America/Sao_Paulo

## Architecture

### Multi-tenant por usuário
Cada usuário é dono de seus próprios dados. Tudo é filtrado por `user_id`. Não há perfis/admin — todos usuarios autenticados têm acesso igual.

### Database Schema
```
users (1) → (N) customers
         → (N) products
         → (N) sales   → (N) sale_items ← (N) products
         → (N) stocks  ← (1) products
         → (1) profiles (não utilizado)
```

| Tabela | Colunas principais | Notas |
|---|---|---|
| `users` | id, name, email, password | Auth via sessão |
| `profiles` | id, user_id, first_name, last_name, phone, avatar_url | **Não utilizado em controllers/views** |
| `customers` | id, user_id, name, email, phone | email: nullable |
| `products` | id, user_id, name, price | Observer cria Stock automático |
| `stocks` | id, user_id, product_id, quantity | Unique [user_id, product_id] |
| `sales` | id, user_id, customer_id, sale_date, total_amount, status, notes | status: pendente/concluida/cancelada |
| `sale_items` | id, sale_id, product_id, quantity, price_at_sale | Snapshot do preço na venda |

### Rotas

**Públicas**: `/` (home), `/register` (form+POST), `/login` (form+POST)
**Protegidas** (auth): `/logout`, `/dashboard`, `/dashboard/products`, `/dashboard/stocks`, `/dashboard/customers`, `/dashboard/sales`

### Controllers
- **RegisterController**: Cria user + loga auto → redirect dashboard
- **LoginController**: Auth com email/password, logout invalida sessão
- **SiteController**: Home page e Dashboard (métricas: receita, pedidos, ticket médio, alerta estoque baixo)
- **ProductController**: CRUD simples
- **StockController**: index/store/update (sem destroy). Store faz increment se já existe.
- **CustomerController**: CRUD simples
- **SaleController**: Delegata lógica de negócio para `SaleController`; responsável apenas por validação (via `SaleRequest`), tratamento de exceções e respostas HTTP

### Services
- **SaleService**: Encapsula toda a lógica de negócio de vendas (create, update, delete) com transações, validação de estoque e operações de banco de dados

### Observers
- **ProductObserver::created**: Cria Stock com `quantity = 0` usando `$product->user_id`
- **ProductObserver::deleted**: Deleta o Stock do produto

### Policies
Todas seguem o padrão: `viewAny`/`view`/`create` = `true`; `update`/`delete` = `user.id === resource.user_id`. StockPolicy não tem método `delete`.

## Padrões de Frontend
- **Layout**: Sidebar fixa vertical (`aside.blade.php`). Mobile: overlay.
- **Modais**: Inline com Alpine.js (`x-show`, `x-model`, `x-transition`).
- **Toast**: Session flash (`success`/`error`/`warning`), auto-dismiss em 5s.
- **Estilo**: Minimalista branco, tipografia heavy (`font-black`, tracking largo), bordas grandes.
- **Confirmação de delete**: `confirm()` em ambos delete (desktop e mobile).

## Fluxo de Vendas (crítico entender)

**Create**: Modal Alpine → items → POST → `SaleRequest` valida → `DB::transaction`:
1. **Pre-flight**: valida estoque de todos os itens ANTES de escrever
2. Cria Sale com `total_amount` = soma(qty × price) dos items
3. Para cada item: cria SaleItem com `price_at_sale`, decrementa estoque
4. Se falhar → Exception → rollback automático

**Update**: `DB::transaction`:
1. **Pre-flight**: valida estoque (somando o que será devolvido)
2. Devolve estoque dos itens antigos
3. Atualiza Sale header
4. Deleta items antigos, cria novos com desconto de estoque

**Delete**: Devolve estoque dos itens → deleta venda (cascade)

## Resolved Issues
1. ~~Destroy não devolvia estoque~~ — ✅ increment antes de delete
2. ~~Store/update sem SaleRequest~~ — ✅ validação ativa (ownership de cliente/produtos)
3. ~~ProductObserver com Auth::id()~~ — ✅ usa $product->user_id
4. ~~User::phone_formatted inválido~~ — ✅ removido
5. ~~SaleItem casts duplicado~~ — ✅ limpo
6. ~~Customer.email mismatch~~ — ✅ nullable
7. ~~StockRequest message incorreta~~ — ✅ corrigida
8. ~~Estoque verificado APÓS criar item~~ — ✅ pre-flight ANTES
9. ~~N+1 com $sale->load('items')~~ — ✅ toJson()
10. ~~hasEnoughStock() indefinido~~ — ✅ no x-data
11. ~~Sem confirmação delete desktop~~ — ✅ confirm()

## Known Issues (não críticos)
- Profile model nunca usado (sem impacto funcional)
- Estoque negativo em concorrência extrema (improvável — sistema single-user)

## Model Accessors
- `User::total_products` → stock().count()
- `User::total_items` → stock().sum('quantity')
- `User::total_customers` → customers().count()
- `User::total_sales` → sales().count()
- `SaleItem::total_item_amount` → quantity × price_at_sale
- `Customer::phone_formatted` → (DD) XXXXX-XXXX ou (DD) XXXX-XXXX

## Documentation
- **TASKS.md**: Log detalhado de cada correção
- **CLAUDE.md**: Referência de arquitetura (este arquivo)
