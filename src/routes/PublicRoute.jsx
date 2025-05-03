import { Navigate } from "react-router-dom";
import useAuth from "@/hooks/useAuth";

export default function PublicRoute({ children }) {
  const { user, loading } = useAuth();

  if (loading) return null;

  return user ? <Navigate to="/dashboard" /> : children;
}
