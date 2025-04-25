import ThemeSwitch from "@/components/Switch";
import { Globe, BarChart2, CheckCircle, UserCheck } from "lucide-react";
import { Link } from "react-router-dom";

export default function LandingPage() {
  return (
    <main className="min-h-screen bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white flex flex-col">
      <header className="items-center flex justify-between px-20 h-15 shadow">
        <div className="flex items-center gap-2">
          <Globe size={26} className="text-azul-claro" />
          <h1 className="font-bold text-2xl italic">Mini Dash</h1>
        </div>

        <div className="flex items-center gap-4">
          <ThemeSwitch />
          <Link to="/login">
            <button className="bg-azul-claro text-white px-4 py-[2px] rounded-lg hover:bg-azul-escuro dark:hover:bg-white dark:text-white dark:hover:text-azul-claro transition text-lg hover:cursor-pointer">
              Login
            </button>
          </Link>
        </div>
      </header>

      <section className="flex-1 flex flex-col items-center justify-center text-center px-6 py-20">
        <h2 className="text-4xl md:text-6xl font-bold mb-6 max-w-3xl">
          Gerencie seus dados
        </h2>
        <p className="text-lg md:text-lg text-zinc-500 dark:text-zinc-400 mb-8 max-w-xl">
          Um sistema simples de gerenciamento pessoal com um dashboard do seu
          jeito.
        </p>
        <Link to="/signup">
          <button className="bg-azul-claro text-white px-4 py-2 rounded-lg hover:bg-azul-escuro transition text-lg hover:cursor-pointer dark:hover:bg-white dark:text-white dark:hover:text-azul-claro">
            Começar Agora
          </button>
        </Link>
      </section>

      <section
        id="funcionamento"
        className="py-20 bg-zinc-100 dark:bg-zinc-900 text-center px-6"
      >
        <h3 className="text-3xl font-semibold mb-8">Como funciona?</h3>
        <div className="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          <div className="shadow-lg rounded-2xl">
            <CheckCircle className="mx-auto text-azul-claro" size={40} />
            <h4 className="text-xl font-medium mt-4">Cadastro simples</h4>
            <p className="text-zinc-500 dark:text-zinc-400 mt-2 mb-3">
              Crie sua conta em poucos cliques e comece a organizar seus dados.
            </p>
          </div>
          <div className="shadow-lg rounded-2xl">
            <BarChart2 className="mx-auto text-azul-claro" size={40} />
            <h4 className="text-xl font-medium mt-4">Dashboard completo</h4>
            <p className="text-zinc-500 dark:text-zinc-400 mt-2 mb-3">
              Visualize informações importantes em um painel intuitivo.
            </p>
          </div>
          <div className="shadow-lg rounded-2xl">
            <UserCheck className="mx-auto text-azul-claro" size={40} />
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

      <footer className="text-center py-8 text-sm text-zinc-400 border-t border-zinc-200 dark:border-zinc-700 mt-10">
        <div className="flex flex-col md:flex-row justify-center items-center gap-4 mb-4">
          <Link href="/sobre" className="hover:underline">
            Sobre
          </Link>
          <Link href="/contato" className="hover:underline">
            Contato
          </Link>
          <Link href="/termos" className="hover:underline">
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
