function saleForm(products = {}) {
    return {
        // Estado do Modal e Controle
        activeModal: null,
        products: typeof products === "object" ? products : {},

        // Dados principais da venda
        formData: {
            id: null,
            customer_id: "",
            sale_date: "",
            status: "concluida",
            notes: "",
        },

        // Itens da venda
        items: [{ product_id: "", quantity: 1, price: 0 }],

        // Prepara o formulário para uma nova venda
        openCreate() {
            this.formData = {
                id: null,
                customer_id: "",
                sale_date: new Date().toISOString().split("T")[0], // Data de hoje
                status: "concluida",
                notes: "",
            };
            this.items = [{ product_id: "", quantity: 1, price: 0 }];
            this.activeModal = "sale-modal";
        },

        // Prepara o formulário com dados existentes para edição
        openEdit(sale) {
            this.formData = {
                id: sale.id,
                customer_id: sale.customer_id,
                sale_date: sale.sale_date.split(" ")[0], // Garante formato YYYY-MM-DD
                status: sale.status,
                notes: sale.notes || "",
            };

            // Carrega os itens da venda (certificando-se que preços são números)
            this.items = sale.items.map((item) => ({
                product_id: item.product_id,
                quantity: item.quantity,
                price: parseFloat(item.price),
            }));

            this.activeModal = "sale-modal";
        },

        addItem() {
            this.items.push({
                product_id: "",
                quantity: 1,
                price: 0,
            });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        onProductChange(item) {
            const productId = String(item.product_id);
            if (productId && this.products[productId] !== undefined) {
                item.price = this.products[productId];
            }
        },

        get totalSale() {
            return this.items
                .reduce((total, item) => total + item.quantity * item.price, 0)
                .toFixed(2);
        },

        // Formata o total para exibição em PT-BR
        get totalFormatted() {
            return parseFloat(this.totalSale).toLocaleString("pt-BR", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },
    };
}

window.saleForm = saleForm;
console.log("Lógica de vendas carregada com sucesso!");
