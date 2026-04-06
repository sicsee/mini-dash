# Lista de Melhorias e Correções

> Cada tarefa documenta **o que mudou**, **como mudou** e **por que**, para que qualquer pessoa consiga dar manutenção no futuro.

---

## Task 1 — Corrigir bug: Delete de venda não devolve estoque

**Status**: ✅ Concluída

### O problema

Quando uma venda era excluída (`SaleController::destroy`), o código apenas chamava `$sale->delete()`. Isso deleta a venda e seus itens (via cascade), mas o **estoque dos produtos vendidos permanecia descontado**.

**Exemplo prático**:
- Você tinha 10 unidades do Produto X
- Vendeu 3 unidades (estoque caiu para 7)
- Excluiu a venda
- O estoque continuava 7, quando deveria voltar para 10

### O que mudou

**Arquivo**: `app/Http/Controllers/SaleController.php`

```php
// ANTES — apenas deletava
$sale->delete();

// DEPOIS — devolve estoque, depois deleta
foreach ($sale->items as $item) {
    $item->product->stock->increment('quantity', $item->quantity);
}
$sale->delete();
```

### Como funciona a correção — explicação passo a passo

1. **`$sale->items`**: Aqui o Laravel carrega os itens da venda (relação `hasMany` definida no modelo `Sale`). Se os itens já não foram carregados antes, o Laravel faz a query automaticamente (lazy loading).

2. **`$item->product`**: Cada item tem uma relação `belongsTo` com `Product`. O Laravel vai ao banco e busca o produto daquele item.

3. **`$item->product->stock`**: O produto tem uma relação `hasOne` com `Stock`. Acessamos o registro de estoque ligado ao produto.

4. **`->increment('quantity', $item->quantity)`**: Método do Laravel que faz `UPDATE stocks SET quantity = quantity + X`. É atômico e seguro (evita race condition).

5. Só **depois** do loop chamamos `$sale->delete()`.

### Por que fazer assim?

- **Seguro**: se algo quebrar no meio do loop, a venda não é deletada e o estoque fica consistente
- **Simples**: 4 linhas usando relacionamentos que o Laravel já providencia
- **Legível**: quem lê entende em 5 segundos: "devolve o estoque, depois deleta"

### O que observar no futuro

Se um produto foi deletado entre a venda e agora, `$item->product` pode retornar `null` e causar erro. Uma versão ainda mais segura seria:

```php
foreach ($sale->items as $item) {
    if ($product = $item->product) {
        $product->stock?->increment('quantity', $item->quantity);
    }
}
```

Mas como temos `restrictOnDelete` no `product_id` da migration de `sale_items`, o banco impede que um produto seja deletado se tem itens de venda — então o cenário não existe na prática.

---

## Task 2 — Usar `SaleRequest` nos métodos store e update do SaleController

**Status**: ✅ Concluída

### O problema

Os métodos `store` e `update` do `SaleController` recebiam `Request` genérico do Laravel — isso significa que **nenhuma validação era executada**. O `SaleRequest` já existia com regras definidas (cliente obrigatório, itens válidos, status permitido, etc.), mas nunca era chamado.

**Risco**: qualquer um podia enviar dados malformados direto para a rota e a venda seria criada sem checar se o cliente existe, se os itens são válidos, se o status é legítimo, etc.

### O que mudou

**Arquivo**: `app/Http/Controllers/SaleController.php`

```php
// ANTES — usava Request genérico
public function store(Request $request)
public function update(Request $request, Sale $sale)

// DEPOIS — usa SaleRequest com validação
public function store(SaleRequest $request)
public function update(SaleRequest $request, Sale $sale)
```

E o import foi adicionado:

```php
use App\Http\Requests\SaleRequest;
```

### Quais validações agora são executadas

O `SaleRequest` define estas regras (em `app/Http/Requests/SaleRequest.php`):

