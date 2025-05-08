import { BiObjectsVerticalBottom } from "react-icons/bi";
import Header from "@/components/Header";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";

export default function Dashboard() {
  return (
    <>
      <Header />
      <main className="p-4">
        <section className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <Card>
            <CardHeader>
              <div className="flex items-center justify-center">
                <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
                  Produtos Totais
                </CardTitle>
                <BiObjectsVerticalBottom className="ml-auto w-4 h-4" />
              </div>
              <CardDescription>Todos os produtos do estoque</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-base sm:text-lg font-bold">1208</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <div className="flex items-center justify-center">
                <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
                  Produtos Totais
                </CardTitle>
                <BiObjectsVerticalBottom className="ml-auto w-4 h-4" />
              </div>
              <CardDescription>Todos os produtos do estoque</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-base sm:text-lg font-bold">1208</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <div className="flex items-center justify-center">
                <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
                  Produtos Totais
                </CardTitle>
                <BiObjectsVerticalBottom className="ml-auto w-4 h-4" />
              </div>
              <CardDescription>Todos os produtos do estoque</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-base sm:text-lg font-bold">1208</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <div className="flex items-center justify-center">
                <CardTitle className="text-lg sm:text-xl text-black dark:text-white select-none">
                  Produtos Totais
                </CardTitle>
                <BiObjectsVerticalBottom className="ml-auto w-4 h-4" />
              </div>
              <CardDescription>Todos os produtos do estoque</CardDescription>
            </CardHeader>
            <CardContent>
              <p className="text-base sm:text-lg font-bold">1208</p>
            </CardContent>
          </Card>
        </section>
      </main>
    </>
  );
}
