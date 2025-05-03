import { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { supabase } from "@/supabase";
import Header from "@/components/Header";
import { toast } from "sonner";

export default function DashboardLayout({ children }) {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    const checkSession = async () => {
      const { data } = await supabase.auth.getSession();
      if (!data.session) {
        navigate("/");
      }
    };
    checkSession();
  }, [navigate]);

  const toggleMenu = () => setIsMenuOpen(!isMenuOpen);

  const handleLogout = async () => {
    await supabase.auth.signOut();
    console.log("User logged out, navigating to Landing Page");
    navigate("/");
  };

  return (
    <>
      <Header onLogout={handleLogout} />
      <div className="flex h-[557px]">
        <aside className="w-64 bg-gray-900 text-white p-4 space-y-6 md:block hidden">
          <h1 className="text-2xl font-bold">Meu Dashboard</h1>
          <nav className="space-y-2">
            <Link to="/dashboard" className="block hover:text-cyan-400">
              Visão Geral
            </Link>
            <Link
              to="/dashboard/usuarios"
              className="block hover:text-cyan-400"
            >
              Usuários
            </Link>
            <Link to="/dashboard/config" className="block hover:text-cyan-400">
              Configurações
            </Link>
          </nav>
        </aside>

        <div className="md:hidden">
          <button
            onClick={toggleMenu}
            className="text-black p-2 focus:outline-none"
          >
            {isMenuOpen ? "Fechar Menu" : "Abrir Menu"}
          </button>
          {isMenuOpen && (
            <nav className="absolute top-16 left-0 w-full bg-gray-900 text-white p-4 space-y-6 md:hidden">
              <Link to="/dashboard" className="block hover:text-cyan-400">
                Visão Geral
              </Link>
              <Link
                to="/dashboard/usuarios"
                className="block hover:text-cyan-400"
              >
                Usuários
              </Link>
              <Link
                to="/dashboard/config"
                className="block hover:text-cyan-400"
              >
                Configurações
              </Link>
            </nav>
          )}
        </div>

        <main className="flex-1 p-6 bg-gray-100 overflow-y-auto">
          {children}
        </main>
      </div>
    </>
  );
}
