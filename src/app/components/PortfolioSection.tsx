import { useState, useRef } from "react";
import { motion, useInView } from "motion/react";
import { ArrowUpRight, ArrowRight } from "lucide-react";
import GradientText from "./GradientText";

const img1 =
  "https://images.unsplash.com/photo-1722506224957-7695c097ff88?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc3QlMjBwZXJmb3JtaW5nJTIwbXVzaWMlMjBzdGFnZSUyMHB1cnBsZSUyMGJsdWUlMjBsaWdodHN8ZW58MXx8fHwxNzc4MjM5NDIwfDA&ixlib=rb-4.1.0&q=80&w=1080";
const img2 =
  "https://images.unsplash.com/photo-1760218832333-5b16f66ebc1a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjcm93ZCUyMGNvbmNlcnQlMjBoYW5kcyUyMHVwJTIwc2lsaG91ZXR0ZSUyMHN0YWdlJTIwbGlnaHRzfGVufDF8fHx8MTc3ODIzOTQyMXww&ixlib=rb-4.1.0&q=80&w=1080";
const img3 =
  "https://images.unsplash.com/photo-1590699306463-dbb2b13c0ca0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtdXNpYyUyMGVudGVydGFpbm1lbnQlMjBlZGl0b3JpYWwlMjBwaG90b2dyYXBoeSUyMGRhcmt8ZW58MXx8fHwxNzc4MjM5NDIxfDA&ixlib=rb-4.1.0&q=80&w=1080";
const img4 =
  "https://images.unsplash.com/photo-1765224747205-3c9c23f0553c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb25jZXJ0JTIwc3RhZ2UlMjBsaWdodGluZyUyMGNyb3dkJTIwZGFyayUyMGRyYW1hdGljfGVufDF8fHx8MTc3ODIzOTQxNnww&ixlib=rb-4.1.0&q=80&w=1080";
const img5 =
  "https://images.unsplash.com/photo-1774112560513-38b6ec3ba898?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVjdHJvbmljJTIwbXVzaWMlMjBmZXN0aXZhbCUyMGNvbG9yZnVsJTIwbGFzZXIlMjBzaG93fGVufDF8fHx8MTc3ODIzOTQxN3ww&ixlib=rb-4.1.0&q=80&w=1080";

interface Work {
  id: number;
  title: string;
  category: string;
  year: string;
  location: string;
  accentColor: string;
  image: string;
  description: string;
  // bento placement
  gridCol: string;
  gridRow: string;
  type: "feature" | "tall" | "square" | "banner";
}

// Bento layout on 3-column grid, 3-row grid:
// [ W1: col 1-2, row 1 — WIDE FEATURE ] [ W2: col 3, row 1-2 — TALL PORTRAIT ]
// [ W3: col 1, row 2 — SQUARE         ] [ W4: col 2, row 2 — SQUARE          ] [ W2 ]
// [ W5: col 1-3, row 3 — FULL BANNER  ]
const works: Work[] = [
  {
    id: 1,
    title: "NEON PULSE",
    category: "Arena Concert",
    year: "2025",
    location: "Kuala Lumpur",
    accentColor: "#A855F7",
    image: img1,
    description: "A sold-out 3-night arena run — 60,000+ fans, world-class production.",
    gridCol: "1 / 3",
    gridRow: "1",
    type: "feature",
  },
  {
    id: 2,
    title: "TIDAL WAVE",
    category: "Music Festival",
    year: "2025",
    location: "Penang",
    accentColor: "#0EA5E9",
    image: img2,
    description: "Malaysia's largest beachside music festival — 3 stages, 2 days.",
    gridCol: "3",
    gridRow: "1 / 3",
    type: "tall",
  },
  {
    id: 3,
    title: "DARK MATTER",
    category: "Media Production",
    year: "2024",
    location: "Regional SEA",
    accentColor: "#E11D48",
    image: img3,
    description: "Original docu-series on SEA's underground music culture.",
    gridCol: "1",
    gridRow: "2",
    type: "square",
  },
  {
    id: 4,
    title: "ULTRAVIOLET",
    category: "Festival",
    year: "2024",
    location: "Singapore",
    accentColor: "#FFB700",
    image: img4,
    description: "Groundbreaking electronic music with real-time visual sync.",
    gridCol: "2",
    gridRow: "2",
    type: "square",
  },
  {
    id: 5,
    title: "VOLTAGE — THE ASIA TOUR",
    category: "Arena Tour",
    year: "2024",
    location: "8 Cities · SEA",
    accentColor: "#F97316",
    image: img5,
    description: "The largest coordinated arena tour in Southeast Asian history — 8 cities, 22 sold-out nights, 2 million+ in attendance.",
    gridCol: "1 / 4",
    gridRow: "3",
    type: "banner",
  },
];

