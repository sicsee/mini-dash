import React from "react";
import { useEffect, useState, useMemo, useCallback } from "react";
import { supabase } from "@/supabase";
import { toast } from "sonner";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
  DialogClose,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Archive,
  ChevronsDown,
  ChevronLeft,
  ChevronRight,
  ChevronsUp,
  Search as SearchIcon,
  PackageOpen,
} from "lucide-react";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { debounce } from "lodash";

function LoadingSpinner() {
  return (
    <div className="flex justify-center py-6">
      <svg
        className="animate-spin h-8 w-8 text-primary"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        aria-label="Carregando"
      >
        <circle
          className="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          strokeWidth="4"
        ></circle>
        <path
          className="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        ></path>
      </svg>
    </div>
  );
}

const SortIndicator = React.memo(({ direction }) => {
  if (!direction) return null;
  const Icon = direction === "asc" ? ChevronsUp : ChevronsDown;
  return (
    <span className="inline-flex items-center gap-1">
      <Icon className="w-5 h-5" />
    </span>
  );
});

const FilterInput = React.memo(
  ({ icon: Icon, value, onChange, placeholder }) => {
    return (
      <div className="relative w-full max-w-sm">
        <Input
          type="text"
          placeholder={placeholder}
          value={value}
          onChange={onChange}
          className="pl-10"
        />
        <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-muted-foreground">
          <Icon className="h-5 w-5" />
        </div>
      </div>
    );
  }
);

