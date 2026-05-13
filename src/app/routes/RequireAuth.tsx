import type { ReactNode } from "react";
import { Navigate, useLocation } from "react-router";
import { useCurrentUser } from "../auth/auth-queries";

type RequireAuthProps = {
  children: ReactNode;
};

export function RequireAuth({ children }: RequireAuthProps) {
  const location = useLocation();
  const { data: user, isLoading } = useCurrentUser();

  if (isLoading) {
    return null;
  }

  if (!user) {
    return <Navigate to="/login" replace state={{ from: location.pathname }} />;
  }

  return children;
}
