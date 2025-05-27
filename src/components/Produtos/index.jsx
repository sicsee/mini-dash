import { useEffect, useState } from "react";
import { supabase } from "@/supabase";
import { toast } from "sonner";

export default function ProductList() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState("");
  const [form, setForm] = useState({ name: "", price: "" });
  const [editingId, setEditingId] = useState(null);
  const [user, setUser] = useState(null);

  useEffect(() => {
    const getUser = async () => {
      const {
        data: { user },
      } = await supabase.auth.getUser();
      setUser(user);
    };
    getUser();
  }, []);

  useEffect(() => {
    if (user) fetchProducts();
  }, [user]);

  const fetchProducts = async () => {
    setLoading(true);
    const { data, error } = await supabase
      .from("products")
      .select("id, name, price")
      .order("created_at", { ascending: false });

    if (error) {
      toast.error("Erro ao buscar produtos: " + error.message);
    } else {
      setProducts(data);
    }
    setLoading(false);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm((f) => ({ ...f, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!form.name.trim()) {
      toast.error("Nome é obrigatório");
      return;
    }
    const priceNumber = parseFloat(form.price);
    if (isNaN(priceNumber) || priceNumber < 0) {
      toast.error("Preço inválido");
      return;
    }
    if (!user) {
      toast.error("Usuário não autenticado");
      return;
    }

    if (editingId) {
      // Update
      const { error } = await supabase
        .from("products")
        .update({ name: form.name.trim(), price: priceNumber })
        .eq("id", editingId);
      if (error) {
        toast.error("Erro ao atualizar produto: " + error.message);
      } else {
        toast.success("Produto atualizado");
        setEditingId(null);
        setForm({ name: "", price: "" });
        fetchProducts();
      }
    } else {
      // Create
      const { error } = await supabase
        .from("products")
        .insert([
          { name: form.name.trim(), price: priceNumber, user_id: user.id },
        ]);
      if (error) {
        toast.error("Erro ao criar produto: " + error.message);
      } else {
        toast.success("Produto criado");
        setForm({ name: "", price: "" });
        fetchProducts();
      }
    }
  };

  const handleEdit = (product) => {
    setEditingId(product.id);
    setForm({ name: product.name, price: product.price.toString() });
  };

  const handleDelete = async (id) => {
    if (!confirm("Tem certeza que quer excluir?")) return;

    const { error } = await supabase.from("products").delete().eq("id", id);
    if (error) {
      toast.error("Erro ao excluir: " + error.message);
    } else {
      toast.success("Produto excluído");
      if (editingId === id) {
        setEditingId(null);
        setForm({ name: "", price: "" });
      }
      fetchProducts();
    }
  };

  // Filtrar produtos pelo input
  const filteredProducts = products.filter((p) =>
    p.name.toLowerCase().includes(filter.toLowerCase())
  );

  return (
    <div className="p-6 max-w-3xl mx-auto">
      {/* Card Total */}
      <div className="bg-blue-100 text-blue-900 px-4 py-3 rounded mb-6 shadow">
        <h2 className="text-xl font-semibold">
          Total de Produtos: {products.length}
        </h2>
      </div>

      {/* Filtro */}
      <input
        type="text"
        placeholder="Buscar por nome..."
        value={filter}
        onChange={(e) => setFilter(e.target.value)}
        className="mb-6 w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />

      {/* Formulário */}
      <form
        onSubmit={handleSubmit}
        className="mb-6 space-y-4 bg-white p-4 rounded shadow"
      >
        <div>
          <label htmlFor="name" className="block font-medium mb-1">
            Nome
          </label>
          <input
            id="name"
            name="name"
            value={form.name}
            onChange={handleChange}
            className="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <div>
          <label htmlFor="price" className="block font-medium mb-1">
            Preço (R$)
          </label>
          <input
            id="price"
            name="price"
            type="number"
            step="0.01"
            min="0"
            value={form.price}
            onChange={handleChange}
            className="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>
        <button
          type="submit"
          className="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700 transition"
        >
          {editingId ? "Atualizar Produto" : "Criar Produto"}
        </button>
        {editingId && (
          <button
            type="button"
            onClick={() => {
              setEditingId(null);
              setForm({ name: "", price: "" });
            }}
            className="ml-4 bg-gray-300 text-gray-700 rounded px-4 py-2 hover:bg-gray-400 transition"
          >
            Cancelar
          </button>
        )}
      </form>

      {/* Lista */}
      {loading ? (
        <p>Carregando...</p>
      ) : filteredProducts.length === 0 ? (
        <p>Nenhum produto encontrado.</p>
      ) : (
        <ul className="space-y-4">
          {filteredProducts.map((product) => (
            <li
              key={product.id}
              className="border border-gray-300 p-4 rounded shadow flex justify-between items-center"
            >
              <div>
                <h3 className="text-lg font-semibold">{product.name}</h3>
                <p className="text-sm text-gray-600">
                  Preço: R$ {product.price}
                </p>
              </div>
              <div className="space-x-2">
                <button
                  onClick={() => handleEdit(product)}
                  className="text-blue-600 hover:underline"
                >
                  Editar
                </button>
                <button
                  onClick={() => handleDelete(product.id)}
                  className="text-red-600 hover:underline"
                >
                  Excluir
                </button>
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
