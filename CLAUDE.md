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
| `customers` | id, user_id, name, email, phone | email nullable na migration, required na validação |
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
- **SaleController**: Store com SaleRequest + pre-flight de estoque. Update com devolução de estoque antigo. Destroy devolve estoque.

### Observers
- **ProductObserver::created**: Cria Stock com `quantity = 0` vinculado ao user. ⚠️ Usa `Auth::id()` — problema se criado fora de contexto web.
- **ProductObserver::deleted**: **Vazio** — estoque fica orphan ao deletar produto.

### Policies
Todas seguem o padrão: `viewAny`/`view`/`create` = `true`; `update`/`delete` = `user.id === resource.user_id`.
StockPolicy não tem método `delete`.

## Padrões de Frontend
- **Layout**: Sidebar fixa vertical (`aside.blade.php`). Mobile: overlay.
- **Modais**: Inline com Alpine.js (`x-show`, `x-model`, `x-transition`). Sem modais de confirmação de delete.
- **Toast**: Session flash (`success`/`error`/`warning`), auto-dismiss em 5s.
- **Estilo**: Minimalista branco, tipografia heavy (`font-black`, tracking largo), bordas arredondadas grandes.

## Fluxo de Vendas (crítico entender)

**Create**: Modal Alpine → items adicionados → POST → transação DB:
1. Cria Sale com `total_amount` = soma de (qty * price) dos items
2. Para cada item: cria SaleItem com `price_at_sale`, verifica estoque, decrementa `stocks.quantity`
3. Se qualquer item falha em estoque → Exception → rollback automático

**Update**: Transação DB:
1. Devolve estoque dos itens antigos (increment)
2. Atualiza Sale header
3. Deleta items antigos
4. Cria novos items com decremento de estoque

**Delete**: Apenas `$sale->delete()` (cascade em items). ⚠️ **Estoque NÃO é devolvido.**

## Resolved Issues
1. ~~SaleController::destroy não devolve estoque~~ — ✅ devolve estoque antes de deletar
2. ~~SaleController::store/update usam Request genérico~~ — ✅ agora usam SaleRequest
3. ~~ProductObserver::created depende de Auth::id()~~ — ✅ usa $product->user_id
4. ~~User::phone_formatted referencia campo inexistente~~ — ✅ removido
5. ~~SaleItem casts duplicado/inválido~~ — ✅ limpo
6. ~~Customer.email nullable vs required~~ — ✅ validação usa nullable
9. ~~StockRequest message errada~~ — ✅ mensagem corrigida

## Remaining Known Issues
- Profile model existe mas nunca é usado
- Estoque pode ficar negativo em edge cases no update de vendas (se update for chamado concorrentemente)

## Model Accessors
- `User::total_products` → conta registros de stock (não produtos!)
- `User::total_items` → soma quantities de stock
- `User::total_customers` → conta customers
- `User::total_sales` → conta sales
- `SaleItem::total_item_amount` → quantity * price_at_sale
- `Customer::phone_formatted` → (DD) XXXXX-XXXX ou (DD) XXXX-XXXX