const categories = ["ALL", "CONCERT", "FESTIVAL", "MEDIA"];
const cardDescriptionColor = "rgba(235,245,255,0.78)";
const cardMetaColor = "rgba(226,238,255,0.68)";

// FEATURE card: wide, image with overlay, large title
function FeatureCard({ work, delay }: { work: Work; delay: number }) {
  const [hovered, setHovered] = useState(false);
  return (
    <motion.div
      initial={{ opacity: 0, y: 24 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-60px" }}
      transition={{ duration: 0.7, delay, ease: [0.22, 1, 0.36, 1] }}
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      className="relative overflow-hidden cursor-pointer h-full"
      style={{
        border: `1px solid ${hovered ? work.accentColor + "55" : "rgba(255,255,255,0.06)"}`,
        transition: "border-color 0.4s",
      }}
    >
      <img
        src={work.image}
        alt={work.title}
        className="w-full h-full object-cover"
        style={{
          transform: hovered ? "scale(1.05)" : "scale(1)",
          transition: "transform 0.8s ease",
          position: "absolute",
          inset: 0,
        }}
      />
      {/* Gradient overlay */}
      <div className="absolute inset-0" style={{ background: "linear-gradient(180deg, rgba(5,5,5,0.22) 0%, rgba(5,5,5,0.48) 48%, rgba(5,5,5,0.94) 100%), linear-gradient(135deg, rgba(5,5,5,0.8) 0%, rgba(5,5,5,0.18) 50%, rgba(5,5,5,0.62) 100%)" }} />
      {/* Glow */}
      <motion.div
        animate={{ opacity: hovered ? 1 : 0 }}
        className="absolute inset-0"
        style={{ background: `radial-gradient(circle at 20% 80%, ${work.accentColor}20 0%, transparent 65%)` }}
      />

      {/* Arrow */}
      <motion.div
        animate={{ opacity: hovered ? 1 : 0, x: hovered ? 0 : 8, y: hovered ? 0 : -8 }}
        transition={{ duration: 0.25 }}
        className="absolute top-5 right-5 flex items-center justify-center"
        style={{ width: 40, height: 40, background: work.accentColor }}
      >
        <ArrowUpRight size={18} color="#fff" />
      </motion.div>

      {/* Category */}
      <div
        className="absolute top-5 left-5"
        style={{ background: "rgba(5,5,5,0.75)", backdropFilter: "blur(8px)", padding: "5px 12px", border: `1px solid ${work.accentColor}30` }}
      >
        <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "10px", letterSpacing: "0.3em", color: work.accentColor }}>
          {work.category.toUpperCase()}
        </span>
      </div>

      {/* Content bottom */}
      <div className="absolute bottom-0 left-0 right-0 p-7">
        <div style={{ width: hovered ? 48 : 28, height: 2, background: `linear-gradient(90deg, ${work.accentColor}, transparent)`, transition: "width 0.4s", marginBottom: "12px" }} />
        <h3 style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 900, fontSize: "clamp(2rem, 3vw, 3.2rem)", lineHeight: 0.92, letterSpacing: "-0.01em", color: "#fff", textTransform: "uppercase", marginBottom: "8px" }}>
          {work.title}
        </h3>
        <p style={{ fontFamily: "'Barlow', sans-serif", fontWeight: 400, fontSize: "0.82rem", color: cardDescriptionColor, marginBottom: "12px" }}>
          {work.description}
        </p>
        <div className="flex items-center justify-between">
          <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "11px", letterSpacing: "0.2em", color: cardMetaColor }}>
            {work.location.toUpperCase()}
          </span>
          <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "11px", letterSpacing: "0.2em", color: work.accentColor }}>
            {work.year}
          </span>
        </div>
      </div>
    </motion.div>
  );
}

