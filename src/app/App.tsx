import { Navbar } from "./components/Navbar";
import { HeroSection } from "./components/HeroSection";
import { AboutSection } from "./components/AboutSection";
import { EventsSection } from "./components/EventsSection";
import { PortfolioSection } from "./components/PortfolioSection";
import { StatsSection } from "./components/StatsSection";
import { NewsSection } from "./components/NewsSection";
import { Footer } from "./components/Footer";

export default function App() {
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

      <Navbar />
      <HeroSection />
      <AboutSection />
      <EventsSection />
      <PortfolioSection />
      <StatsSection />
      <NewsSection />
      <Footer />
    </div>
  );
}
