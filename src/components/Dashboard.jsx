// src/components/Dashboard.jsx
import { useEffect, useState } from "react";
import { supabase } from "../supabaseClient";

export default function Dashboard() {
  const [produtos, setProdutos] = useState([]);

  useEffect(() => {
    fetchProdutos();
  }, []);

  async function fetchProdutos() {
    const { data, error } = await supabase.from("produtos").select("*");
    if (error) console.error(error);
    else setProdutos(data);
  }

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Administração de Produtos</h1>
      <table className="min-w-full bg-white shadow rounded">
        <thead>
          <tr>
            <th className="text-left p-2">Nome</th>
            <th className="text-left p-2">Preço</th>
            <th className="text-left p-2">Quantidade</th>
            <th className="text-left p-2">Tag</th>
          </tr>
        </thead>
        <tbody>
          {produtos.map((prod) => (
            <tr key={prod.id} className="border-t">
              <td className="p-2">{prod.nome}</td>
              <td className="p-2">R$ {prod.preco.toFixed(2)}</td>
              <td className="p-2">{prod.quantidade}</td>
              <td className="p-2">{prod.tag}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
