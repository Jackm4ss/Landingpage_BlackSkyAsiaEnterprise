import { Navbar } from "../components/Navbar";
import { HeroSection } from "../components/HeroSection";
import { AboutSection } from "../components/AboutSection";
import { EventsSection } from "../components/EventsSection";
import { PortfolioSection } from "../components/PortfolioSection";
import { StatsSection } from "../components/StatsSection";
import { NewsSection } from "../components/NewsSection";
import { Footer } from "../components/Footer";

export function LandingPage() {
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