// TALL card: portrait format, fills full height
function TallCard({ work, delay }: { work: Work; delay: number }) {
  const [hovered, setHovered] = useState(false);
  return (
    <motion.div
      initial={{ opacity: 0, x: 24 }}
      whileInView={{ opacity: 1, x: 0 }}
      viewport={{ once: true, margin: "-60px" }}
      transition={{ duration: 0.8, delay, ease: [0.22, 1, 0.36, 1] }}
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      className="relative overflow-hidden cursor-pointer h-full"
      style={{
        border: `1px solid ${hovered ? work.accentColor + "55" : "rgba(255,255,255,0.06)"}`,
        transition: "border-color 0.4s",
      }}
    >
      <img
        src={work.image}
        alt={work.title}
        className="w-full h-full object-cover"
        style={{ transform: hovered ? "scale(1.06)" : "scale(1)", transition: "transform 0.9s ease", position: "absolute", inset: 0 }}
      />
      <div className="absolute inset-0" style={{ background: "linear-gradient(180deg, rgba(5,5,5,0.28) 0%, rgba(5,5,5,0.55) 45%, rgba(5,5,5,0.96) 100%)" }} />
      <motion.div animate={{ opacity: hovered ? 1 : 0 }} className="absolute inset-0" style={{ background: `radial-gradient(circle at 50% 90%, ${work.accentColor}28 0%, transparent 65%)` }} />

      {/* Arrow */}
      <motion.div
        animate={{ opacity: hovered ? 1 : 0, x: hovered ? 0 : 8, y: hovered ? 0 : -8 }}
        transition={{ duration: 0.25 }}
        className="absolute top-5 right-5 flex items-center justify-center"
        style={{ width: 38, height: 38, background: work.accentColor }}
      >
        <ArrowUpRight size={16} color="#fff" />
      </motion.div>

      {/* Category */}
      <div
        className="absolute top-5 left-5"
        style={{ background: "rgba(5,5,5,0.8)", backdropFilter: "blur(8px)", padding: "4px 10px" }}
      >
        <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "10px", letterSpacing: "0.28em", color: work.accentColor }}>
          {work.category.toUpperCase()}
        </span>
      </div>

      {/* Bottom content */}
      <div className="absolute bottom-0 left-0 right-0 p-6">
        <div style={{ width: hovered ? 40 : 20, height: 2, background: work.accentColor, transition: "width 0.4s", marginBottom: "10px" }} />
        <h3 style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 900, fontSize: "clamp(1.6rem, 2.2vw, 2.4rem)", lineHeight: 0.93, letterSpacing: "-0.01em", color: "#fff", textTransform: "uppercase", marginBottom: "8px" }}>
          {work.title}
        </h3>
        <p style={{ fontFamily: "'Barlow', sans-serif", fontWeight: 400, fontSize: "0.78rem", color: cardDescriptionColor, lineHeight: 1.65, marginBottom: "12px" }}>
          {work.description}
        </p>
        <div className="flex items-center gap-2">
          <div style={{ width: 6, height: 6, borderRadius: "50%", background: work.accentColor }} />
          <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "10px", letterSpacing: "0.25em", color: cardMetaColor }}>
            {work.location.toUpperCase()} — {work.year}
          </span>
        </div>
      </div>
    </motion.div>
  );
}

