export const registrationSourceOptions = [
  { value: "direct", label: "Direct / Website" },
  { value: "instagram", label: "Instagram" },
  { value: "facebook", label: "Facebook" },
  { value: "tiktok", label: "TikTok" },
  { value: "google", label: "Google" },
  { value: "newsletter", label: "Newsletter" },
  { value: "partner", label: "Partner / Vendor" },
  { value: "other", label: "Other" },
] as const;

export type RegistrationSource = (typeof registrationSourceOptions)[number]["value"];

type RegistrationAttribution = {
  source: RegistrationSource;
  referrer: string | null;
  capturedAt: string;
};

const ATTRIBUTION_STORAGE_KEY = "black-sky.registration.attribution";
const sourceValues = new Set(registrationSourceOptions.map((option) => option.value));

const isBrowser = () => typeof window !== "undefined";

export const normalizeRegistrationSource = (
  value: string | null | undefined,
): RegistrationSource => {
  const normalized = value?.trim().toLowerCase().replace(/[^a-z0-9]+/g, "-") ?? "";

  if (!normalized) return "direct";
  if (sourceValues.has(normalized as RegistrationSource)) return normalized as RegistrationSource;
  if (["ig", "insta"].includes(normalized)) return "instagram";
  if (["fb", "facebook-com", "meta"].includes(normalized)) return "facebook";
  if (["tt"].includes(normalized)) return "tiktok";
  if (["google-organic", "google-search", "search"].includes(normalized)) return "google";
  if (["email", "mail", "edm"].includes(normalized)) return "newsletter";
  if (["vendor", "vendors", "affiliate", "affiliates"].includes(normalized)) return "partner";

  return "other";
};

const sourceFromHost = (host: string): RegistrationSource => {
  const hostname = host.toLowerCase();

  if (hostname.includes("instagram.")) return "instagram";
  if (hostname.includes("facebook.") || hostname.includes("fb.")) return "facebook";
  if (hostname.includes("tiktok.")) return "tiktok";
  if (hostname.includes("google.")) return "google";
  if (hostname.includes("mail.") || hostname.includes("newsletter.")) return "newsletter";

  return "other";
};

const safeReadAttribution = (): RegistrationAttribution | null => {
  if (!isBrowser()) return null;

  try {
    const storedValue = window.localStorage.getItem(ATTRIBUTION_STORAGE_KEY);
    if (!storedValue) return null;

    const parsed = JSON.parse(storedValue) as RegistrationAttribution;
    if (!parsed.source || !sourceValues.has(parsed.source)) return null;

    return parsed;
  } catch {
    window.localStorage.removeItem(ATTRIBUTION_STORAGE_KEY);
    return null;
  }
};

const safeWriteAttribution = (attribution: RegistrationAttribution) => {
  if (!isBrowser()) return;

  try {
    window.localStorage.setItem(ATTRIBUTION_STORAGE_KEY, JSON.stringify(attribution));
  } catch {
    window.localStorage.removeItem(ATTRIBUTION_STORAGE_KEY);
  }
};

const detectCurrentAttribution = (): RegistrationAttribution => {
  if (!isBrowser()) {
    return { source: "direct", referrer: null, capturedAt: new Date().toISOString() };
  }

  const url = new URL(window.location.href);
  const utmSource =
    url.searchParams.get("utm_source") ??
    url.searchParams.get("source") ??
    url.searchParams.get("ref");

  if (utmSource) {
    return {
      source: normalizeRegistrationSource(utmSource),
      referrer: document.referrer || null,
      capturedAt: new Date().toISOString(),
    };
  }

  if (document.referrer) {
    try {
      const referrer = new URL(document.referrer);
      if (referrer.origin !== window.location.origin) {
        return {
          source: sourceFromHost(referrer.hostname),
          referrer: document.referrer,
          capturedAt: new Date().toISOString(),
        };
      }
    } catch {
      return {
        source: "other",
        referrer: document.referrer,
        capturedAt: new Date().toISOString(),
      };
    }
  }

  return { source: "direct", referrer: null, capturedAt: new Date().toISOString() };
};

export const rememberRegistrationAttribution = () => {
  const detected = detectCurrentAttribution();
  const stored = safeReadAttribution();

  if (detected.source !== "direct" || detected.referrer || !stored) {
    safeWriteAttribution(detected);
  }
};

export const getRegistrationAttribution = (): RegistrationAttribution => {
  const detected = detectCurrentAttribution();

  if (detected.source !== "direct" || detected.referrer) {
    safeWriteAttribution(detected);
    return detected;
  }

  return safeReadAttribution() ?? detected;
};
