import { useRef, useState } from "react";
import { motion, useInView, useMotionValue, animate } from "motion/react";
import { ArrowRight, MapPin, Calendar, ChevronLeft, ChevronRight } from "lucide-react";
import GradientText from "./GradientText";

const img1 =
  "https://images.unsplash.com/photo-1772587023108-61e60c18537a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxsaXZlJTIwY29uY2VydCUyMHBlcmZvcm1lciUyMHN0YWdlJTIwc3BvdGxpZ2h0fGVufDF8fHx8MTc3ODIzOTQxN3ww&ixlib=rb-4.1.0&q=80&w=1080";
const img2 =
  "https://images.unsplash.com/photo-1774112560513-38b6ec3ba898?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVjdHJvbmljJTIwbXVzaWMlMjBmZXN0aXZhbCUyMGNvbG9yZnVsJTIwbGFzZXIlMjBzaG93fGVufDF8fHx8MTc3ODIzOTQxN3ww&ixlib=rb-4.1.0&q=80&w=1080";
const img3 =
  "https://images.unsplash.com/photo-1754492885592-34e5fe3f0093?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb25jZXJ0JTIwc3RhZ2UlMjBweXJvdGVjaG5pY3MlMjBmaXJlJTIwc2hvdyUyMGRhcmt8ZW58MXx8fHwxNzc4MjM5NDIwfDA&ixlib=rb-4.1.0&q=80&w=1080";
const img4 =
  "https://images.unsplash.com/photo-1739194029327-bb1b252cf9c3?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxTb3V0aGVhc3QlMjBBc2lhJTIwY29uY2VydCUyMG91dGRvb3IlMjBmZXN0aXZhbCUyMG5pZ2h0fGVufDF8fHx8MTc3ODIzOTQyNHww&ixlib=rb-4.1.0&q=80&w=1080";
const img5 =
  "https://images.unsplash.com/photo-1590699306463-dbb2b13c0ca0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtdXNpYyUyMGVudGVydGFpbm1lbnQlMjBlZGl0b3JpYWwlMjBwaG90b2dyYXBoeSUyMGRhcmt8ZW58MXx8fHwxNzc4MjM5NDIxfDA&ixlib=rb-4.1.0&q=80&w=1080";

interface Event {
  id: number;
  title: string;
  subtitle: string;
  date: string;
  venue: string;
  city: string;
  tag: string;
  accentColor: string;
  glowColor: string;
  image: string;
  soldOut?: boolean;
}

const events: Event[] = [
  {
    id: 1,
    title: "IGNITE FESTIVAL",
    subtitle: "The Greatest Electronic Music Gathering in SEA",
    date: "JUL 19–21, 2026",
    venue: "Axiata Arena",
    city: "Kuala Lumpur",
    tag: "FESTIVAL",
    accentColor: "#A855F7",
    glowColor: "rgba(168,85,247,0.45)",
    image: img2,
  },
  {
    id: 2,
    title: "ECHOES",
    subtitle: "Asia Arena Tour — Singapore Night",
    date: "AUG 05, 2026",
    venue: "Singapore Indoor Stadium",
    city: "Singapore",
    tag: "ARENA SHOW",
    accentColor: "#0EA5E9",
    glowColor: "rgba(14,165,233,0.45)",
    image: img1,
    soldOut: true,
  },
  {
    id: 3,
    title: "BLACKOUT",
    subtitle: "The Ultimate Dark Rave Experience",
    date: "AUG 23, 2026",
    venue: "Zepp KL",
    city: "Kuala Lumpur",
    tag: "RAVE",
    accentColor: "#E11D48",
    glowColor: "rgba(225,29,72,0.45)",
    image: img3,
  },
  {
    id: 4,
    title: "SKY SESSIONS",
    subtitle: "Rooftop Open Air Concert Series",
    date: "SEP 12, 2026",
    venue: "TREC Entertainment Hub",
    city: "Kuala Lumpur",
    tag: "OUTDOOR",
    accentColor: "#F97316",
    glowColor: "rgba(249,115,22,0.45)",
    image: img4,
  },
  {
    id: 5,
    title: "STARLIGHT",
    subtitle: "K-Pop & Asian Pop Live Spectacular",
    date: "OCT 03, 2026",
    venue: "Stadium Merdeka",
    city: "Kuala Lumpur",
    tag: "K-POP",
    accentColor: "#FFB700",
    glowColor: "rgba(255,183,0,0.45)",
    image: img5,
  },
];

