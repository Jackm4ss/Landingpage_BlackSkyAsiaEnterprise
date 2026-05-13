import { useState, useEffect } from "react";
import { motion, AnimatePresence } from "motion/react";
import { Menu, X } from "lucide-react";
import logo from "../../assets/LOGO.png";

const navLinks = [
  { label: "ABOUT", href: "/#about" },
  { label: "DISCOVER", href: "/discover" },
  { label: "EVENTS", href: "/#events" },
  { label: "WORKS", href: "/#works" },
  { label: "BLOG", href: "/blog" },
  { label: "NEWS", href: "/#news" },
  { label: "CONTACT", href: "/#contact" },
];

export function Navbar() {
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 50);
    onScroll();
    window.addEventListener("scroll", onScroll);
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  const handleNav = (href: string) => {
    setMobileOpen(false);

    if (href.startsWith("/#")) {
      if (window.location.pathname !== "/") {
        window.location.assign(href);
        return;
      }

      const el = document.querySelector(href.replace("/", ""));
      if (el) el.scrollIntoView({ behavior: "smooth" });
      return;
    }

    if (href.startsWith("#")) {
      const el = document.querySelector(href);
      if (el) el.scrollIntoView({ behavior: "smooth" });
      return;
    }

    window.location.assign(href);
  };

  const goToLogin = () => {
    setMobileOpen(false);
    window.location.assign("/login");
  };

  return (
    <>
      <motion.nav
        initial={{ y: -80, opacity: 0 }}
        animate={{ y: 0, opacity: 1 }}
        transition={{ duration: 0.8, ease: [0.22, 1, 0.36, 1] }}
        className="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
        style={{
          background: scrolled
            ? "linear-gradient(135deg, rgba(7,18,33,0.62), rgba(4,8,16,0.46))"
            : "linear-gradient(to bottom, rgba(5,5,5,0.7) 0%, transparent 100%)",
          backdropFilter: scrolled ? "blur(24px) saturate(160%)" : "none",
          WebkitBackdropFilter: scrolled ? "blur(24px) saturate(160%)" : "none",
          borderBottom: scrolled ? "1px solid rgba(255,255,255,0.14)" : "none",
          boxShadow: scrolled
            ? "0 18px 50px rgba(0,0,0,0.34), inset 0 1px 0 rgba(255,255,255,0.08)"
            : "none",
        }}
      >
        <div className="max-w-[1600px] mx-auto px-8 py-5 flex items-center justify-between">
          {/* Logo */}
          <button
            onClick={() => {
              if (window.location.pathname === "/") {
                window.scrollTo({ top: 0, behavior: "smooth" });
                return;
              }

              window.location.assign("/");
            }}
            className="flex items-center group"
            aria-label="Black Sky Enterprise"
          >
            <img
              src={logo}
              alt="Black Sky Enterprise"
              className="w-auto"
              style={{ height: "clamp(52px, 7vw, 72px)" }}
            />
          </button>

          {/* Desktop Nav */}
          <div className="hidden lg:flex items-center gap-10">
            {navLinks.map((link) => (
              <button
                key={link.label}
                onClick={() => handleNav(link.href)}
                className="relative group"
                style={{
                  fontFamily: "'Barlow Condensed', sans-serif",
                  fontWeight: 600,
                  fontSize: "13px",
                  letterSpacing: "0.2em",
                  color: "rgba(255,255,255,0.65)",
                  background: "none",
                  border: "none",
                  cursor: "pointer",
                  transition: "color 0.3s",
                }}
                onMouseEnter={(e) =>
                  (e.currentTarget.style.color = "rgba(255,255,255,1)")
                }
                onMouseLeave={(e) =>
                  (e.currentTarget.style.color = "rgba(255,255,255,0.65)")
                }
              >
                {link.label}
                <span
                  className="absolute bottom-[-4px] left-0 w-0 group-hover:w-full transition-all duration-300"
                  style={{ height: "1px", background: "#A855F7" }}
                />
              </button>
            ))}
          </div>

          {/* CTA + Mobile */}
          <div className="flex items-center gap-4">
            <button
              onClick={goToLogin}
              className="hidden lg:flex items-center px-2 py-3 transition-colors duration-300"
              style={{
                background: "none",
                border: "none",
                color: "rgba(255,255,255,0.72)",
                cursor: "pointer",
                fontFamily: "'Barlow Condensed', sans-serif",
                fontSize: "13px",
                fontWeight: 700,
                letterSpacing: 0,
              }}
            >
              LOGIN
            </button>
            <button
              onClick={() => handleNav("/discover")}
              className="hidden lg:flex items-center gap-2 px-6 py-3 transition-all duration-300 hover:opacity-90"
              style={{
                background: "#0EA5E9",
                fontFamily: "'Barlow Condensed', sans-serif",
                fontWeight: 700,
                fontSize: "12px",
                letterSpacing: "0.25em",
                color: "#fff",
                border: "none",
                cursor: "pointer",
                clipPath: "polygon(8px 0%, 100% 0%, calc(100% - 8px) 100%, 0% 100%)",
              }}
            >
              GET TICKETS
            </button>
            <button
              className="lg:hidden text-white p-1"
              onClick={() => setMobileOpen(true)}
              style={{ background: "none", border: "none", cursor: "pointer" }}
            >
              <Menu size={24} />
            </button>
          </div>
        </div>
      </motion.nav>

      {/* Mobile Menu */}
      <AnimatePresence>
        {mobileOpen && (
          <motion.div
            initial={{ x: "100%" }}
            animate={{ x: 0 }}
            exit={{ x: "100%" }}
            transition={{ duration: 0.4, ease: [0.22, 1, 0.36, 1] }}
            className="fixed inset-0 z-[100] flex flex-col"
            style={{ background: "#050505" }}
          >
            <div className="flex items-center justify-between px-8 py-5">
              <img
                src={logo}
                alt="Black Sky Enterprise"
                className="w-auto"
                style={{ height: "64px" }}
              />
              <button
                onClick={() => setMobileOpen(false)}
                style={{
                  background: "none",
                  border: "none",
                  cursor: "pointer",
                  color: "#fff",
                }}
              >
                <X size={28} />
              </button>
            </div>
            <div className="flex-1 flex flex-col justify-center px-8 gap-2">
              {navLinks.map((link, i) => (
                <motion.button
                  key={link.label}
                  initial={{ x: 60, opacity: 0 }}
                  animate={{ x: 0, opacity: 1 }}
                  transition={{ delay: i * 0.08, duration: 0.4 }}
                  onClick={() => handleNav(link.href)}
                  className="text-left py-4"
                  style={{
                    fontFamily: "'Barlow Condensed', sans-serif",
                    fontWeight: 800,
                    fontSize: "clamp(2.5rem, 8vw, 4rem)",
                    letterSpacing: "0.05em",
                    color: "rgba(255,255,255,0.9)",
                    background: "none",
                    border: "none",
                    borderBottom: "1px solid rgba(255,255,255,0.08)",
                    cursor: "pointer",
                  }}
                >
                  {link.label}
                </motion.button>
              ))}
            </div>
            <div className="px-8 pb-12">
              <button
                onClick={goToLogin}
                className="w-full py-4 mb-3"
                style={{
                  background: "rgba(255,255,255,0.06)",
                  fontFamily: "'Barlow Condensed', sans-serif",
                  fontWeight: 700,
                  fontSize: "14px",
                  letterSpacing: 0,
                  color: "#fff",
                  border: "1px solid rgba(255,255,255,0.14)",
                  cursor: "pointer",
                }}
              >
                LOGIN
              </button>
              <button
                onClick={() => handleNav("/discover")}
                className="w-full py-4"
                style={{
                  background: "#0EA5E9",
                  fontFamily: "'Barlow Condensed', sans-serif",
                  fontWeight: 700,
                  fontSize: "14px",
                  letterSpacing: 0,
                  color: "#fff",
                  border: "none",
                  cursor: "pointer",
                }}
              >
                GET TICKETS
              </button>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