export default function StockList() {
  const [stockItems, setStockItems] = useState([]);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState("");
  const [form, setForm] = useState({ product_id: "", quantity: "" });
  const [editingId, setEditingId] = useState(null);
  const [user, setUser] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [availableProducts, setAvailableProducts] = useState([]);

  // Paginação
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 5;

  // Ordenação
  const [sortConfig, setSortConfig] = useState({
    key: "updated_at",
    direction: "desc",
  });

  const fetchStockItems = useCallback(async () => {
    setLoading(true);

    const { data, error } = await supabase
      .from("stock")
      .select("*, products(name)")
      .order("updated_at", { ascending: false });

    if (error) {
      toast.error("Erro ao buscar itens de estoque: " + error.message);
    } else {
      setStockItems(data);
    }
    setLoading(false);
  }, []);

  const totalQuantityInStock = useMemo(() => {
    return stockItems.reduce((sum, item) => sum + item.quantity, 0);
  }, [stockItems]);

  const fetchAvailableProducts = useCallback(async () => {
    const { data, error } = await supabase
      .from("products")
      .select("id, name")
      .eq("user_id", user.id);
    if (error) {
      toast.error("Erro ao buscar produtos disponíveis: " + error.message);
    } else {
      setAvailableProducts(data);
    }
  }, [user]);

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
    if (user) {
      fetchStockItems();
      fetchAvailableProducts();
    }
  }, [user, fetchStockItems, fetchAvailableProducts]);

  const filteredAndSortedStockItems = useMemo(() => {
    let currentItems = [...stockItems];

    if (filter) {
      currentItems = currentItems.filter((item) =>
        item.products?.name.toLowerCase().includes(filter.toLowerCase())
      );
    }

    if (sortConfig.key) {
      currentItems.sort((a, b) => {
        let aValue;
        let bValue;

        if (sortConfig.key === "product_name") {
          aValue = a.products?.name || "";
          bValue = b.products?.name || "";
        } else {
          aValue = a[sortConfig.key];
          bValue = b[sortConfig.key];
        }

        if (typeof aValue === "string" && typeof bValue === "string") {
          aValue = aValue.toLowerCase();
          bValue = bValue.toLowerCase();
        }

        if (aValue < bValue) {
          return sortConfig.direction === "asc" ? -1 : 1;
        }
        if (aValue > bValue) {
          return sortConfig.direction === "asc" ? 1 : -1;
        }
        return 0;
      });
    }

    return currentItems;
  }, [stockItems, filter, sortConfig]);

  const paginatedStockItems = useMemo(() => {
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    return filteredAndSortedStockItems.slice(startIndex, endIndex);
  }, [currentPage, itemsPerPage, filteredAndSortedStockItems]);

  const totalPages = Math.ceil(
    filteredAndSortedStockItems.length / itemsPerPage
  );

  const openCreateModal = useCallback(() => {
    setEditingId(null);
    setForm({ product_id: "", quantity: "" });
    setIsModalOpen(true);
  }, []);

  const openEditModal = useCallback((item) => {
    setEditingId(item.id);
    setForm({ product_id: item.product_id, quantity: item.quantity });
    setIsModalOpen(true);
  }, []);

  const handleChange = useCallback((e) => {
    const { name, value } = e.target;
    setForm((f) => ({ ...f, [name]: value }));
  }, []);

  const handleSelectChange = useCallback((value) => {
    setForm((f) => ({ ...f, product_id: value }));
  }, []);

  const handleSubmit = useCallback(
    async (e) => {
      e.preventDefault();
      const quantityNumber = parseInt(form.quantity, 10);

      if (!form.product_id && !editingId) {
        toast.error("Produto é obrigatório para um novo item de estoque.");
        return;
      }
      if (isNaN(quantityNumber) || quantityNumber < 0) {
        toast.error("Quantidade inválida.");
        return;
      }
      if (!user) {
        toast.error("Usuário não autenticado.");
        return;
      }

      if (editingId) {
        const { error } = await supabase
          .from("stock")
          .update({
            quantity: quantityNumber,
            updated_at: new Date().toISOString(),
          })
          .eq("id", editingId);
        if (error) {
          toast.error("Erro ao atualizar estoque: " + error.message);
        } else {
          toast.success("Estoque atualizado.");
          setIsModalOpen(false);
          fetchStockItems();
        }
      } else {
        const { error } = await supabase.from("stock").insert([
          {
            product_id: form.product_id,
            quantity: quantityNumber,
            user_id: user.id,
          },
        ]);
        if (error) {
          toast.error("Erro ao criar item de estoque: " + error.message);
        } else {
          toast.success("Item de estoque criado.");
          setIsModalOpen(false);
          fetchStockItems();
        }
      }
    },
    [form, editingId, user, fetchStockItems]
  );

  const handleDelete = useCallback(
    async (id) => {
      if (!confirm("Tem certeza que quer excluir este item de estoque?"))
        return;

      const { error } = await supabase.from("stock").delete().eq("id", id);
      if (error) {
        toast.error("Erro ao excluir: " + error.message);
      } else {
        toast.success("Item de estoque excluído.");
        if (editingId === id) {
          setEditingId(null);
          setForm({ product_id: "", quantity: "" });
        }
        fetchStockItems();
      }
    },
    [editingId, fetchStockItems]
  );

  const requestSort = useCallback((key) => {
    setSortConfig((prevSortConfig) => {
      let direction = "asc";
      if (prevSortConfig.key === key && prevSortConfig.direction === "asc") {
        direction = "desc";
      }
      return { key, direction };
    });
  }, []);

  const debouncedSetFilter = useMemo(
    () =>
      debounce((value) => {
        setFilter(value);
        setCurrentPage(1);
      }, 300),
    []
  );

  useEffect(() => {
    return () => {
      debouncedSetFilter.cancel();
    };
  }, [debouncedSetFilter]);

  return (
    <div className="p-6 max-w-6xl mx-auto">
      <Card className="mb-5">
        <CardHeader>
          <div className="flex items-center justify-center">
            <div className="flex flex-col">
              <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
                Total de Itens em Estoque:
              </CardTitle>
              <CardDescription>
                Capacidade máxima de 2.000 items
              </CardDescription>
            </div>
            <Archive className="ml-auto w-6 h-6" />
          </div>
        </CardHeader>
        <CardContent>
          <p className="text-lg sm:text-xl font-bold">
            {totalQuantityInStock} / 2.000
          </p>
        </CardContent>
      </Card>

      <div className="flex flex-col sm:flex-row mb-6 gap-4 items-center justify-between">
        <FilterInput
          icon={SearchIcon}
          placeholder="Buscar por nome do produto..."
          onChange={(e) => debouncedSetFilter(e.target.value)}
        />
        <Button
          onClick={openCreateModal}
          variant="default"
          className="whitespace-nowrap"
        >
          Novo Item de Estoque
        </Button>
      </div>

      {loading ? (
        <LoadingSpinner />
      ) : filteredAndSortedStockItems.length === 0 ? (
        <p>Nenhum item de estoque encontrado.</p>
      ) : (
        <div className="overflow-x-auto border border-border rounded-md dark:bg-zinc-900">
          <table className="w-full min-w-[600px] text-left">
            <thead>
              <tr>
                <th
                  className="border-b border-border p-4 font-medium cursor-pointer select-none"
                  onClick={() => requestSort("product_name")}
                >
                  <div className="inline-flex items-center gap-1">
                    Produto
                    <SortIndicator
                      direction={
                        sortConfig.key === "product_name"
                          ? sortConfig.direction
                          : null
                      }
                    />
                  </div>
                </th>
                <th
                  className="border-b border-border p-4 font-medium cursor-pointer select-none"
                  onClick={() => requestSort("quantity")}
                >
                  <div className="inline-flex items-center gap-1">
                    Quantidade
                    <SortIndicator
                      direction={
                        sortConfig.key === "quantity"
                          ? sortConfig.direction
                          : null
                      }
                    />
                  </div>
                </th>
                <th
                  className="border-b border-border p-4 font-medium cursor-pointer select-none"
                  onClick={() => requestSort("updated_at")}
                >
                  <div className="inline-flex items-center gap-1">
                    Última Atualização
                    <SortIndicator
                      direction={
                        sortConfig.key === "updated_at"
                          ? sortConfig.direction
                          : null
                      }
                    />
                  </div>
                </th>
                <th className="border-b border-border p-4 font-medium">
                  Ações
                </th>
              </tr>
            </thead>
            <tbody>
              {paginatedStockItems.map((item) => (
                <tr
                  key={item.id}
                  className="border-b border-border last:border-0 hover:bg-muted"
                >
                  <td className="p-4 font-medium">
                    {item.products?.name || "Produto Desconhecido"}
                  </td>
                  <td className="p-4">{item.quantity}</td>
                  <td className="p-4">
                    {new Date(item.updated_at).toLocaleDateString("pt-BR")}{" "}
                    {new Date(item.updated_at).toLocaleTimeString("pt-BR")}
                  </td>
                  <td className="p-4 space-x-2">
                    <Button
                      size="sm"
                      variant="default"
                      onClick={() => openEditModal(item)}
                    >
                      Editar
                    </Button>
                    <Button
                      size="sm"
                      variant="outline"
                      className="text-destructive"
                      onClick={() => handleDelete(item.id)}
                    >
                      Excluir
                    </Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}

      {/* Paginação */}
      {filteredAndSortedStockItems.length > 0 && totalPages > 1 && (
        <div className="flex justify-center space-x-2 mt-4">
          <Button
            size="sm"
            disabled={currentPage === 1}
            onClick={() => setCurrentPage((p) => Math.max(p - 1, 1))}
          >
            <ChevronLeft />
          </Button>
          <span className="px-4 py-2 select-none">
            Página {currentPage} de {totalPages}
          </span>
          <Button
            size="sm"
            disabled={currentPage === totalPages}
            onClick={() => setCurrentPage((p) => Math.min(p + 1, totalPages))}
          >
            <ChevronRight />
          </Button>
        </div>
      )}

      {/* Modal Criar/Editar */}
      <Dialog open={isModalOpen} onOpenChange={setIsModalOpen}>
        <DialogContent className="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>
              {editingId ? "Editar Quantidade" : "Novo Item de Estoque"}
            </DialogTitle>
          </DialogHeader>
          <form onSubmit={handleSubmit} className="space-y-5">
            {!editingId && (
              <div className="space-y-2">
                <Label htmlFor="product_id">Produto</Label>
                <Select
                  value={form.product_id}
                  onValueChange={handleSelectChange}
                  name="product_id"
                  id="product_id"
                  required
                >
                  <SelectTrigger className="w-full">
                    <SelectValue placeholder="Selecione um produto" />
                  </SelectTrigger>
                  <SelectContent>
                    {availableProducts.length === 0 ? (
                      <SelectItem disabled>
                        Nenhum produto disponível
                      </SelectItem>
                    ) : (
                      availableProducts.map((p) => (
                        <SelectItem key={p.id} value={p.id}>
                          {p.name}
                        </SelectItem>
                      ))
                    )}
                  </SelectContent>
                </Select>
              </div>
            )}
            <div className="space-y-2">
              <Label htmlFor="quantity">Quantidade</Label>
              <Input
                id="quantity"
                name="quantity"
                type="number"
                value={form.quantity}
                onChange={handleChange}
                required
                autoFocus
                min="0"
              />
            </div>
            <DialogFooter>
              <DialogClose asChild>
                <Button type="button" variant="outline" className="mr-2">
                  Cancelar
                </Button>
              </DialogClose>
              <Button type="submit">{editingId ? "Salvar" : "Criar"}</Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </div>
  );
}