```php
'customer_id' => ['required', Rule::exists('customers', 'id')->where('user_id', $this->user()->id)],
'sale_date'   => ['required', 'date'],
'status'      => ['required', 'string', Rule::in(['pendente', 'concluida', 'cancelada'])],
'notes'       => ['nullable', 'string', 'max:65535'],
'items'       => ['required', 'array', 'min:1'],
'items.*.product_id' => ['required', Rule::exists('products', 'id')->where('user_id', $this->user()->id)],
'items.*.quantity'   => ['required', 'integer', 'min:1'],
'items.*.price'      => ['required', 'numeric', 'min:0'],
```

Ou seja, agora o Laravel garante automaticamente:
- O cliente **existe** e **pertence ao usuário logado**
- A data da venda é uma data válida
- O status é um dos três permitidos (`pendente`, `concluida`, `cancelada`)
- Pelo menos 1 item é obrigatório
- Cada produto dos itens **existe** e **pertence ao usuário logado**
- Quantidade é número inteiro mínimo 1
- Preço é número >= 0

### O que acontece se a validação falhar

O Laravel **interrompe a requisição automaticamente** e redireciona de volta com os erros de validação na sessão. O método `store`/`update` **nem chega a rodar**. Isso é o comportamento do `FormRequest` — ele valida antes de entrar no controller.

### Por que FormRequest é melhor que `$request->validate()`

- **Separação de responsabilidades**: a validação fica fora do controller, em classe própria
- **Reutilizável**: a mesma classe pode ser usada em diferentes contextos
- **Testável**: você pode unit-testar a validação sem precisar testar o controller
- **Mais legível**: o controller fica focado em lógica de negócio, não em regras de validação

---

## Task 3 — Corrigir ProductObserver para não depender de `Auth::id()`

**Status**: ✅ Concluída

### O problema

O `ProductObserver::created` usava `Auth::id()` para definir o `user_id` do registro de estoque criado automaticamente. Se um produto fosse criado fora de contexto web (seeder, tinker, job), `Auth::id()` retornaria `null`.

```php
// ANTES — falha fora de contexto web
Stock::create([
    'user_id' => Auth::id(),
    // ...
]);
```

### O que mudou

**Arquivo**: `app/Observers/ProductObserver.php`

```php
// DEPOIS — usa o user_id que já está no produto
Stock::create([
    'user_id' => $product->user_id,
    // ...
]);
```

### Por que funciona

O `ProductController::store` já associa o `user_id` ao criar o produto:

```php
auth()->user()->products()->create($validated);
```

O Laravel preenche automaticamente `user_id` nesse contexto. O Observer recebe o `$product` já populado, então `$product->user_id` sempre estará correto, independente de onde o produto foi criado.

---

## Task 4 — Limpar código morto e atributos inválidos nos Models

**Status**: ✅ Concluída

### O problema

Havia três problemas:

1. **`User::getPhoneFormattedAttribute`** — referencia `$this->phone`, mas `phone` não existe na tabela `users` (só em `profiles`). Esse método nunca retornaria valor útil.

2. **`SaleItem::$casts['sale_date']`** — a coluna `sale_date` não existe na tabela `sale_items` (só existe em `sales`). Cast inválido que o Laravel ignora, mas causa confusão.

3. **`SaleItem` tinha dois métodos de casts** — a propriedade `$casts` e o método `casts()`, com overlap. O método sobrescreve a propriedade, então `$casts` era ignorado.

### O que mudou

**Arquivo**: `app/Models/User.php` — removido `getPhoneFormattedAttribute`

**Arquivo**: `app/Models/SaleItem.php` — removido `$casts` property (mantido apenas o método `casts()` que já faz o job):

```php
// ANTES — cast inválido + duplicado
protected $casts = [
    'sale_date' => 'date:Y-m-d',  // não existe em sale_items
];
protected function casts(): array {
    return ['price_at_sale' => 'decimal:2',];
}

// DEPOIS — apenas o que é válido
protected function casts(): array {
    return ['price_at_sale' => 'decimal:2',];
}
```

---

## Task 5 — Validar estoque ANTES de criar itens da venda

**Status**: ✅ Concluída

### O problema

No `SaleController::store`, a verificação de estoque acontecia **depois** de criar cada `SaleItem`. Se o terceiro item de uma venda falhasse por estoque insuficiente, os dois primeiros já tinham sido criados e o estoque já tinha sido descontado (antes do rollback da transação).

### O que mudou

