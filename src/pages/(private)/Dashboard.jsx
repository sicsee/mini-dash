import { useEffect, useState } from "react";
import { supabase } from "@/supabase";
import { useNavigate } from "react-router-dom";
import Header from "@/components/Header";

export default function Dashboard() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUser = async () => {
      const { data, error } = await supabase.auth.getUser();
      if (error || !data.user) {
        navigate("/login"); // redireciona se não estiver logado
      } else {
        setUser(data.user);
      }
      setLoading(false);
    };

    fetchUser();
  }, [navigate]);

  if (loading) return <p>Carregando...</p>;

  return (
    <>
      <Header />
      <div>
        <h1>Bem-vindo ao Dashboard, {username || user?.email}!</h1>
      </div>
    </>
  );
}
