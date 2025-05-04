import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { IoMdClose } from "react-icons/io";
import { Button } from "@/components/ui/button";
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { supabase } from "@/supabase";
import { toast } from "sonner";

export default function Signup() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [userName, setUserName] = useState(""); // Novo campo para o nome
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleSignup = async (e) => {
    e.preventDefault();
    try {
      // Cadastro
      const { data, error } = await supabase.auth.signUp({
        email,
        password,
        options: {
          data: {
            display_name: userName,
          },
        },
      });
      const user = data?.user;

      if (error) {
        toast.error("Erro no cadastro: " + error.message, {
          duration: 3000,
          style: {
            backgroundColor: "red",
            color: "white",
            fontFamily: "JetBrains Mono",
            border: "none",
          },
        });
        return;
      }

      if (!user || !user.id || !userName) {
        toast.error("Dados do usuário ou nome inválidos!", {
          duration: 3000,
          style: {
            backgroundColor: "red",
            color: "white",
            fontFamily: "JetBrains Mono",
            border: "none",
          },
        });
        return;
      }

      if (profileError) {
        console.error("Erro ao inserir no perfil:", profileError);
        toast.error("Erro ao criar perfil: " + profileError.message, {
          duration: 3000,
          style: {
            backgroundColor: "red",
            color: "white",
            fontFamily: "JetBrains Mono",
            border: "none",
          },
        });
        return;
      }

      toast.success("Cadastro feito com sucesso!", {
        duration: 3000,
        style: {
          backgroundColor: "green",
          color: "white",
          fontFamily: "JetBrains Mono",
          border: "none",
        },
      });

      navigate("/dashboard");
    } catch (err) {
      console.error(err);
      setError("Erro ao fazer cadastro. Tente novamente.");
    }
  };

  return (
    <main className="min-h-screen flex flex-col md:flex-row bg-azul-escuro overflow-hidden">
      <section className="relative flex-1 flex items-center justify-center text-white p-10 overflow-hidden">
        <div className="absolute -top-10 -right-10 h-56 w-56 bg-azul-claro rounded-full blur-[100px] opacity-70 z-0" />
        <div className="absolute -bottom-20 left-0 h-56 w-56 bg-azul-claro rounded-full blur-[100px] opacity-70 z-0" />
        <div className="z-10 max-w-md text-left">
          <h1 className="text-4xl md:text-5xl font-light mb-4">Bem-vindo.</h1>
          <p className="text-lg md:text-xl">
            Comece sua jornada agora mesmo com nosso sistema de gestão!
          </p>
        </div>

        <div className="absolute top-6 left-6 z-20">
          <Link to="/">
            <h1 className="text-white font-bold text-2xl italic">
              Mini Dash{" "}
              <span className="text-azul-claro font-bold text-3xl">.</span>
            </h1>
          </Link>
        </div>
      </section>

      <section className="flex-1 flex items-center justify-center p-6 bg-background">
        <div className="w-full max-w-md space-y-6">
          <Card className="w-full shadow-2xl border-none rounded-2xl">
            <div className="flex justify-end px-10">
              <Link to="/">
                <IoMdClose
                  size={35}
                  className="font-thin hover:text-azul-claro transition-all ease-linear"
                />
              </Link>
            </div>
            <CardHeader className="text-3xl font-bold text-center text-zinc-800 dark:text-white">
              Crie sua conta
            </CardHeader>
            <CardContent className="space-y-6">
              <form onSubmit={handleSignup} className="space-y-4">
                <div>
                  <label className="italic font-medium text-sm text-zinc-600 dark:text-zinc-300">
                    Nome
                  </label>
                  <Input
                    type="text"
                    placeholder="Seu nome"
                    onChange={(e) => setUserName(e.target.value)}
                    required
                    className="mt-1"
                  />
                </div>

                <div>
                  <label className="italic font-medium text-sm text-zinc-600 dark:text-zinc-300">
                    Email
                  </label>
                  <Input
                    type="email"
                    placeholder="email@email.com"
                    onChange={(e) => setEmail(e.target.value)}
                    required
                    className="mt-1"
                  />
                </div>

                <div>
                  <label className="italic font-medium text-sm text-zinc-600 dark:text-zinc-300">
                    Senha
                  </label>
                  <Input
                    type="password"
                    placeholder="********"
                    onChange={(e) => setPassword(e.target.value)}
                    required
                    className="mt-1"
                  />
                </div>

                <Button
                  type="submit"
                  className="w-full bg-azul-claro hover:bg-azul-escuro hover:cursor-pointer text-white dark:hover:bg-white dark:hover:text-azul-claro font-semibold py-2 rounded-md transition-all"
                >
                  Criar conta
                </Button>

                {error && (
                  <p className="text-red-500 text-center font-medium">
                    {error}
                  </p>
                )}

                <p className="text-sm text-center text-zinc-500 dark:text-zinc-400">
                  Já tem uma conta?{" "}
                  <Link
                    to="/login"
                    className="text-azul-claro underline hover:opacity-90"
                  >
                    Faça login
                  </Link>
                </p>
              </form>
            </CardContent>
          </Card>
        </div>
      </section>
    </main>
  );
}
