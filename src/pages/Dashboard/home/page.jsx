import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { DollarSign, Users, Percent, BadgeDollarSign } from "lucide-react";

export default function DashboardHome() {
  return (
    <section className="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <Card>
        <CardHeader>
          <div className="flex items-center justify-center">
            <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
              Total de Vendas
            </CardTitle>
            <DollarSign className="ml-auto w-4 h-4" />
          </div>
          <CardDescription>Total vendas em 90 dias</CardDescription>
        </CardHeader>
        <CardContent>
          <p className="text-base sm:text-lg font-bold">R$ 23.000</p>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <div className="flex items-center justify-center">
            <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
              Novos Clientes
            </CardTitle>
            <Users className="ml-auto w-4 h-4" />
          </div>
          <CardDescription>Novos clientes em 30 dias</CardDescription>
        </CardHeader>
        <CardContent>
          <p className="text-base sm:text-lg font-bold">237</p>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <div className="flex items-center justify-center">
            <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
              Pedidos hoje
            </CardTitle>
            <Percent className="ml-auto w-4 h-4" />
          </div>
          <CardDescription>Total de pedidos hoje</CardDescription>
        </CardHeader>
        <CardContent>
          <p className="text-base sm:text-lg font-bold">29</p>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <div className="flex items-center justify-center">
            <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
              Total Pedidos
            </CardTitle>
            <BadgeDollarSign className="ml-auto w-4 h-4" />
          </div>
          <CardDescription>Total de pedidos em 30 dias</CardDescription>
        </CardHeader>
        <CardContent>
          <p className="text-base sm:text-lg font-bold">1348</p>
        </CardContent>
      </Card>
    </section>
  );
}
