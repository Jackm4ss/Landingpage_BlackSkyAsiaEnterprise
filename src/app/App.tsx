import { useCallback, useEffect, useState } from "react";
import { Navbar } from "./components/Navbar";
import { HeroSection } from "./components/HeroSection";
import { AboutSection } from "./components/AboutSection";
import { EventsSection } from "./components/EventsSection";
import { PortfolioSection } from "./components/PortfolioSection";
import { StatsSection } from "./components/StatsSection";
import { NewsSection } from "./components/NewsSection";
import { Footer } from "./components/Footer";
import { LoginPage } from "./pages/LoginPage";
import { RegisterPage } from "./pages/RegisterPage";
import { LoginSuccessPage } from "./pages/LoginSuccessPage";
import { ForgotPasswordPage } from "./pages/ForgotPasswordPage";
import { ResetPasswordPage } from "./pages/ResetPasswordPage";

const getPathname = () => window.location.pathname.replace(/\/$/, "") || "/";

function LandingPage() {
  return (
    <>
      <Navbar />
      <HeroSection />
      <AboutSection />
      <EventsSection />
      <PortfolioSection />
      <StatsSection />
      <NewsSection />
      <Footer />
    </>
  );
}

export default function App() {
  const [pathname, setPathname] = useState(getPathname);

  const navigate = useCallback((path: string) => {
    window.history.pushState(null, "", path);
    setPathname(getPathname());
    window.scrollTo({ top: 0, behavior: "auto" });
  }, []);

  useEffect(() => {
    const handlePopState = () => setPathname(getPathname());

    window.addEventListener("popstate", handlePopState);
    return () => window.removeEventListener("popstate", handlePopState);
  }, []);

  const renderPage = () => {
    if (pathname === "/login") return <LoginPage onNavigate={navigate} />;
    if (pathname === "/register") return <RegisterPage onNavigate={navigate} />;
    if (pathname === "/forgot-password") {
      return <ForgotPasswordPage onNavigate={navigate} />;
    }
    if (pathname === "/reset-password") {
      return <ResetPasswordPage onNavigate={navigate} />;
    }
    if (pathname === "/login/success") {
      return <LoginSuccessPage onNavigate={navigate} />;
    }

    return <LandingPage />;
  };

  return (
    <div
      style={{
        background: "#050505",
        minHeight: "100vh",
        fontFamily: "'Inter', sans-serif",
        scrollBehavior: "smooth",
      }}
    >
      {/* Global Scrollbar Styling */}
      <style>{`
        * {
          scrollbar-width: thin;
          scrollbar-color: rgba(168,85,247,0.3) transparent;
        }
        *::-webkit-scrollbar {
          width: 4px;
        }
        *::-webkit-scrollbar-track {
          background: transparent;
        }
        *::-webkit-scrollbar-thumb {
          background: rgba(168,85,247,0.3);
          border-radius: 2px;
        }
        ::selection {
          background: rgba(168,85,247,0.35);
          color: #fff;
        }
        html {
          scroll-behavior: smooth;
        }
      `}</style>

      {renderPage()}
    </div>
  );
}
