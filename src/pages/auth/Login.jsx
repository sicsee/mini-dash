import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { IoMdClose } from "react-icons/io";
import { Button } from "@/components/ui/button";
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { supabase } from "@/supabase";
import { toast } from "sonner";
import { Separator } from "@/components/ui/separator";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      const { error } = await supabase.auth.signInWithPassword({
        email,
        password,
      });

      if (error) {
        toast.error("Erro no login: " + error.message, {
          duration: 3000,
          style: {
            backgroundColor: "red",
            color: "white",
            fontFamily: "JetBrains Mono",
            border: "none",
          },
        });
      } else {
        toast.success("Login feito com sucesso!", {
          duration: 3000,
          style: {
            backgroundColor: "green",
            color: "white",
            fontFamily: "JetBrains Mono",
            border: "none",
          },
        });
        navigate("/dashboard");
      }
    } catch (err) {
      console.error(err);
      setError("Erro ao fazer login. Tente novamente.");
    }
  };

  const handleGoogleLogin = async () => {
    const { error } = await supabase.auth.signInWithOAuth({
      provider: "google",
      options: {
        redirectTo: `${window.location.origin}/dashboard`,
      },
    });

    if (error) {
      toast.error("Erro no login com Google: " + error.message);
    } else {
      toast.success("Login com Google realizado com sucesso!");
    }
  };

  return (
    <main className="min-h-screen flex flex-col md:flex-row overflow-hidden">
      <section className="flex-1 flex items-center justify-center p-6 bg-background">
        <div className="w-full max-w-md space-y-6">
          <Card className="w-full shadow-2xl border-none rounded-2xl">
            <div className="flex justify-end px-10">
              <Link to="/">
                <IoMdClose
                  size={35}
                  className="font-thin text-black dark:text-white hover:text-zinc-400 dark:hover:text-zinc-400 transition-all ease-linear"
                />
              </Link>
            </div>
            <CardHeader className="text-3xl font-bold text-center text-zinc-800 dark:text-white">
              Entre na sua conta
            </CardHeader>
            <CardContent className="space-y-6">
              <form onSubmit={handleLogin} className="space-y-4">
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
                  className="w-full flex items-center justify-center gap-3 mt-5 text-sm cursor-pointer"
                >
                  Entrar
                </Button>
                {error && (
                  <p className="text-red-500 text-center font-medium">
                    {error}
                  </p>
                )}
                <div className="flex items-center gap-6">
                  <Separator />
                  <span className="text-xs text-muted-foreground">ou</span>
                  <Separator />
                </div>

                <Button
                  onClick={handleGoogleLogin}
                  className="w-full flex items-center justify-center gap-3 mt-5 text-sm cursor-pointer"
                >
                  <img
                    src="https://www.svgrepo.com/show/475656/google-color.svg"
                    alt="Google"
                    className="h-5 w-5"
                  />
                  Entrar com Google
                </Button>
                <p className="text-sm text-center text-zinc-500 dark:text-zinc-400">
                  Não tem uma conta?{" "}
                  <Link
                    to="/signup"
                    className="text-azul-claro underline hover:opacity-90"
                  >
                    Faça o cadastro
                  </Link>
                </p>
              </form>
            </CardContent>
          </Card>
        </div>
      </section>
      <section className="bg-[url('./images/background.jpg')] bg-no-repeat bg-cover bg-center  relative flex-1 flex items-center justify-center text-white dark:text-black p-10 overflow-hidden m-2 rounded-xl">
        <div className="z-10 max-w-md text-left">
          <h1 className="text-4xl md:text-5xl font-light mb-4 text-center">
            Entre na sua conta.
          </h1>
          <p className="text-lg md:text-xl">
            Acesse seu painel e gerencie sua conta de forma rápida e segura!
          </p>
        </div>

        <div className="absolute top-6 left-6 z-20">
          <Link to="/">
            <h1 className="text-white dark:text-black font-bold text-2xl italic">
              Mini Dash{" "}
              <span className="text-white dark:text-black font-bold text-3xl">
                .
              </span>
            </h1>
          </Link>
        </div>
      </section>
    </main>
  );
}