// SQUARE card: image top + content bottom
function SquareCard({ work, delay }: { work: Work; delay: number }) {
  const [hovered, setHovered] = useState(false);
  return (
    <motion.div
      initial={{ opacity: 0, y: 24 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-60px" }}
      transition={{ duration: 0.7, delay, ease: [0.22, 1, 0.36, 1] }}
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      className="relative overflow-hidden cursor-pointer flex flex-col h-full"
      style={{
        background: "#0A0A0A",
        border: `1px solid ${hovered ? work.accentColor + "50" : "rgba(255,255,255,0.06)"}`,
        transition: "border-color 0.4s",
      }}
    >
      {/* Image top ~55% */}
      <div className="relative overflow-hidden" style={{ flex: "0 0 58%" }}>
        <img
          src={work.image}
          alt={work.title}
          className="w-full h-full object-cover"
          style={{ transform: hovered ? "scale(1.07)" : "scale(1)", transition: "transform 0.8s ease" }}
        />
        <div className="absolute inset-0" style={{ background: "linear-gradient(180deg, transparent 40%, rgba(10,10,10,0.97) 100%)" }} />
        <motion.div animate={{ opacity: hovered ? 1 : 0 }} className="absolute inset-0" style={{ background: `radial-gradient(circle at 50% 80%, ${work.accentColor}20 0%, transparent 65%)` }} />

        {/* Arrow */}
        <motion.div
          animate={{ opacity: hovered ? 1 : 0, x: hovered ? 0 : 8, y: hovered ? 0 : -8 }}
          transition={{ duration: 0.25 }}
          className="absolute top-4 right-4 flex items-center justify-center"
          style={{ width: 34, height: 34, background: work.accentColor }}
        >
          <ArrowUpRight size={14} color="#fff" />
        </motion.div>

        {/* Category badge */}
        <div className="absolute top-4 left-4" style={{ background: work.accentColor, padding: "3px 10px" }}>
          <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "9px", letterSpacing: "0.3em", color: "#fff" }}>
            {work.category.toUpperCase()}
          </span>
        </div>
      </div>

      {/* Content bottom */}
      <div className="flex-1 flex flex-col justify-between p-5">
        <div>
          <h3 style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 900, fontSize: "clamp(1.3rem, 2vw, 1.9rem)", lineHeight: 0.94, letterSpacing: "-0.01em", color: "#fff", textTransform: "uppercase", marginBottom: "6px" }}>
            {work.title}
          </h3>
          <p style={{ fontFamily: "'Barlow', sans-serif", fontWeight: 400, fontSize: "0.75rem", color: cardDescriptionColor, lineHeight: 1.65 }}>
            {work.description}
          </p>
        </div>
        <div
          className="flex items-center gap-2 mt-3 pt-3"
          style={{ borderTop: "1px solid rgba(255,255,255,0.05)" }}
        >
          <div style={{ width: 5, height: 5, borderRadius: "50%", background: work.accentColor }} />
          <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "10px", letterSpacing: "0.22em", color: cardMetaColor }}>
            {work.location.toUpperCase()}
          </span>
          <span style={{ marginLeft: "auto", fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "11px", color: work.accentColor }}>
            {work.year}
          </span>
        </div>
      </div>
    </motion.div>
  );
}

