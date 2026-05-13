import { Navigate } from "react-router";

export function LoginSuccessPage() {
  return <Navigate to="/dashboard" replace />;
}
