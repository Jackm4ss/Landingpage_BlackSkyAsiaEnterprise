import { mountAdminDateRangePickers } from "./filament-admin-date-range";
import { mountAdminCountryDropdowns } from "./filament-admin-country-dropdown";

const flagAssets = import.meta.glob("../node_modules/flag-icons/flags/4x3/*.svg", {
  eager: true,
  import: "default",
  query: "?url",
}) as Record<string, string>;

const flagUrls = new Map(
  Object.entries(flagAssets).map(([path, url]) => {
    const [, code = ""] = path.match(/\/([a-z0-9-]+)\.svg$/) ?? [];

    return [code, url];
  }),
);

const mountDashboardFlags = () => {
  document.querySelectorAll<HTMLElement>("[data-bsa-country-code]").forEach((flag) => {
    const code = flag.dataset.bsaCountryCode?.toLowerCase();
    const url = code ? flagUrls.get(code) : null;

    if (!url) return;

    flag.style.backgroundImage = `url("${url}")`;
    flag.classList.add("bsa-country-flag-loaded");
  });
};

const mountAdminEnhancements = () => {
  mountDashboardFlags();
  mountAdminDateRangePickers();
  mountAdminCountryDropdowns();
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", mountAdminEnhancements, { once: true });
} else {
  mountAdminEnhancements();
}

document.addEventListener("livewire:navigated", mountAdminEnhancements);
document.addEventListener("livewire:initialized", () => {
  const livewire = (window as unknown as { Livewire?: { hook?: (name: string, callback: () => void) => void } }).Livewire;

  livewire?.hook?.("morphed", mountAdminEnhancements);
});
