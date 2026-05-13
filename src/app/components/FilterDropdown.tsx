import { type ReactNode, useEffect, useId, useMemo, useRef, useState } from "react";
import { Check, ChevronDown } from "lucide-react";
import "./FilterDropdown.css";

export type FilterDropdownOption = {
  value: string;
  label: string;
};

type FilterDropdownProps = {
  ariaLabel: string;
  value: string;
  options: FilterDropdownOption[];
  onChange: (value: string) => void;
  icon?: ReactNode;
  accent?: "cyan" | "rose";
};

export function FilterDropdown({
  ariaLabel,
  value,
  options,
  onChange,
  icon,
  accent = "cyan",
}: FilterDropdownProps) {
  const [isOpen, setIsOpen] = useState(false);
  const id = useId();
  const rootRef = useRef<HTMLDivElement | null>(null);

  const selectedOption = useMemo(
    () => options.find((option) => option.value === value) ?? options[0],
    [options, value],
  );

  useEffect(() => {
    if (!isOpen) {
      return undefined;
    }

    const handlePointerDown = (event: PointerEvent) => {
      if (!rootRef.current?.contains(event.target as Node)) {
        setIsOpen(false);
      }
    };

    const handleKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        setIsOpen(false);
      }
    };

    document.addEventListener("pointerdown", handlePointerDown);
    document.addEventListener("keydown", handleKeyDown);

    return () => {
      document.removeEventListener("pointerdown", handlePointerDown);
      document.removeEventListener("keydown", handleKeyDown);
    };
  }, [isOpen]);

  return (
    <div
      ref={rootRef}
      className={`bsa-filter-dropdown bsa-filter-dropdown--${accent}`}
    >
      <button
        type="button"
        className="bsa-filter-dropdown__trigger"
        aria-haspopup="listbox"
        aria-expanded={isOpen}
        aria-controls={`${id}-menu`}
        onClick={() => setIsOpen((current) => !current)}
      >
        {icon ? <span className="bsa-filter-dropdown__icon">{icon}</span> : null}
        <span className="sr-only">{ariaLabel}</span>
        <span className="bsa-filter-dropdown__value">{selectedOption?.label}</span>
        <ChevronDown className="bsa-filter-dropdown__chevron" aria-hidden="true" />
      </button>

      {isOpen ? (
        <div id={`${id}-menu`} className="bsa-filter-dropdown__menu" role="listbox">
          {options.map((option) => {
            const isSelected = option.value === value;

            return (
              <button
                key={option.value || option.label}
                type="button"
                className="bsa-filter-dropdown__option"
                role="option"
                aria-selected={isSelected}
                onClick={() => {
                  onChange(option.value);
                  setIsOpen(false);
                }}
              >
                <span>{option.label}</span>
                {isSelected ? <Check aria-hidden="true" /> : null}
              </button>
            );
          })}
        </div>
      ) : null}
    </div>
  );
}
