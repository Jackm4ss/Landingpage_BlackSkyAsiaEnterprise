import { useEffect, useState } from "react";
import { getCurrentSession, type AuthSession } from "../auth/session";
import { AuthShell } from "./AuthShell";

type LoginSuccessPageProps = {
  onNavigate: (path: string) => void;
};

export function LoginSuccessPage({ onNavigate }: LoginSuccessPageProps) {
  const [session] = useState<AuthSession | null>(() => getCurrentSession());

  useEffect(() => {
    if (!session) {
      onNavigate("/login");
    }
  }, [onNavigate, session]);

  if (!session) return null;

  return (
    <AuthShell>
      <div className="auth-success" role="status" aria-live="polite">
        <p className="auth-success__text">OK login user berhasil</p>
      </div>
    </AuthShell>
  );
}
