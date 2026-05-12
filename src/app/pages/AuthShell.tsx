import type { CSSProperties, ReactNode } from "react";
import logo from "../../assets/LOGO.png";
import heroImage from "../../assets/hero-concert-bg.png";
import { cn } from "../components/ui/utils";
import "./AuthPages.css";

type AuthShellProps = {
  children: ReactNode;
  panelSize?: "default" | "wide";
};

export function AuthShell({ children, panelSize = "default" }: AuthShellProps) {
  return (
    <main
      className="auth-page"
      style={{ "--auth-bg-image": `url(${heroImage})` } as CSSProperties}
    >
      <div className="auth-page__background" aria-hidden="true" />
      <section className="auth-page__content" aria-label="Authentication">
        <div
          className={cn(
            "auth-page__panel",
            panelSize === "wide" && "auth-page__panel--wide",
          )}
        >
          <a className="auth-page__logo" href="/" aria-label="Black Sky Enterprise">
            <img src={logo} alt="" aria-hidden="true" />
          </a>
          {children}
        </div>
      </section>
    </main>
  );
}
