"use client";
import { useEffect, useState } from "react";
import { supabase } from "@/supabase";
import { useNavigate, Link, NavLink } from "react-router-dom";
import { Cog, Globe } from "lucide-react";
import ThemeSwitch from "./Switch";
import { Button } from "./ui/button";
import { toast } from "sonner";

export default function Header() {
  const [userName, setUserName] = useState("");
  const navigate = useNavigate();

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

  const handleLogout = async () => {
    try {
      await supabase.auth.signOut();

      toast.success("Você foi desconectado com sucesso! ", {
        duration: 3000,
        style: {
          fontFamily: "Poppins",
          fontWeight: "bolder",
        },
      });

      navigate("/");
    } catch (error) {
      console.error("Erro ao fazer logout:", error.message);
      toast.error("Ocorreu um erro ao tentar sair.");
    }
  };

  return (
    <header className="items-center flex justify-between px-20 h-15 shadow">
      <div className="flex items-center gap-2">
        <Globe size={26} className="text-azul-claro" />
        <Link to="/">
          <h1 className=" font-bold font-lg md:text-2xl italic">
            Mini Dash{" "}
            <span className="text-azul-claro font-bold text-3xl rounded-full">
              .
            </span>
          </h1>
        </Link>
      </div>
      <nav className="gap-4 flex">
        <NavLink
          to="/dashboard"
          className="hover:text-black text-gray-400 hover:font-medium dark:hover:text-white"
        >
          Dashboard
        </NavLink>
        <Link
          to="/gerenciamento"
          className="hover:text-black text-gray-400 hover:font-medium dark:hover:text-white"
        >
          Gerenciamento
        </Link>
      </nav>
      <div className="flex items-center gap-4">
        {userName && (
          <span className="text-sm italic text-gray-600 dark:text-gray-300">
            Olá, {userName}
          </span>
        )}
        <Link to="/config">
          <i>
            <Cog className="hover:rotate-90 hover:scale-105 transition-all ease-linear duration-200" />
          </i>
        </Link>
        <Button
          onClick={handleLogout}
          className="hover:cursor-pointer bg-azul-claro hover:bg-azul-claro/40 transition-all ease-linear px-5 "
        >
          Sair
        </Button>
        <ThemeSwitch />
      </div>
    </header>
  );
}
