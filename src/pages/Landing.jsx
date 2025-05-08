import ThemeSwitch from "@/components/Switch";
import { Globe, BarChart2, CheckCircle, UserCheck } from "lucide-react";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { supabase } from "@/supabase";
import { Button } from "@/components/ui/button";

export default function LandingPage() {
  const [user, setUser] = useState(null);

  useEffect(() => {
    const getUser = async () => {
      const { data } = await supabase.auth.getUser();
      setUser(data?.user || null);
    };

    getUser();

    const { data: listener } = supabase.auth.onAuthStateChange(
      (_event, session) => {
        setUser(session?.user || null);
      }
    );

    return () => {
      listener.subscription.unsubscribe();
    };
  }, []);

  return (
    <main className="min-h-screen bg-white dark:bg-zinc-900 text-black dark:text-white flex flex-col">
      <header className="flex sm:justify-between space-x-4 justify-center items-center px-6 md:px-20 py-4 border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
        <div className="flex items-center gap-2">
          <Globe className="text-black dark:text-white size-8 md:size-9" />
          <h1 className="font-bold hidden text-2xl md:block italic">
            Mini Dash
          </h1>
        </div>

        <div className="flex items-center gap-4">
          {user ? (
            <Button asChild>
              <Link to="/dashboard">Dashboard</Link>
            </Button>
          ) : (
            <>
              <Button variant="outline" asChild>
                <Link to="/login">Login</Link>
              </Button>
              <Button asChild>
                <Link to="/signup">Criar Conta</Link>
              </Button>
            </>
          )}
          <ThemeSwitch />
        </div>
      </header>

      <section className="flex-1 flex flex-col items-center justify-center text-center px-6 py-20">
        <h2 className="text-4xl md:text-6xl font-bold mb-6 max-w-3xl">
          Gerencie seus dados
        </h2>
        <p className="text-lg text-zinc-600 dark:text-zinc-400 mb-8 max-w-xl">
          Um sistema simples de gerenciamento pessoal com um dashboard do seu
          jeito.
        </p>
        <Button asChild>
          <Link to="/signup">Começar Agora</Link>
        </Button>
      </section>

      <section
        id="funcionamento"
        className="py-20 bg-zinc-100 dark:bg-zinc-900 text-center px-6"
      >
        <h3 className="text-3xl font-semibold mb-8">Como funciona?</h3>
        <div className="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          <div className="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow">
            <CheckCircle
              className="mx-auto text-black dark:text-white"
              size={40}
            />
            <h4 className="text-xl font-medium mt-4">Cadastro simples</h4>
            <p className="text-zinc-500 dark:text-zinc-400 mt-2 mb-3">
              Crie sua conta em poucos cliques e comece a organizar seus dados.
            </p>
          </div>
          <div className="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow">
            <BarChart2
              className="mx-auto text-black dark:text-white"
              size={40}
            />
            <h4 className="text-xl font-medium mt-4">Dashboard completo</h4>
            <p className="text-zinc-500 dark:text-zinc-400 mt-2 mb-3">
              Visualize informações importantes em um painel intuitivo.
            </p>
          </div>
          <div className="bg-white dark:bg-zinc-800 p-6 rounded-2xl shadow">
            <UserCheck
              className="mx-auto text-black dark:text-white"
              size={40}
            />
            <h4 className="text-xl font-medium mt-4">
              Gerencie com facilidade
            </h4>
            <p className="text-zinc-500 dark:text-zinc-400 mt-2 mb-3">
              Adicione, edite e acompanhe seus dados sempre que precisar.
            </p>
          </div>
        </div>
      </section>

      <section className="py-20 px-6 text-center">
        <h3 className="text-3xl font-semibold mb-8">O que estão dizendo</h3>
        <div className="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          <div className="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow">
            <p className="italic text-zinc-600 dark:text-zinc-300">
              “Simples, rápido e eficiente. Exatamente o que eu precisava.”
            </p>
            <p className="font-bold mt-4">— João M.</p>
          </div>
          <div className="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow">
            <p className="italic text-zinc-600 dark:text-zinc-300">
              “O Mini Dash me ajudou a me organizar melhor no dia a dia.”
            </p>
            <p className="font-bold mt-4">— Ana C.</p>
          </div>
          <div className="bg-white dark:bg-zinc-800 p-6 rounded-xl shadow">
            <p className="italic text-zinc-600 dark:text-zinc-300">
              “Fácil de usar e com ótimo visual. Recomendo demais.”
            </p>
            <p className="font-bold mt-4">— Pedro L.</p>
          </div>
        </div>
      </section>

      <footer className="text-center py-8 text-sm text-zinc-800 dark:text-zinc-400 border-t border-zinc-200 dark:border-zinc-700 mt-10">
        <div className="flex flex-col md:flex-row justify-center items-center gap-4 mb-4">
          <Link to="/sobre" className="hover:underline">
            Sobre
          </Link>
          <Link to="/contato" className="hover:underline">
            Contato
          </Link>
          <Link to="/termos" className="hover:underline">
            Termos de Uso
          </Link>
        </div>
        <p>
          © {new Date().getFullYear()} Mini Dash. Todos os direitos reservados.
        </p>
      </footer>
    </main>
  );
}
