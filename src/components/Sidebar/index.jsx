"use react";

import {
  PanelBottom,
  Globe,
  Home,
  Package,
  Settings,
  LogOut,
  User,
  Archive,
} from "lucide-react";

import { Button } from "../ui/button";

import {
  Sheet,
  SheetContent,
  SheetFooter,
  SheetTrigger,
  SheetHeader,
  SheetTitle,
  SheetDescription,
} from "../ui/sheet";

import {
  TooltipProvider,
  Tooltip,
  TooltipTrigger,
  TooltipContent,
} from "../ui/tooltip";

import ThemeSwitch from "../Switch";

import { useNavigate, Link } from "react-router-dom";

import { toast } from "sonner";

import { supabase } from "@/supabase";

export function Sidebar() {
  const navigate = useNavigate();

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
    <div className="flex w-full sm:w-0 flex-col bg-muted/40">
      <aside className="fixed inset-y-0 left-0 z-10 hidden w-14 border-r bg-background sm:flex flex-col ">
        <nav className="flex flex-col items-center gap-4 px-2 py-5">
          <TooltipProvider>
            <Link
              to="/"
              className="flex h-9 w-9 shrink-0 items-center justify-center bg-primary text-primary-foreground rounded-full"
            >
              <Globe className="h-4 w-4" />

              <span className="sr-only">Mini Dash Logo</span>
            </Link>

            <Tooltip>
              <TooltipTrigger asChild>
                <Link
                  to=""
                  className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:text-foreground"
                >
                  <Home className="h-5 w-5" />

                  <span className="sr-only">Inicio</span>
                </Link>
              </TooltipTrigger>

              <TooltipContent side="right">Inicio</TooltipContent>
            </Tooltip>

            <Tooltip>
              <TooltipTrigger asChild>
                <Link
                  to="estoque"
                  className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:text-foreground"
                >
                  <Archive className="h-5 w-5" />

                  <span className="sr-only">Estoque</span>
                </Link>
              </TooltipTrigger>

              <TooltipContent side="right">Estoque</TooltipContent>
            </Tooltip>

            <Tooltip>
              <TooltipTrigger asChild>
                <Link
                  to="produtos"
                  className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:text-foreground"
                >
                  <Package className="w-5 h-5 " />

                  <span className="sr-only">Produtos</span>
                </Link>
              </TooltipTrigger>

              <TooltipContent side="right">Produtos</TooltipContent>
            </Tooltip>

            <Tooltip>
              <TooltipTrigger asChild>
                <Link
                  to="clientes"
                  className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:text-foreground"
                >
                  <User className="w-5 h-5 " />

                  <span className="sr-only">Clientes</span>
                </Link>
              </TooltipTrigger>

              <TooltipContent side="right">Clientes</TooltipContent>
            </Tooltip>

            <Tooltip>
              <TooltipTrigger asChild>
                <Link
                  to="settings"
                  className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:text-foreground"
                >
                  <Settings className="w-5 h-5" />

                  <span className="sr-only">Configurações</span>
                </Link>
              </TooltipTrigger>

              <TooltipContent side="right">Configurações</TooltipContent>
            </Tooltip>
          </TooltipProvider>
        </nav>

        <nav className="mt-auto flex flex-col items-center gap-4 px-2 py-5">
          <ThemeSwitch />

          <TooltipProvider>
            <Tooltip>
              <TooltipTrigger asChild>
                <Button
                  onClick={handleLogout}
                  variant="secondary"
                  className="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:text-foreground cursor-pointer"
                >
                  <LogOut className="w-5 h-5 text-red-500" />

                  <span className="sr-only">Sair</span>
                </Button>
              </TooltipTrigger>

              <TooltipContent side="right">Sair</TooltipContent>
            </Tooltip>
          </TooltipProvider>
        </nav>
      </aside>

      <div className="sm:hidden flex flex-col sm:gap-4 sm:py-4 sm:pl-14 ">
        <header className="sticky top-0 z-30 flex h-14 items-center px-4 border-b bg-background gap-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6 justify-between">
          <div className="flex items-center gap-5">
            <Sheet>
              <SheetTrigger asChild>
                <Button size="icon" variant="outline" className="sm:hidden">
                  <PanelBottom className="w-5 h-5 " />

                  <span className="sr-only">Abrir / Fechar menu</span>
                </Button>
              </SheetTrigger>

              <SheetContent side="left" className="sm:max-w-x">
                <SheetHeader>
                  <SheetTitle>Menu de Navegação</SheetTitle>

                  <SheetDescription></SheetDescription>
                </SheetHeader>

                <nav className="grid gap-6 text-lg font-medium p-3">
                  <Link
                    to="/"
                    className="flex h-10 w-10 bg-primary rounded-full items-center justify-center text-primary-foreground md:text-base gap-2"
                    prefetch={false}
                  >
                    <Globe className="w-5 h-5 transition-all" />

                    <span className="sr-only">Mini Dash Logo</span>
                  </Link>

                  <Link
                    to="/dashboard"
                    className="flex items-center gap-4 px-2.5 text-muted-foreground hover:text-foreground"
                    prefetch={false}
                  >
                    <Home className="w-5 h-5 transition-all" />
                    Inicio
                  </Link>

                  <Link
                    to="estoque"
                    className="flex items-center gap-4 px-2.5 text-muted-foreground hover:text-foreground"
                    prefetch={false}
                  >
                    <Archive className="w-5 h-5 transition-all" />
                    Estoque
                  </Link>

                  <Link
                    to="produtos"
                    className="flex items-center gap-4 px-2.5 text-muted-foreground hover:text-foreground"
                    prefetch={false}
                  >
                    <Package className="w-5 h-5 transition-all" />
                    Produtos
                  </Link>

                  <Link
                    to="clientes"
                    className="flex items-center gap-4 px-2.5 text-muted-foreground hover:text-foreground"
                    prefetch={false}
                  >
                    <User className="w-5 h-5 transition-all" />
                    Clientes
                  </Link>

                  <Link
                    to="settings"
                    className="flex items-center gap-4 px-2.5 text-muted-foreground hover:text-foreground"
                    prefetch={false}
                  >
                    <Settings className="w-5 h-5 transition-all" />
                    Configurações
                  </Link>
                </nav>

                <SheetFooter className="p-3 mt-auto">
                  <Button
                    onClick={handleLogout}
                    variant="default" // Ajustado para "default" ou "secondary" conforme preferência
                    className="flex items-center gap-4 px-2.5 w-full justify-center" // Centraliza o texto e ícone
                  >
                    <LogOut className="w-5 h-5 text-red-500" />
                    Sair
                  </Button>
                </SheetFooter>
              </SheetContent>
            </Sheet>

            <h2>Menu</h2>
          </div>

          <ThemeSwitch />
        </header>
      </div>
    </div>
  );
}
