"use client";
import { supabase } from "@/supabase";
import { useNavigate } from "react-router-dom";
import { Link, NavLink } from "react-router-dom";
import { Cog, Globe } from "lucide-react";
import ThemeSwitch from "./Switch";

export default function Header() {
  const navigate = useNavigate();
  const handleLogout = async () => {
    await supabase.auth.signOut();
    navigate("/");
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
        <button onClick={handleLogout}>Sair</button>
      </div>
    </header>
  );
}
