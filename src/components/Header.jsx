"use client";
import { supabase } from "@/supabase";
import { useNavigate } from "react-router-dom";
import { Link, NavLink } from "react-router-dom";
import { Cog, Globe } from "lucide-react";
import ThemeSwitch from "./Switch";
import { Button } from "./ui/button";
import { toast } from "sonner";

export default function Header() {
  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
      await supabase.auth.signOut();

      toast.success("Você foi desconectado com sucesso! ", {
        duration: 3000,
        style: {
          backgroundColor: "green",
          color: "white",
          fontFamily: "JetBrains Mono",
          border: "none",
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
        <h1 className="font-bold text-2xl italic">Mini Dash</h1>
      </div>
      <nav className="gap-4 flex">
        <NavLink
          to="/dashboard"
          className="hover:text-black text-gray-400 hover:font-medium dark:hover:text-white"
        >
          Dashboard
        </NavLink>
        <Link
          href="/gerenciamento"
          className="hover:text-black text-gray-400 hover:font-medium dark:hover:text-white"
        >
          Gerenciamento
        </Link>
      </nav>
      <div className="flex items-center gap-4">
        <ThemeSwitch />
        <Link href="/config">
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
      </div>
    </header>
  );
}
