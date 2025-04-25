import { useState, useEffect } from "react";
import { signIn } from "@/services/Auth";
import { useNavigate, Link } from "react-router-dom";
import { IoMdClose } from "react-icons/io";
import { Button } from "@/components/ui/button";
import { Card, CardHeader, CardContent } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { supabase } from "@/supabase";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  // Efeito para checar se o usuário já está autenticado
  useEffect(() => {
    const checkUser = async () => {
      const {
        data: { user },
      } = await supabase.auth.getUser();

      if (user) {
        navigate("/dashboard");
      }
    };

    checkUser();

    // Listener para detectar mudanças no estado de autenticação
    const { data: listener } = supabase.auth.onAuthStateChange(
      (event, session) => {
        if (session?.user) {
          navigate("/dashboard");
        }
      }
    );

    return () => listener?.subscription.unsubscribe();
  }, [navigate]);

  const handleGoogleLogin = async () => {
    const { data, error } = await supabase.auth.signInWithOAuth({
      provider: "google",
      redirectTo: `${window.location.origin}/dashboard`, // Direcionar para o dashboard após login
    });

    if (error) {
      console.error("Erro no login com Google:", error);
    }
  };

  const handleLogin = async (e) => {
    e.preventDefault();
    try {
      await signIn(email, password);
      setError("");
      navigate("/dashboard");
    } catch (err) {
      if (err.message.includes("Invalid login credentials")) {
        setError("Email ou senha incorretos. Verifique e tente novamente.");
      } else if (err.message.includes("User not found")) {
        setError("Usuário não encontrado. Verifique o email cadastrado.");
      } else {
        setError("Ocorreu um erro. Tente novamente mais tarde.");
      }
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
                className="font-thin hover:text-azul-claro transition-all ease-linear "
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

            <Button
              onClick={handleGoogleLogin}
              className="w-full bg-white text-black border border-gray-300 hover:bg-gray-100 transition-all hover:cursor-pointer"
            >
              Entrar com Google
            </Button>
          </CardContent>
        </Card>
      </div>
    </section>
  );
}
