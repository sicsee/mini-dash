import { useState, useEffect } from "react";
import { supabase } from "@/supabase"; // Ajuste o caminho para o seu cliente Supabase
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input"; // Importar Input para campos editáveis
import { Label } from "@/components/ui/label"; // Importar Label para campos de formulário
import { PencilLine, ImagePlus } from "lucide-react"; // ImagePlus para upload de avatar
import { toast } from "sonner"; // Assumindo que você usa sonner para toasts

export default function Settings() {
  const [loading, setLoading] = useState(true); // Começa como true para mostrar o carregamento
  const [profile, setProfile] = useState(null);
  const [isEditingPersonal, setIsEditingPersonal] = useState(false);
  // Estados para os novos valores dos campos de edição
  const [newFirstName, setNewFirstName] = useState("");
  const [newLastName, setNewLastName] = useState("");
  const [newEmail, setNewEmail] = useState("");
  const [newPhone, setNewPhone] = useState("");

  // --- Funções de Busca de Dados ---
  const fetchProfile = async () => {
    try {
      setLoading(true); // Inicia o loading
      const {
        data: { user },
      } = await supabase.auth.getUser();

      if (!user) {
        toast.error("Usuário não autenticado. Redirecionando para o login.");
        // Opcional: redirecionar para a página de login
        // navigate('/login');
        return;
      }

      // Busca o perfil na tabela 'profiles'
      const { data, error } = await supabase
        .from("profiles")
        .select("*")
        .eq("user_id", user.id)
        .single(); // Espera apenas um resultado

      if (error && error.code !== "PGRST116") {
        // PGRST116 significa "nenhuma linha encontrada"
        throw error;
      }

      if (data) {
        // Se o perfil for encontrado, preenche os estados
        setProfile(data);
        setNewFirstName(data.first_name || "");
        setNewLastName(data.last_name || "");
        setNewEmail(data.email || ""); // O email aqui deve vir do auth.users idealmente
        setNewPhone(data.phone || "");
      } else {
        // Fallback: Se o perfil não for encontrado (ex: trigger falhou ou atrasou),
        // preenche com dados do auth.user e valores padrão/vazios.
        // O trigger no banco de dados já deveria ter criado, mas como fallback.
        setProfile({
          id: user.id,
          user_id: user.id,
          first_name: user.user_metadata?.first_name || "",
          last_name: user.user_metadata?.last_name || "",
          email: user.email,
          phone: "",
          avatar_url: user.user_metadata?.avatar_url || "",
          created_at: user.created_at, // Use created_at do auth.users
          updated_at: new Date().toISOString(),
        });
        setNewFirstName(user.user_metadata?.first_name || "");
        setNewLastName(user.user_metadata?.last_name || "");
        setNewEmail(user.email || "");
        setNewPhone("");
        console.warn(
          "Perfil não encontrado na tabela profiles. Usando dados do auth.user como fallback."
        );
      }
    } catch (error) {
      console.error("Erro ao buscar perfil:", error.message);
      toast.error("Erro ao carregar dados do perfil: " + error.message, {
        duration: 5000,
      });
    } finally {
      setLoading(false); // Finaliza o loading
    }
  };

  // Efeito para buscar o perfil quando o componente é montado
  useEffect(() => {
    fetchProfile();
  }, []);

  // --- Funções de Edição e Salvamento ---
  const handleSavePersonal = async () => {
    try {
      setLoading(true);
      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) {
        toast.error(
          "Usuário não autenticado. Por favor, faça login novamente."
        );
        setLoading(false);
        return;
      }

      // 1. Atualizar a tabela 'profiles'
      const { error: profileError } = await supabase
        .from("profiles")
        .update({
          first_name: newFirstName,
          last_name: newLastName,
          phone: newPhone,
          // email: newEmail, // Evite atualizar o email da tabela profiles diretamente se ele espelha o auth.users.email
          updated_at: new Date().toISOString(), // Atualiza o updated_at manualmente ou confie no trigger
        })
        .eq("user_id", user.id); // Garante que você só atualiza seu próprio perfil

      if (profileError) throw profileError;

      // 2. Opcional: Atualizar o email no Auth se ele mudou
      // ATENÇÃO: Mudar o email no Supabase Auth geralmente requer verificação por email.
      // Você precisaria de um fluxo para isso, que não está incluído aqui.
      // Considere desabilitar a edição do email diretamente nesta tela se não tiver o fluxo de verificação.
      if (newEmail && newEmail !== user.email) {
        const { error: authEmailError } = await supabase.auth.updateUser({
          email: newEmail,
        });
        if (authEmailError) {
          // Se a atualização de e-mail falhar, pode ser devido à necessidade de confirmação.
          toast.info(
            "Um email de verificação foi enviado para o novo endereço. Por favor, confirme a alteração de email.",
            { duration: 7000 }
          );
          console.warn(
            "Erro ao atualizar email no Auth (pode ser necessário verificação):",
            authEmailError.message
          );
        } else {
          toast.success("Email atualizado com sucesso no Auth.");
        }
      }

      // 3. Opcional: Atualizar metadados do usuário no Auth para first_name/last_name
      // Isso é útil se você usa esses metadados em outras partes da sua aplicação.
      const { error: metaError } = await supabase.auth.updateUser({
        data: {
          first_name: newFirstName,
          last_name: newLastName,
        },
      });

      if (metaError)
        console.warn(
          "Erro ao atualizar metadados do usuário:",
          metaError.message
        );

      await fetchProfile(); // Recarrega os dados para mostrar as atualizações
      setIsEditingPersonal(false); // Sai do modo de edição
      toast.success("Informações pessoais atualizadas com sucesso!", {
        duration: 3000,
      });
    } catch (error) {
      console.error("Erro ao salvar informações pessoais:", error.message);
      toast.error("Erro ao salvar: " + error.message, { duration: 5000 });
    } finally {
      setLoading(false);
    }
  };

  const handleAvatarUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) {
      toast.error("Nenhum arquivo selecionado para upload.");
      return;
    }

    // Validação básica do tipo de arquivo
    const allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
    if (!allowedTypes.includes(file.type)) {
      toast.error(
        "Formato de imagem não suportado. Use JPG, PNG, GIF ou WebP."
      );
      return;
    }

    setLoading(true); // Inicia o loading
    console.log("Iniciando upload de avatar para o arquivo:", file.name);

    try {
      const {
        data: { user },
      } = await supabase.auth.getUser();
      if (!user) {
        toast.error("Usuário não autenticado. Faça login novamente.");
        setLoading(false);
        return;
      }
      console.log("User ID (auth.uid()):", user.id); // Verifique este log no console!

      const fileExtension = file.name.split(".").pop();
      // --- LINHA CRÍTICA: VERIFIQUE ESTA LINHA NO SEU CÓDIGO ---
      const filePath = `${user.id}/${Date.now()}.${fileExtension}`;
      // --- E NENHUM CARACTERE ESTRANHO AQUI! ---
      console.log("Calculated file path for Storage:", filePath); // Verifique o formato no console!

      // Nome do seu bucket de Storage (verifique se este nome está correto no Supabase!)
      const bucketName = "avatars";

      const { data: uploadData, error: uploadError } = await supabase.storage
        .from(bucketName)
        .upload(filePath, file, {
          cacheControl: "3600", // Cache por 1 hora
          upsert: true, // Sobrescreve se um arquivo com o mesmo nome e caminho já existir
        });

      if (uploadError) {
        console.error("Erro no upload para o Storage:", uploadError);
        throw new Error(
          `Erro ao fazer upload da imagem: ${uploadError.message}`
        );
      }
      console.log("Upload bem-sucedido:", uploadData);

      // Obter URL pública da imagem
      const { data: publicUrlData } = supabase.storage
        .from(bucketName)
        .getPublicUrl(filePath);

      const publicUrl = publicUrlData.publicUrl;
      console.log("URL pública do avatar:", publicUrl);

      // Atualizar a URL do avatar no perfil do usuário na tabela 'profiles'
      const { error: updateProfileError } = await supabase
        .from("profiles")
        .update({ avatar_url: publicUrl, updated_at: new Date().toISOString() })
        .eq("user_id", user.id);

      if (updateProfileError) {
        console.error(
          "Erro ao atualizar avatar_url no perfil:",
          updateProfileError
        );
        throw new Error(
          `Erro ao salvar URL do avatar no perfil: ${updateProfileError.message}`
        );
      }
      console.log("URL do avatar atualizada no perfil.");

      // Opcional: Atualizar o avatar_url nos metadados do usuário Auth também
      const { error: updateAuthMetaError } = await supabase.auth.updateUser({
        data: { avatar_url: publicUrl },
      });
      if (updateAuthMetaError) {
        console.warn(
          "Erro ao atualizar avatar_url nos metadados do Auth:",
          updateAuthMetaError.message
        );
      }

      await fetchProfile(); // Recarrega os dados para mostrar o novo avatar
      toast.success("Foto de perfil atualizada com sucesso!", {
        duration: 3000,
      });
    } catch (error) {
      console.error("Erro capturado em handleAvatarUpload:", error);
      toast.error("Erro ao carregar imagem: " + error.message, {
        duration: 5000,
      });
    } finally {
      setLoading(false); // Finaliza o loading
    }
  };

  // --- Funções de Formatação e Auxiliares ---
  const formatCreatedAt = (dateString) => {
    if (!dateString) return "N/A";
    const date = new Date(dateString);
    return date.toLocaleDateString("pt-BR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    });
  };

  const getDisplayName = () => {
    if (profile) {
      // Combina first_name e last_name, trata casos nulos
      const firstName = profile.first_name || "";
      const lastName = profile.last_name || "";
      return `${firstName} ${lastName}`.trim() || "Usuário"; // Retorna "Usuário" se ambos estiverem vazios
    }
    return "Convidado";
  };

  const getAvatarFallback = () => {
    if (profile && profile.first_name) {
      return profile.first_name.substring(0, 1).toUpperCase();
    }
    return "US"; // User (se não houver first_name)
  };

  // --- Renderização Condicional de Carregamento ---
  if (loading && !profile) {
    // Mostra o carregamento apenas se não houver dados de perfil ainda
    return (
      <main className="p-4 max-w-6xl space-y-6 flex flex-col items-center justify-center h-screen">
        <h1 className="text-2xl font-bold text-black dark:text-white sm:text-3xl">
          Carregando perfil...
        </h1>
        {/* Você pode adicionar um spinner ou Skeleton aqui */}
        {/* Exemplo de spinner simples: */}
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900"></div>
      </main>
    );
  }

  return (
    <main className="p-4 max-w-6xl space-y-6">
      <h1 className="text-2xl font-bold text-black dark:text-white select-none sm:text-3xl">
        Meu Perfil
      </h1>

      {/* Seção de Resumo do Perfil com Avatar e Botão Editar */}
      <div className="bg-card text-card-foreground rounded-xl border p-6 shadow-sm flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6 w-full">
        <div className="relative group">
          <Avatar className="size-20 sm:size-24">
            <AvatarImage
              src={profile?.avatar_url || ""}
              alt={`${getDisplayName()} avatar`}
            />
            <AvatarFallback>{getAvatarFallback()}</AvatarFallback>
          </Avatar>
          {/* Botão de upload para o avatar (aparece ao passar o mouse) */}
          <label
            htmlFor="avatar-upload"
            className="absolute bottom-0 right-0 p-1 bg-primary text-primary-foreground rounded-full cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity"
          >
            <ImagePlus className="h-4 w-4" />
            <Input
              id="avatar-upload"
              type="file"
              accept="image/*" // Aceita apenas arquivos de imagem
              className="hidden"
              onChange={handleAvatarUpload}
              disabled={loading} // Desabilita enquanto está carregando/salvando
            />
          </label>
        </div>

        <div className="flex flex-col items-center sm:items-start text-center sm:text-left flex-grow">
          <h1 className="font-semibold text-xl sm:text-2xl">
            {getDisplayName()}
          </h1>
          <p className="text-sm text-accent-foreground font-medium">
            Plano base
          </p>
          <p className="text-sm text-muted-foreground whitespace-nowrap">
            Conta criada em {formatCreatedAt(profile?.created_at)}
          </p>
        </div>

        <div className="w-full flex justify-center sm:justify-end mt-4 sm:mt-0">
          <Button
            variant="outline"
            className="cursor-pointer gap-2"
            onClick={() => setIsEditingPersonal(!isEditingPersonal)}
          >
            <PencilLine className="h-4 w-4" />
            {isEditingPersonal ? "Cancelar Edição" : "Editar Perfil"}
          </Button>
        </div>
      </div>

      {/* Seção de Informações Pessoais */}
      <Card className="mb-5">
        <CardHeader className="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <CardTitle className="text-xl font-bold text-black dark:text-white select-none mb-4 sm:mb-0">
            Informações Pessoais
          </CardTitle>
          {/* Botão de edição para telas maiores (hidden sm:flex) */}
          <Button
            variant="outline"
            className="cursor-pointer gap-2 hidden sm:flex"
            onClick={() => setIsEditingPersonal(!isEditingPersonal)}
          >
            <PencilLine className="h-4 w-4" />
            {isEditingPersonal ? "Cancelar Edição" : "Editar Perfil"}
          </Button>
        </CardHeader>
        <CardContent className="space-y-6">
          <section className="grid gap-4 sm:grid-cols-2">
            <div>
              <Label
                htmlFor="firstName"
                className="text-lg font-semibold text-muted-foreground mb-1"
              >
                Primeiro Nome
              </Label>
              {isEditingPersonal ? (
                <Input
                  id="firstName"
                  value={newFirstName}
                  onChange={(e) => setNewFirstName(e.target.value)}
                  className="text-lg font-medium"
                  disabled={loading}
                />
              ) : (
                <p className="text-lg font-medium">
                  {profile?.first_name || "N/A"}
                </p>
              )}
            </div>
            <div>
              <Label
                htmlFor="lastName"
                className="text-lg font-semibold text-muted-foreground mb-1"
              >
                Sobrenome
              </Label>
              {isEditingPersonal ? (
                <Input
                  id="lastName"
                  value={newLastName}
                  onChange={(e) => setNewLastName(e.target.value)}
                  className="text-lg font-medium"
                  disabled={loading}
                />
              ) : (
                <p className="text-lg font-medium">
                  {profile?.last_name || "N/A"}
                </p>
              )}
            </div>
          </section>

          <section className="grid gap-4 sm:grid-cols-2">
            <div>
              <Label
                htmlFor="email"
                className="text-lg font-semibold text-muted-foreground mb-1"
              >
                Endereço de Email
              </Label>
              {isEditingPersonal ? (
                <Input
                  id="email"
                  type="email"
                  value={newEmail}
                  onChange={(e) => setNewEmail(e.target.value)}
                  className="text-lg font-medium"
                  disabled={loading}
                  // Considere desabilitar a edição direta aqui e guiar o usuário para um fluxo de "mudar email"
                  // disabled={true} // Desabilitaria a edição direta do email
                />
              ) : (
                <p className="text-lg font-medium">{profile?.email || "N/A"}</p>
              )}
            </div>
            <div>
              <Label
                htmlFor="phone"
                className="text-lg font-semibold text-muted-foreground mb-1"
              >
                Telefone
              </Label>
              {isEditingPersonal ? (
                <Input
                  id="phone"
                  type="tel"
                  value={newPhone}
                  onChange={(e) => setNewPhone(e.target.value)}
                  className="text-lg font-medium"
                  disabled={loading}
                />
              ) : (
                <p className="text-lg font-medium">{profile?.phone || "N/A"}</p>
              )}
            </div>
          </section>
          {isEditingPersonal && (
            <Button onClick={handleSavePersonal} disabled={loading}>
              {loading ? "Salvando..." : "Salvar Alterações"}
            </Button>
          )}
          {/* Botão de edição para telas menores (sm:hidden) */}
          <Button
            variant="outline"
            className="cursor-pointer gap-2 sm:hidden"
            onClick={() => setIsEditingPersonal(!isEditingPersonal)}
          >
            <PencilLine className="h-4 w-4" />
            {isEditingPersonal ? "Cancelar Edição" : "Editar Perfil"}
          </Button>
        </CardContent>
      </Card>

      {/* Seção do Plano (Manter estático por enquanto) */}
      <h2 className="text-xl font-bold text-black dark:text-white select-none">
        Outros
      </h2>

      <Card className="mb-5">
        <CardHeader className="flex flex-col sm:flex-row sm:items-center sm:justify-between">
          <CardTitle className="text-xl font-bold text-black dark:text-white select-none mb-4 sm:mb-0">
            Seu Plano
          </CardTitle>
          {/* Botão de edição do plano (hidden sm:flex) */}
          <Button
            variant="outline"
            className="cursor-pointer gap-2 hidden sm:flex"
            // onClick={() => setIsEditingPlan(!isEditingPlan)} // Habilitar quando tiver lógica de edição de plano
            disabled={true} // Desabilitado por enquanto
          >
            <PencilLine className="h-4 w-4" />
            Editar
          </Button>
        </CardHeader>
        <CardContent className="space-y-6">
          {/* Campos do plano - Atualmente estáticos */}
          <section className="grid gap-4 sm:grid-cols-2">
            <div>
              <h1 className="text-lg  font-semibold text-muted-foreground mb-1">
                Data de Vencimento
              </h1>
              <p className="text-lg font-medium">Data/Mês/Ano</p>
            </div>
            <div>
              <h1 className="text-lg  font-semibold text-muted-foreground mb-1">
                Forma de Pagamento
              </h1>
              <p className="text-lg font-medium">Cartão - Crédito *0982</p>
            </div>
          </section>

          <section className="grid gap-4 sm:grid-cols-2">
            <div>
              <h1 className="text-lg font-semibold text-muted-foreground mb-1">
                Plano
              </h1>
              <p className="text-lg font-medium">Premium</p>
            </div>
            <div>
              <h1 className="text-lg font-semibold text-muted-foreground mb-1">
                Valor
              </h1>
              <p className="text-lg font-medium">R$ 94,99/Mês</p>
            </div>
          </section>
          {/* Botão de edição do plano para telas menores (sm:hidden) */}
          <Button
            variant="outline"
            className="cursor-pointer gap-2 sm:hidden"
            disabled={true}
          >
            <PencilLine className="h-4 w-4" />
            Editar
          </Button>
        </CardContent>
      </Card>

      {/* Botão Excluir Conta */}
      <div>
        <Button className="text-xl font-bold text-red-400 select-none bg-red-800/20 cursor-pointer">
          Excluir Conta
        </Button>
      </div>
    </main>
  );
}
