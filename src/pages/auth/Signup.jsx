import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { IoMdClose } from "react-icons/io";
import { Button } from "@/components/ui/button";
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { supabase } from "@/supabase";
import { toast } from "sonner";
import { Separator } from "@/components/ui/separator";

export default function Signup() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [firstName, setFirstName] = useState("");
  const [lastName, setLastName] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  // Seu componente de cadastro
  const handleSignup = async (e) => {
    e.preventDefault();
    setError(""); // Limpa erros anteriores

    if (!email || !password || !firstName) {
      toast.error("Por favor, preencha todos os campos obrigatórios.", {
        duration: 3000,
        style: {
          backgroundColor: "red",
          color: "white",
          fontFamily: "Poppins",
          border: "none",
        },
      });
      return;
    }

    try {
      const { data: authData, error: authError } = await supabase.auth.signUp({
        email,
        password,
        options: {
          data: {
            first_name: firstName, // Isso vai para user_metadata no auth.users
            last_name: lastName || null, // Se você tiver um campo para sobrenome
          },
        },
      });

      if (authError) {
        toast.error("Erro no cadastro de usuário: " + authError.message, {
          duration: 4000,
          style: {
            backgroundColor: "red",
            color: "white",
            fontFamily: "Poppins",
            border: "none",
          },
        });
        return;
      }

      if (
        authData.user &&
        authData.user.identities &&
        authData.user.identities.length === 0
      ) {
        toast.info(
          "Verifique seu e-mail para confirmar a conta antes de fazer login.",
          { duration: 5000 }
        );
        navigate("/check-email"); // Redireciona para uma página de sucesso ou instrução
      } else {
        toast.success("Cadastro realizado com sucesso! Redirecionando...", {
          duration: 3000,
          style: { fontFamily: "Poppins", fontWeight: "bolder" },
        });
        navigate("/dashboard"); // Redireciona para o dashboard
      }
    } catch (err) {
      console.error("Erro geral no processo de cadastro:", err);
      setError("Ocorreu um erro inesperado. Tente novamente mais tarde.");
      toast.error("Erro inesperado: " + err.message, {
        duration: 5000,
        style: {
          backgroundColor: "red",
          color: "white",
          fontFamily: "Poppins",
          border: "none",
        },
      });
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
      <section className="bg-[url('./images/bg-black.jpg')] dark:bg-[url('./images/bg-white.jpg')] bg-no-repeat bg-cover bg-center  relative flex-1 items-center justify-center text-white dark:text-black p-10 overflow-hidden m-2 rounded-xl hidden md:flex">
        <div className="absolute -top-10 -right-10 h-56 w-56 rounded-full blur-[100px] opacity-70 z-0" />
        <div className="absolute -bottom-20 left-0 h-56 w-56 rounded-full blur-[100px] opacity-70 z-0" />
        <div className="z-10 max-w-md text-left">
          <h1 className="text-4xl md:text-5xl font-light mb-4">Bem-vindo.</h1>
          <p className="text-lg md:text-xl">
            Comece sua jornada agora mesmo com nosso sistema de gestão!
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

      <section className="flex-1 flex items-center justify-center p-6 bg-background">
        <div className="w-full max-w-md space-y-6">
          <Card className="w-full shadow-2xl border-none rounded-2xl">
            <div className="flex justify-end px-10">
              <Link to="/">
                <IoMdClose
                  size={35}
                  className="font-thin text-black dark:text-white transition-all ease-linear"
                />
              </Link>
            </div>
            <CardHeader className="text-3xl font-bold text-center text-zinc-800 dark:text-white">
              Crie sua conta
            </CardHeader>
            <CardContent className="space-y-6">
              <form onSubmit={handleSignup} className="space-y-4">
                <div className="inline-flex gap-5">
                  <div>
                    <label className="italic font-medium text-sm text-zinc-600 dark:text-zinc-300">
                      Nome
                    </label>

                    <Input
                      type="text"
                      placeholder="Seu primeiro nome"
                      onChange={(e) => setFirstName(e.target.value)}
                      required
                      className="mt-1"
                    />
                  </div>
                  <div>
                    <label className="italic font-medium text-sm text-zinc-600 dark:text-zinc-300">
                      Sobrenome
                    </label>
                    <Input
                      type="text"
                      placeholder="Seu sobrenome"
                      onChange={(e) => setLastName(e.target.value)}
                      required
                      className="mt-1"
                    />
                  </div>
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
                  className="w-full flex items-center justify-center gap-3 mt-5 text-sm cursor-pointer"
                >
                  Criar conta
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