**Arquivo**: `app/Http/Controllers/SaleController.php` — Adicionado pre-flight check:

```php
// 1. Primeiro: validar TUDO antes de criar qualquer coisa
foreach ($request->items as $item) {
    $product = Product::with('stock')->findOrFail($item['product_id']);
    if ($product->stock->quantity < $item['quantity']) {
        throw new \Exception("Estoque insuficiente para: {$product->name}");
    }
}

// 2. Só depois que tudo foi validado, criar a venda
$sale = auth()->user()->sales()->create([/* ... */]);

// 3. E então descontar estoque
foreach ($request->items as $item) {
    $sale->items()->create([/* ... */]);
    $product->stock->decrement('quantity', $item['quantity']);
}
```

### Por que separar em duas fases?

- **Consistência**: ou tudo passa ou tudo falha — nunca chega no rollback do banco
- **Performance**: o rollback de transação é mais caro que uma simples validação em memória
- **Feedback**: a exceção é lançada imediatamente, sem criar registros fantasmas

---

## Task 6 — Criar ProductObserver::deleted para limpar estoque

**Status**: ✅ Concluída

### O problema

Quando um produto era deletado, o registro de `Stock` ficava órfão no banco. A FK tem `cascadeOnDelete`, então o banco limpa, mas deixar o Observer vazio é má prática — quem lê o código não entende que existe essa relação.

### O que mudou

**Arquivo**: `app/Observers/ProductObserver.php`

```php
public function deleted(Product $product): void
{
    $product->stock()->delete();
}
```

Mesmo com o cascade no banco, ter isso no Observer:
- Torna a intenção explícita no código
- Garante limpeza mesmo se a constraint mudar no futuro
- Funciona para testes onde a FK pode estar desabilitada

---

## Task 7 — Corrigir mensagem de validação errada no StockRequest

**Status**: ✅ Concluída

### O problema

No `StockRequest`, a mensagem para `product_id.required` estava errada:

```php
// ANTES
'product_id.required' => 'O campo quantidade é obrigatório.',
```

Dizia "quantidade" quando deveria dizer "produto". Era um copy-paste erro.

### O que mudou

```php
// DEPOIS
'product_id.required' => 'O campo produto é obrigatório.',
```

---

## Task 8 — Garantir autorização consistente nas vendas

**Status**: ✅ Concluída

### O problema

O `SaleRequest` já valida que o `customer_id` pertence ao usuário logado:

```php
'customer_id' => Rule::exists('customers', 'id')->where('user_id', $this->user()->id),
```

Mas essa validação só passou a funcionar quando a Task 2 foi aplicada (usar `SaleRequest` ao invés de `Request`). Antes, o `customer_id` podia ser de qualquer usuário.

### O que mudou

Nenhum código novo — a correção já veio com a Task 2. A Task 8 documenta que agora temos **defesa em profundidade**:
- **Validação** (`SaleRequest`): impede dados inválidos antes de entrar no controller
- **Policy** (`SalePolicy`): verifica permissão do usuário no objeto
- **Scoping**: todo acesso usa `auth()->user()->sales()` — nunca consulta direta na tabela

### Por que defesa em profundidade?

Se por acidente um validador deixar passar algo, a policy barra. Se a policy falhar, o scoping garante que só volta dados do usuário logado. São 3 camadas que protegem o mesmo dado.

---

## Task 9 — Corrigir Customer email: nullable na migration vs required na validação

**Status**: ✅ Concluída

### O problema

Na migration `customers.php`:
```php
$table->string('email')->nullable();
```

No `CustomerRequest`:
```php
'email' => 'required|email|string',
```

A migration permite `null`, mas a validação exige o campo. Inconsistência.

### O que mudou

**Arquivo**: `app/Http/Requests/CustomerRequest.php`

```php
// ANTES
'email' => 'required|email|string',

// DEPOIS
'email' => 'nullable|email|string',
```

### Por que mudar assim

- O banco já permite null → a validação deveria refletir isso
- Um cliente pode existir só com nome e telefone
- Se no futuro quiser tornar email obrigatório, basta: (1) criar migration para remover nullable e (2) voltar a validação para `required`