// BANNER card: full-width cinematic strip
function BannerCard({ work, delay }: { work: Work; delay: number }) {
  const [hovered, setHovered] = useState(false);
  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-60px" }}
      transition={{ duration: 0.8, delay, ease: [0.22, 1, 0.36, 1] }}
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      className="relative overflow-hidden cursor-pointer h-full"
      style={{
        border: `1px solid ${hovered ? work.accentColor + "55" : "rgba(255,255,255,0.07)"}`,
        transition: "border-color 0.4s",
      }}
    >
      <img
        src={work.image}
        alt={work.title}
        className="w-full h-full object-cover"
        style={{ transform: hovered ? "scale(1.04)" : "scale(1)", transition: "transform 1s ease", position: "absolute", inset: 0 }}
      />
      <div className="absolute inset-0" style={{ background: "linear-gradient(180deg, rgba(5,5,5,0.2) 0%, rgba(5,5,5,0.88) 100%), linear-gradient(90deg, rgba(5,5,5,0.96) 0%, rgba(5,5,5,0.58) 50%, rgba(5,5,5,0.78) 100%)" }} />
      <motion.div animate={{ opacity: hovered ? 1 : 0 }} className="absolute inset-0" style={{ background: `radial-gradient(circle at 30% 50%, ${work.accentColor}18 0%, transparent 60%)` }} />

      {/* Content — horizontal layout */}
      <div className="relative z-10 h-full flex items-center px-10 gap-12">
        {/* Left: Number + title */}
        <div className="flex-shrink-0">
          <div className="flex items-center gap-4 mb-3">
            <div style={{ background: work.accentColor, padding: "4px 12px" }}>
              <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "10px", letterSpacing: "0.3em", color: "#fff" }}>
                {work.category.toUpperCase()}
              </span>
            </div>
            <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "11px", letterSpacing: "0.2em", color: work.accentColor }}>
              {work.year}
            </span>
          </div>
          <h3 style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 900, fontSize: "clamp(2rem, 3.5vw, 4rem)", lineHeight: 0.9, letterSpacing: "-0.02em", color: "#fff", textTransform: "uppercase" }}>
            {work.title}
          </h3>
        </div>

        {/* Divider */}
        <div style={{ width: 1, height: 80, background: "rgba(255,255,255,0.1)", flexShrink: 0 }} />

        {/* Middle: desc + location */}
        <div className="flex-1 max-w-sm">
          <p style={{ fontFamily: "'Barlow', sans-serif", fontWeight: 400, fontSize: "0.9rem", color: cardDescriptionColor, lineHeight: 1.7 }}>
            {work.description}
          </p>
          <div className="flex items-center gap-2 mt-3">
            <div style={{ width: 5, height: 5, borderRadius: "50%", background: work.accentColor }} />
            <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 700, fontSize: "11px", letterSpacing: "0.25em", color: cardMetaColor }}>
              {work.location.toUpperCase()}
            </span>
          </div>
        </div>

        {/* Right: CTA */}
        <div className="flex-shrink-0 ml-auto">
          <motion.div
            animate={{
              background: hovered
                ? `linear-gradient(135deg, ${work.accentColor}, ${work.accentColor}bb)`
                : "transparent",
              borderColor: hovered ? work.accentColor : "rgba(255,255,255,0.15)",
            }}
            className="flex items-center gap-3 px-7 py-4"
            style={{
              border: "1px solid rgba(255,255,255,0.15)",
              fontFamily: "'Barlow Condensed', sans-serif",
              fontWeight: 700,
              fontSize: "11px",
              letterSpacing: "0.28em",
              color: "#fff",
              cursor: "pointer",
            }}
          >
            VIEW PROJECT
            <ArrowRight
              size={13}
              style={{ transform: hovered ? "translateX(4px)" : "translateX(0)", transition: "transform 0.3s" }}
            />
          </motion.div>
        </div>
      </div>
    </motion.div>
  );
}

