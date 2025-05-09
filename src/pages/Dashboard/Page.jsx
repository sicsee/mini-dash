"use client";
import { useEffect, useState } from "react";
import { supabase } from "@/supabase";
import { Outlet } from "react-router-dom";
import { Sidebar } from "@/components/Sidebar";

export default function DashboardLayout() {
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
    <div className="flex-col sm:flex">
      <Sidebar />
      <main className="ml-1 sm:ml-14 p-4 w-[cal(100% - 56px)] flex flex-col transition-all">
        {userName && (
          <div className="mt-2 mb-5">
            <span className="text-xl italic text-foreground font-bold">
              Olá, {userName}
            </span>
          </div>
        )}

        <Outlet />
      </main>
    </div>
  );
}