const CARD_WIDTH = 460;
const GAP = 20;

function EventSlideCard({ event, isActive }: { event: Event; isActive: boolean }) {
  const [hovered, setHovered] = useState(false);

  return (
    <motion.div
      onMouseEnter={() => setHovered(true)}
      onMouseLeave={() => setHovered(false)}
      animate={{ scale: isActive ? 1 : 0.96, opacity: isActive ? 1 : 0.65 }}
      transition={{ duration: 0.4, ease: [0.22, 1, 0.36, 1] }}
      className="relative overflow-hidden cursor-grab active:cursor-grabbing flex-shrink-0"
      style={{
        width: CARD_WIDTH,
        background: "#0A0A0A",
        border: `1px solid ${isActive ? event.accentColor + "50" : "rgba(255,255,255,0.05)"}`,
        transition: "border-color 0.4s",
        userSelect: "none",
      }}
    >
      {/* Image Area */}
      <div className="relative overflow-hidden" style={{ aspectRatio: "16/9" }}>
        <img
          src={event.image}
          alt={event.title}
          draggable={false}
          className="w-full h-full object-cover"
          style={{
            transform: hovered ? "scale(1.06)" : "scale(1)",
            transition: "transform 0.8s ease",
            pointerEvents: "none",
          }}
        />
        {/* Base gradient */}
        <div
          className="absolute inset-0"
          style={{
            background: "linear-gradient(180deg, rgba(5,5,5,0.1) 0%, rgba(10,10,10,0.92) 100%)",
          }}
        />
        {/* Bottom glow bloom */}
        <motion.div
          className="absolute inset-0"
          animate={{ opacity: isActive ? 1 : 0 }}
          transition={{ duration: 0.5 }}
          style={{
            background: `radial-gradient(ellipse at 50% 110%, ${event.glowColor} 0%, transparent 65%)`,
          }}
        />

        {/* Tag badge */}
        <div
          className="absolute top-5 left-5 flex items-center gap-2"
          style={{
            background: "rgba(5,5,5,0.82)",
            backdropFilter: "blur(10px)",
            padding: "5px 14px",
            border: `1px solid ${event.accentColor}35`,
          }}
        >
          <div
            style={{
              width: 6,
              height: 6,
              borderRadius: "50%",
              background: event.accentColor,
              boxShadow: `0 0 6px ${event.accentColor}`,
            }}
          />
          <span
            style={{
              fontFamily: "'Barlow Condensed', sans-serif",
              fontWeight: 700,
              fontSize: "10px",
              letterSpacing: "0.32em",
              color: event.accentColor,
            }}
          >
            {event.tag}
          </span>
        </div>

        {/* Sold Out */}
        {event.soldOut && (
          <div
            className="absolute top-5 right-5 px-3 py-1"
            style={{ background: "#E11D48" }}
          >
            <span
              style={{
                fontFamily: "'Barlow Condensed', sans-serif",
                fontWeight: 700,
                fontSize: "9px",
                letterSpacing: "0.35em",
                color: "#fff",
              }}
            >
              SOLD OUT
            </span>
          </div>
        )}
      </div>

      {/* Content */}
      <div className="p-7">
        {/* Accent line */}
        <div
          className="mb-5"
          style={{
            width: isActive ? 48 : 24,
            height: 2,
            background: `linear-gradient(90deg, ${event.accentColor}, transparent)`,
            transition: "width 0.4s ease",
          }}
        />

        <h3
          style={{
            fontFamily: "'Barlow Condensed', sans-serif",
            fontWeight: 900,
            fontSize: "clamp(2rem, 3.5vw, 2.8rem)",
            lineHeight: 0.92,
            letterSpacing: "-0.01em",
            color: "#FFFFFF",
            textTransform: "uppercase",
            marginBottom: "10px",
          }}
        >
          {event.title}
        </h3>
        <p
          style={{
            fontFamily: "'Barlow', sans-serif",
            fontWeight: 300,
            fontSize: "0.82rem",
            color: "rgba(255,255,255,0.4)",
            lineHeight: 1.6,
            marginBottom: "20px",
          }}
        >
          {event.subtitle}
        </p>

        {/* Meta info */}
        <div className="flex flex-col gap-2.5 mb-6">
          <div className="flex items-center gap-2.5">
            <Calendar size={12} style={{ color: event.accentColor, flexShrink: 0 }} />
            <span
              style={{
                fontFamily: "'Barlow Condensed', sans-serif",
                fontWeight: 600,
                fontSize: "11px",
                letterSpacing: "0.22em",
                color: "rgba(255,255,255,0.55)",
              }}
            >
              {event.date}
            </span>
          </div>
          <div className="flex items-center gap-2.5">
            <MapPin size={12} style={{ color: event.accentColor, flexShrink: 0 }} />
            <span
              style={{
                fontFamily: "'Barlow Condensed', sans-serif",
                fontWeight: 600,
                fontSize: "11px",
                letterSpacing: "0.22em",
                color: "rgba(255,255,255,0.55)",
              }}
            >
              {event.venue}, {event.city}
            </span>
          </div>
        </div>

        {/* CTA */}
        <div
          className="flex items-center justify-between pt-5"
          style={{ borderTop: "1px solid rgba(255,255,255,0.07)" }}
        >
          <button
            style={{
              display: "flex",
              alignItems: "center",
              gap: "8px",
              fontFamily: "'Barlow Condensed', sans-serif",
              fontWeight: 700,
              fontSize: "11px",
              letterSpacing: "0.28em",
              color: event.soldOut ? "rgba(255,255,255,0.22)" : event.accentColor,
              background: "none",
              border: "none",
              cursor: event.soldOut ? "not-allowed" : "pointer",
              padding: 0,
            }}
          >
            {event.soldOut ? "NOTIFY ME" : "GET TICKETS"}
            <ArrowRight
              size={12}
              style={{
                transform: hovered && !event.soldOut ? "translateX(5px)" : "translateX(0)",
                transition: "transform 0.3s",
              }}
            />
          </button>
          <span
            style={{
              fontFamily: "'Barlow Condensed', sans-serif",
              fontWeight: 700,
              fontSize: "11px",
              letterSpacing: "0.3em",
              color: "rgba(255,255,255,0.72)",
            }}
          >
            BSE — 2026
          </span>
        </div>
      </div>
    </motion.div>
  );
}

