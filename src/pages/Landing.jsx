import { useEffect, useState, useCallback } from "react";
import { Link } from "react-router-dom";
import { supabase } from "@/supabase";
import { Button } from "@/components/ui/button";
import HeaderLanding from "@/components/LandingPage/header";
import { UserCheck, BarChart2, CheckCircle } from "lucide-react";

import backgroundImage from "/images/bg-land.png";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

export default function LandingPage() {
  const [user, setUser] = useState(null);
  const [showIntroAnimation, setShowIntroAnimation] = useState(true);
  const [backgroundLoaded, setBackgroundLoaded] = useState(false);
  const [animationStarted, setAnimationStarted] = useState(false);

  useEffect(() => {
    const img = new Image();
    img.src = backgroundImage;

    const handleLoad = () => {
      setBackgroundLoaded(true);
    };

    img.onload = handleLoad;

    if (img.complete) {
      handleLoad();
    }
  }, []);

  useEffect(() => {
    let timeoutId;
    if (backgroundLoaded && !animationStarted) {
      setAnimationStarted(true);

      timeoutId = setTimeout(() => {
        setShowIntroAnimation(false);
      }, 500); // 500ms antes da vinheta começar a desaparecer

      return () => {
        clearTimeout(timeoutId);
      };
    }
  }, [backgroundLoaded, animationStarted]);

  useEffect(() => {
    const getUserSession = async () => {
      const { data } = await supabase.auth.getUser();
      setUser(data?.user || null);
    };

    getUserSession();

    const { data: authListener } = supabase.auth.onAuthStateChange(
      (_event, session) => {
        setUser(session?.user || null);
      }
    );

    return () => {
      authListener.subscription.unsubscribe();
    };
  }, []);

  const contentInitialClasses = `${
    !backgroundLoaded || !animationStarted ? "opacity-0-initial" : ""
  }`;

  return (
    <main className="min-h-screen bg-black text-white flex flex-col relative overflow-hidden">
      {showIntroAnimation && (
        <div
          className={`fixed inset-0 bg-black z-50 animate-fade-out-bg`}
          style={{ pointerEvents: "none" }}
        ></div>
      )}

      <HeaderLanding />

      <section className="flex flex-col items-center justify-center relative p-4 h-screen overflow-hidden">
        <div
          className={`absolute inset-0 bg-no-repeat bg-cover bg-center 
                      ${contentInitialClasses} 
                      ${
                        backgroundLoaded && animationStarted
                          ? "animate-zoom-out-bg"
                          : ""
                      }`}
          style={{ backgroundImage: `url(${backgroundImage})` }}
        ></div>

        <div className="max-w-3xl w-full relative z-10 flex items-center flex-col text-center">
          <h2
            className={`text-4xl md:text-6xl font-bold mb-6 text-white font-bodoni ${
              animationStarted
                ? "animate-slide-in-bottom animate-delay-900"
                : ""
            }`}
          >
            Mini Dash
          </h2>
          <p
            className={`text-lg text-zinc-100 mb-8 max-w-xl mx-auto ${
              animationStarted
                ? "animate-slide-in-bottom animate-delay-900"
                : ""
            }`}
          >
            Um sistema simples e eficaz de gerenciamento de vendas e produtos
            com um dashboard completo.
          </p>
          {user ? (
            <Button
              asChild
              className={`${
                animationStarted
                  ? "animate-slide-in-bottom animate-delay-900"
                  : ""
              }`}
            >
              <Link to="/dashboard">Dashboard</Link>
            </Button>
          ) : (
            <>
              <Button
                asChild
                className={` ${
                  animationStarted
                    ? "animate-slide-in-bottom animate-delay-900 "
                    : ""
                }`}
              >
                <Link to="/signup">Criar Conta</Link>
              </Button>
            </>
          )}
        </div>
      </section>

      <section className="p-8 flex flex-col text-center space-y-10 mt-5 items-center relative">
        <div>
          <h1 className="text-3xl sm:text-4xl lg:text-5xl font-medium sm:font-bold">
            Visão Clara, Decisões Inteligentes
          </h1>
          <p className="text-sm sm:text-base text-zinc-400">
            Seu Dashboard Completo em Um Piscar de Olhos
          </p>
        </div>

        <p className="text-base sm:text-lg text-center w-full max-w-4xl ">
          Explore o poder do Mini Dash com nosso painel intuitivo. Tenha dados
          de vendas, estoque e clientes centralizados para decisões rápidas e
          eficazes, tudo em um só lugar.
        </p>

        <div class="relative -mr-56 overflow-hidden px-2 sm:mr-0 mt-10">
          <div
            aria-hidden="true"
            class="bg-gradient-to-b from-transparent via-black/50 to-black absolute inset-0 z-10"
          ></div>
          <div class="relative mx-auto max-w-6xl overflow-hidden rounded-2xl border border- bg-gradient-to-br from-black/80 to-black/60 shadow-lg">
            <img
              alt="Dashboard"
              class="border border-border/50 rounded-xl"
              src="/images/Dash.png"
            />
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
