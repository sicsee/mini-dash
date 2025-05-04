import { useState, useEffect } from "react";
import { supabase } from "@/supabase";
import { toast } from "sonner";
import Header from "@/components/Header";
import { Button } from "@/components/ui/button"; // Supondo que você tenha um botão customizado

export default function Dashboard() {
  const [userName, setUserName] = useState("");
  const [loading, setLoading] = useState(true);
  const [userStats, setUserStats] = useState({
    tasksCompleted: 0,
    tasksPending: 0,
  });

  useEffect(() => {
    const fetchUserData = async () => {
      try {
        const { data: userData, error: userError } =
          await supabase.auth.getUser();
        if (userError || !userData?.user?.id) {
          toast.error("Erro ao obter usuário", {
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

        const userId = userData.user.id;

        const { data: profileData, error: profileError } = await supabase
          .from("profiles")
          .select("name")
          .eq("id", userId)
          .single();

        if (profileError || !profileData) {
          toast.error("Erro ao carregar nome do perfil", {
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

        setUserName(profileData.name);

        const { data: statsData, error: statsError } = await supabase
          .from("user_stats")
          .select("*")
          .eq("user_id", userId)
          .single();

        if (statsError || !statsData) {
          toast.error("Erro ao carregar estatísticas", {
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

        setUserStats({
          tasksCompleted: statsData.tasks_completed,
          tasksPending: statsData.tasks_pending,
        });
      } catch (err) {
        console.error("Erro inesperado:", err);
      } finally {
        setLoading(false);
      }
    };

    fetchUserData();
  }, []);

  if (loading) {
    return <div>Carregando...</div>;
  }

  return (
    <div>
      <Header />
      <h1>Olá, Mundo!</h1>
    </div>
  );
}
