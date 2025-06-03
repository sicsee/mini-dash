import { Card, CardHeader, CardTitle, CardContent, CardDescription } from "@/components/ui/card";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { PencilLine } from "lucide-react";


export default function Settings() {
  return (
    <main className="p-4 w-6xl mx-auto space-y-5">

      <h1 className="text-lg sm:text-xl text-black dark:text-white select-none font-bold">Meu Perfil</h1>

      <div className="bg-card text-card-foreground gap-6 rounded-xl border p-6 shadow-sm flex items-center">
  <Avatar className="size-18">
    <AvatarImage src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTVs3UptN-mFQSO7bL-NysF0JxdXxwLXhAN4g&s" />
    <AvatarFallback>ZE
    </AvatarFallback>
  </Avatar>
  
  <div className="flex flex-col">
    <h1 className="font-semibold text-lg">Zé</h1>
    <p className="text-sm text-accent-foreground font-medium">
      Plano base
    </p>
    <p className="text-sm text-muted-foreground text-nowrap">
      Conta criada em 21/02/2025
    </p>
  </div>
  <div className="w-full flex justify-end">
    <Button variant="outline" className="cursor-pointer">
      <PencilLine />
      Editar
    </Button>
  </div>
</div>


     <Card className="mb-5">
        <CardHeader>
          <div className="flex items-center">
            <div className="flex items-center justify-between w-full">
              <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
                Informações Pessoais
              </CardTitle>
              <Button variant="outline" className="cursor-pointer">
                <PencilLine />
                Editar
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent className="space-y-3 sm:space-y-4">
          <section className="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:max-w-2/3 lg:max-w-1/2">
            <div className="sm:w-1/2">
              <h1 className="text-md sm:text-lg font-semibold text-muted-foreground">Primeiro Nome</h1>
              <p className="text-md font-medium">Zé</p>
            </div>
            <div className="sm:w-1/2">
              <h1 className="text-md sm:text-lg font-semibold text-muted-foreground">Sobrenome</h1>
              <p className="text-md font-medium">Felipe</p>
            </div>
          </section>
  
          <section className="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:justify-between sm:max-w-2/3 lg:max-w-1/2">
            <div className="sm:w-1/2">
              <h1 className="text-md sm:text-lg font-semibold text-muted-foreground">Endereço de Email</h1>
              <p className="text-md font-medium">sddsvirginia@email.com</p>
            </div>
            <div className="sm:w-1/2">
              <h1 className="text-md sm:text-lg font-semibold text-muted-foreground">Telefone</h1>
              <p className="text-md font-medium">+55 (31) 91234-5678</p>
            </div>
          </section>
        </CardContent>
      </Card>

      <h1>
        Outros
      </h1>

      <div>
        <p>
          Configurações de Plano
        </p>
      </div>


      <h1 className="text-red-500">Delete Account</h1>
    </main>
  );
}
