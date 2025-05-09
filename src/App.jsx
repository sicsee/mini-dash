import { Routes, Route } from "react-router-dom";

import Login from "@/pages/auth/Login";
import Signup from "@/pages/auth/Signup";
import DashboardLayout from "@/pages/Dashboard/Page";
import LandingPage from "@/pages/Landing";

import PublicRoute from "@/routes/PublicRoute";
import PrivateRoute from "@/routes/PrivateRoute";
import { Clientes } from "./pages/Dashboard/clientes/page";
import { Estoque } from "./pages/Dashboard/estoque/page";
import { Produtos } from "./pages/Dashboard/produtos/page";
import { Settings } from "./pages/Dashboard/configuracao/page";
import DashboardHome from "./pages/Dashboard/home/page";

export default function App() {
  return (
    <Routes>
      <Route path="/" element={<LandingPage />} />

      <Route
        path="/login"
        element={
          <PublicRoute>
            <Login />
          </PublicRoute>
        }
      />

      <Route
        path="/signup"
        element={
          <PublicRoute>
            <Signup />
          </PublicRoute>
        }
      />

      <Route
        path="/dashboard"
        element={
          <PrivateRoute>
            <DashboardLayout />
          </PrivateRoute>
        }
      >
        <Route index element={<DashboardHome />} />
        <Route path="estoque" element={<Estoque />} />
        <Route path="clientes" element={<Clientes />} />
        <Route path="produtos" element={<Produtos />} />
        <Route path="settings" element={<Settings />} />
      </Route>
    </Routes>
  );
}