export function EventsSection() {
  const sectionRef = useRef<HTMLDivElement>(null);
  const isInView = useInView(sectionRef, { once: true, margin: "-100px" });
  const [activeIndex, setActiveIndex] = useState(0);
  const [dragStartX, setDragStartX] = useState(0);
  const x = useMotionValue(0);
  const TOTAL = events.length;

  const goTo = (index: number) => {
    const clamped = Math.max(0, Math.min(index, TOTAL - 1));
    setActiveIndex(clamped);
    animate(x, -(clamped * (CARD_WIDTH + GAP)), {
      type: "spring",
      stiffness: 280,
      damping: 28,
      mass: 0.9,
    });
  };

  const handleDragEnd = (_: any, info: { offset: { x: number }; velocity: { x: number } }) => {
    const swipeThreshold = CARD_WIDTH * 0.25;
    const velThreshold = 500;
    if (info.offset.x < -swipeThreshold || info.velocity.x < -velThreshold) {
      goTo(activeIndex + 1);
    } else if (info.offset.x > swipeThreshold || info.velocity.x > velThreshold) {
      goTo(activeIndex - 1);
    } else {
      goTo(activeIndex);
    }
  };

  const maxDrag = -((TOTAL - 1) * (CARD_WIDTH + GAP));
  const progressPct = (activeIndex / (TOTAL - 1)) * 100;

  return (
    <section
      id="events"
      ref={sectionRef}
      className="relative py-32 overflow-hidden"
      style={{ background: "#080808" }}
    >
      {/* BG glow */}
      <div
        className="absolute pointer-events-none"
        style={{
          width: "700px",
          height: "700px",
          top: "40%",
          left: "50%",
          transform: "translate(-50%,-50%)",
          background: "radial-gradient(circle, rgba(168,85,247,0.05) 0%, transparent 70%)",
        }}
      />

      <div className="max-w-[1600px] mx-auto">
        {/* Header row */}
        <div className="px-8 md:px-16 flex flex-col md:flex-row items-start md:items-end justify-between gap-6 mb-12">
          <div>
            <motion.div
              initial={{ opacity: 0, x: -20 }}
              animate={isInView ? { opacity: 1, x: 0 } : {}}
              transition={{ duration: 0.6 }}
              className="flex items-center gap-4 mb-4"
            >
              <div style={{ width: 32, height: 2, background: "linear-gradient(90deg, #A855F7, transparent)" }} />
              <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 600, fontSize: "12px", letterSpacing: "0.4em", color: "#A855F7" }}>
                UPCOMING
              </span>
            </motion.div>
            <motion.h2
              initial={{ opacity: 0, y: 30 }}
              animate={isInView ? { opacity: 1, y: 0 } : {}}
              transition={{ duration: 0.8, delay: 0.1, ease: [0.22, 1, 0.36, 1] }}
              style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 900, fontSize: "clamp(2.8rem, 6vw, 7rem)", lineHeight: 0.9, letterSpacing: "-0.01em", color: "#FFFFFF", textTransform: "uppercase" }}
            >
              EVENTS &amp;
              <br />
              <GradientText
                colors={["#A855F7", "#0EA5E9", "#A855F7", "#0EA5E9", "#A855F7"]}
                animationSpeed={3}
                showBorder={false}
              >
                SHOWS
              </GradientText>
            </motion.h2>
          </div>

          {/* Counter + Nav */}
          <motion.div
            initial={{ opacity: 0 }}
            animate={isInView ? { opacity: 1 } : {}}
            transition={{ delay: 0.35, duration: 0.6 }}
            className="flex items-center gap-4 flex-shrink-0"
          >
            {/* Count */}
            <div className="flex items-baseline gap-1">
              <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 900, fontSize: "2rem", color: "#fff", lineHeight: 1 }}>
                {String(activeIndex + 1).padStart(2, "0")}
              </span>
              <span style={{ fontFamily: "'Barlow Condensed', sans-serif", fontWeight: 400, fontSize: "1rem", color: "rgba(255,255,255,0.25)", lineHeight: 1 }}>
                /{String(TOTAL).padStart(2, "0")}
              </span>
            </div>

            {/* Prev / Next buttons */}
            <div className="flex items-center gap-2">
              <button
                onClick={() => goTo(activeIndex - 1)}
                disabled={activeIndex === 0}
                className="flex items-center justify-center transition-all duration-300"
                style={{
                  width: 48,
                  height: 48,
                  border: "1px solid rgba(255,255,255,0.12)",
                  background: activeIndex === 0 ? "transparent" : "rgba(168,85,247,0.08)",
                  color: activeIndex === 0 ? "rgba(255,255,255,0.2)" : "#fff",
                  cursor: activeIndex === 0 ? "not-allowed" : "pointer",
                }}
              >
                <ChevronLeft size={20} />
              </button>
              <button
                onClick={() => goTo(activeIndex + 1)}
                disabled={activeIndex === TOTAL - 1}
                className="flex items-center justify-center transition-all duration-300"
                style={{
                  width: 48,
                  height: 48,
                  border: "1px solid rgba(255,255,255,0.12)",
                  background: activeIndex === TOTAL - 1 ? "transparent" : "rgba(168,85,247,0.08)",
                  color: activeIndex === TOTAL - 1 ? "rgba(255,255,255,0.2)" : "#fff",
                  cursor: activeIndex === TOTAL - 1 ? "not-allowed" : "pointer",
                }}
              >
                <ChevronRight size={20} />
              </button>
            </div>

            {/* View All */}
            <button
              className="hidden lg:flex items-center gap-2 px-6 py-3 transition-all duration-300"
              style={{
                border: "1px solid rgba(168,85,247,0.3)",
                fontFamily: "'Barlow Condensed', sans-serif",
                fontWeight: 700,
                fontSize: "11px",
                letterSpacing: "0.25em",
                color: "#A855F7",
                background: "rgba(168,85,247,0.05)",
                cursor: "pointer",
              }}
            >
              ALL EVENTS
              <ArrowRight size={12} />
            </button>
          </motion.div>
        </div>

        {/* Slider Track */}
        <div
          className="relative overflow-hidden"
          style={{ paddingLeft: "clamp(2rem, 4vw, 8rem)" }}
        >
          <motion.div
            drag="x"
            style={{ x, display: "flex", gap: GAP, cursor: "grab" }}
            dragConstraints={{ left: maxDrag, right: 0 }}
            dragElastic={0.08}
            onDragStart={(_, info) => setDragStartX(info.point.x)}
            onDragEnd={handleDragEnd}
            whileTap={{ cursor: "grabbing" }}
          >
            {events.map((event) => (
              <EventSlideCard
                key={event.id}
                event={event}
                isActive={event.id === events[activeIndex].id}
              />
            ))}
            {/* Trailing spacer */}
            <div style={{ width: "clamp(2rem, 4vw, 6rem)", flexShrink: 0 }} />
          </motion.div>
        </div>

        {/* Bottom: Progress + Dots */}
        <div className="px-8 md:px-16 mt-10 flex items-center justify-between gap-8">
          {/* Progress bar */}
          <div
            className="flex-1 relative overflow-hidden"
            style={{ height: 2, background: "rgba(255,255,255,0.07)", maxWidth: 320 }}
          >
            <motion.div
              animate={{ width: `${progressPct}%` }}
              transition={{ type: "spring", stiffness: 280, damping: 28 }}
              style={{
                position: "absolute",
                left: 0,
                top: 0,
                height: "100%",
                background: "linear-gradient(90deg, #A855F7, #0EA5E9)",
              }}
            />
          </div>

          {/* Dot indicators */}
          <div className="flex items-center gap-2">
            {events.map((_, i) => (
              <button
                key={i}
                onClick={() => goTo(i)}
                style={{
                  width: i === activeIndex ? 24 : 8,
                  height: 6,
                  background: i === activeIndex
                    ? "linear-gradient(90deg, #A855F7, #0EA5E9)"
                    : "rgba(255,255,255,0.15)",
                  border: "none",
                  cursor: "pointer",
                  transition: "width 0.35s ease, background 0.35s ease",
                  padding: 0,
                }}
              />
            ))}
          </div>

          {/* Drag hint */}
          <span
            style={{
              fontFamily: "'Barlow Condensed', sans-serif",
              fontWeight: 600,
              fontSize: "10px",
              letterSpacing: "0.3em",
              color: "rgba(255,255,255,0.2)",
            }}
          >
            DRAG TO EXPLORE
          </span>
        </div>
      </div>
    </section>
  );
}
