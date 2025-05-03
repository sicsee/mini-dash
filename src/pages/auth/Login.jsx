import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { IoMdClose } from "react-icons/io";
import { Button } from "@/components/ui/button";
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { supabase } from "@/supabase";
import { toast } from "sonner";

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

  // Função de login com Google
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
    <section className="h-screen w-screen flex flex-col bg-azul-escuro relative overflow-hidden">
      <div className="absolute top-6 left-6 z-20">
        <Link to="/">
          <h1 className="text-white font-bold text-2xl italic">
            Mini Dash{" "}
            <span className="text-azul-claro font-bold text-3xl rounded-full">
              .
            </span>
          </h1>
        </Link>
      </div>

      <div className="absolute -top-10 -right-10 bg-azul-claro h-48 w-48 rounded-full blur-[100px] opacity-80 z-0"></div>
      <div className="absolute bottom-0 left-0 bg-azul-claro h-48 w-48 rounded-full blur-[100px] opacity-80 z-0"></div>

      <div className="flex flex-1 items-center justify-center">
        <Card className="w-[350px] md:w-[450px] border-none shadow-none z-10">
          <div className="flex justify-end px-10">
            <Link to="/">
              <IoMdClose
                size={35}
                className="font-thin hover:text-azul-claro transition-all ease-linear"
              />
            </Link>
          </div>
          <CardHeader className="text-2xl font-bold text-center">
            Entre na sua conta
          </CardHeader>
          <CardContent>
            <form onSubmit={handleLogin} className="space-y-4">
              <div>
                <label className="italic font-medium">Email</label>
                <Input
                  type="email"
                  placeholder="email@email.com"
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
              </div>

              <div>
                <label className="italic font-medium">Senha</label>
                <Input
                  type="password"
                  placeholder="********"
                  onChange={(e) => setPassword(e.target.value)}
                  required
                />
              </div>

              <p className="font-medium text-zinc-500 text-center mt-2">
                <Link to="/senha" className="text-blue-600">
                  Esqueceu sua senha?
                </Link>
              </p>

              <Button
                type="submit"
                className="w-full bg-azul-claro hover:cursor-pointer hover:bg-azul-escuro dark:hover:bg-white dark:text-white dark:hover:text-azul-claro transition-all ease-linear duration-150"
              >
                Entrar
              </Button>

              {error && (
                <p className="text-red-500 text-center font-medium">{error}</p>
              )}
            </form>

            <button
              onClick={handleGoogleLogin}
              className="bg-white w-full flex items-center justify-center gap-3 py-2 px-4  rounded-md text-sm font-medium text-zinc-700 hover:bg-zinc-300 transition hover:cursor-pointer mt-5"
            >
              <img
                src="https://www.svgrepo.com/show/475656/google-color.svg"
                alt="Google"
                className="h-5 w-5"
              />
              Entrar com Google
            </button>
          </CardContent>
        </Card>
      </div>
    </section>
  );
}