export function PortfolioSection() {
  const [activeFilter, setActiveFilter] = useState("ALL");
  const sectionRef = useRef<HTMLDivElement>(null);
  const isInView = useInView(sectionRef, { once: true, margin: "-100px" });

  return (
    <section
      id="works"
      ref={sectionRef}
      className="relative py-32 overflow-hidden"
      style={{ background: "#050505" }}
    >
      {/* Top accent line */}
      <div className="absolute top-0 left-0 right-0" style={{ height: "1px", background: "linear-gradient(90deg, transparent, rgba(249,115,22,0.35), transparent)" }} />

      <div className="max-w-[1600px] mx-auto px-8 md:px-16">
        {/* Header */}
        <div className="flex flex-col xl:flex-row items-start xl:items-end justify-between gap-8 mb-14">
          <div>
            <motion.div
              initial={{ opacity: 0, x: -20 }}
              animate={isInView ? { opacity: 1, x: 0 } : {}}
              transition={{ duration: 0.6 }}
              className="flex items-center gap-4 mb-4"
            >
              <div style={{ width: 32, height: 2, background: "linear-gradient(90deg, #F97316, transparent)" }} />
              <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 600, fontSize: "12px", letterSpacing: "0.4em", color: "#F97316" }}>
                SELECTED WORKS
              </span>
            </motion.div>
            <motion.h2
              initial={{ opacity: 0, y: 30 }}
              animate={isInView ? { opacity: 1, y: 0 } : {}}
              transition={{ duration: 0.8, delay: 0.1, ease: [0.22, 1, 0.36, 1] }}
              style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 900, fontSize: "clamp(2.8rem, 6vw, 7rem)", lineHeight: 0.9, letterSpacing: "-0.01em", color: "#FFFFFF", textTransform: "uppercase" }}
            >
              OUR
              <br />
              <GradientText
                colors={["#F97316", "#FFB700", "#F97316", "#FFB700", "#F97316"]}
                animationSpeed={3}
                showBorder={false}
              >
                PORTFOLIO
              </GradientText>
            </motion.h2>
          </div>

          {/* Filter tabs */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={isInView ? { opacity: 1 } : {}}
            transition={{ delay: 0.3, duration: 0.6 }}
            className="flex flex-wrap items-center gap-1"
            style={{ background: "rgba(255,255,255,0.03)", padding: "4px", border: "1px solid rgba(255,255,255,0.07)" }}
          >
            {categories.map((cat) => (
              <button
                key={cat}
                onClick={() => setActiveFilter(cat)}
                className="px-5 py-2 transition-all duration-300"
                style={{
                  fontFamily: "'Barlow Condensed', sans-serif",
                  fontWeight: 700,
                  fontSize: "11px",
                  letterSpacing: "0.25em",
                  color: activeFilter === cat ? "#fff" : "rgba(255,255,255,0.33)",
                  background: activeFilter === cat ? "linear-gradient(135deg, #F97316, #FFB700)" : "transparent",
                  border: "none",
                  cursor: "pointer",
                }}
              >
                {cat}
              </button>
            ))}
          </motion.div>
        </div>

        {/* ─── BENTO GRID ─── */}
        <div
          className="hidden xl:grid gap-3"
          style={{
            gridTemplateColumns: "1fr 1fr 1fr",
            gridTemplateRows: "360px 320px 220px",
          }}
        >
          {/* W1 — wide feature */}
          <div style={{ gridColumn: "1 / 3", gridRow: "1" }}>
            <FeatureCard work={works[0]} delay={0.05} />
          </div>
          {/* W2 — tall portrait (spans row 1-2) */}
          <div style={{ gridColumn: "3", gridRow: "1 / 3" }}>
            <TallCard work={works[1]} delay={0.12} />
          </div>
          {/* W3 — square */}
          <div style={{ gridColumn: "1", gridRow: "2" }}>
            <SquareCard work={works[2]} delay={0.18} />
          </div>
          {/* W4 — square */}
          <div style={{ gridColumn: "2", gridRow: "2" }}>
            <SquareCard work={works[3]} delay={0.24} />
          </div>
          {/* W5 — full-width banner */}
          <div style={{ gridColumn: "1 / 4", gridRow: "3" }}>
            <BannerCard work={works[4]} delay={0.3} />
          </div>
        </div>

        {/* Tablet bento */}
        <div
          className="hidden md:grid xl:hidden gap-4"
          style={{
            gridTemplateColumns: "1fr 1fr",
            gridTemplateRows: "340px 300px 300px 240px",
          }}
        >
          <div style={{ gridColumn: "1 / 3", gridRow: "1" }}>
            <FeatureCard work={works[0]} delay={0.05} />
          </div>
          <div style={{ gridColumn: "1", gridRow: "2 / 4" }}>
            <TallCard work={works[1]} delay={0.12} />
          </div>
          <div style={{ gridColumn: "2", gridRow: "2" }}>
            <SquareCard work={works[2]} delay={0.18} />
          </div>
          <div style={{ gridColumn: "2", gridRow: "3" }}>
            <SquareCard work={works[3]} delay={0.24} />
          </div>
          <div style={{ gridColumn: "1 / 3", gridRow: "4" }}>
            <FeatureCard work={works[4]} delay={0.3} />
          </div>
        </div>

        {/* Mobile stacked */}
        <div className="flex flex-col gap-4 md:hidden">
          <div style={{ height: "360px" }}>
            <FeatureCard work={works[0]} delay={0.05} />
          </div>
          <div style={{ height: "430px" }}>
            <TallCard work={works[1]} delay={0.12} />
          </div>
          <div style={{ height: "360px" }}>
            <SquareCard work={works[2]} delay={0.18} />
          </div>
          <div style={{ height: "360px" }}>
            <SquareCard work={works[3]} delay={0.24} />
          </div>
          <div style={{ height: "360px" }}>
            <FeatureCard work={works[4]} delay={0.3} />
          </div>
        </div>
      </div>
    </section>
  );
}
