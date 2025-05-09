"use react";
import { useEffect, useState } from "react";
import { supabase } from "@/supabase";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Sidebar } from "@/components/Sidebar";
import { DollarSign, Users, Percent, BadgeDollarSign } from "lucide-react";

export default function Dashboard() {
  const [userName, setUserName] = useState("");

  useEffect(() => {
    const fetchUserName = async () => {
      const { data: userData, error: userError } =
        await supabase.auth.getUser();

      if (userError || !userData?.user?.id) {
        console.error("Erro ao obter o usuário:", userError?.message);
        return;
      }

      const { data: profileData, error: profileError } = await supabase
        .from("profiles")
        .select("name")
        .eq("id", userData.user.id)
        .single();

      if (profileError) {
        console.error("Erro ao buscar o nome do perfil:", profileError.message);
      }

      if (profileData?.name) {
        setUserName(profileData.name);
      }
    };

    fetchUserName();
  }, []);

  return (
    <>
      <Sidebar />
      <main className="ml-14 p-4">
        <div className="flex w-full mt-2 mb-5">
          {userName && (
            <span className="text-xl italic text-foreground font-bold">
              Olá, {userName}
            </span>
          )}
        </div>
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
      </main>
    </>
  );
}
