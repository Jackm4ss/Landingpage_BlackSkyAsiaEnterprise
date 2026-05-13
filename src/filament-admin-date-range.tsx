import * as React from "react";
import { createRoot, type Root } from "react-dom/client";
import { format } from "date-fns";
import { CalendarIcon, ChevronDownIcon, XIcon } from "lucide-react";
import type { DateRange } from "react-day-picker";

import { Calendar } from "./app/components/ui/calendar";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "./app/components/ui/popover";

import "./styles/filament-admin-date-range.css";

type DateRangePickerProps = {
  fromInputId: string;
  toInputId: string;
  fromValue: string;
  toValue: string;
};

const roots = new WeakMap<Element, Root>();

function parseDateValue(value: string): Date | undefined {
  const parts = value.split("-").map(Number);

  if (parts.length !== 3 || parts.some(Number.isNaN)) {
    return undefined;
  }

  const [year, month, day] = parts;
  const date = new Date(year, month - 1, day);

  return date.getFullYear() === year &&
    date.getMonth() === month - 1 &&
    date.getDate() === day
    ? date
    : undefined;
}

function toInputValue(date: Date | undefined): string {
  return date ? format(date, "yyyy-MM-dd") : "";
}

function formatRangeLabel(range: DateRange | undefined): string {
  if (!range?.from) {
    return "Pick a date range";
  }

  if (!range.to) {
    return format(range.from, "LLL dd, y");
  }

  return `${format(range.from, "LLL dd, y")} - ${format(range.to, "LLL dd, y")}`;
}

function syncInput(inputId: string, value: string): void {
  const input = document.getElementById(inputId) as HTMLInputElement | null;

  if (!input || input.value === value) {
    return;
  }

  input.value = value;
  input.dispatchEvent(new Event("input", { bubbles: true }));
  input.dispatchEvent(new Event("change", { bubbles: true }));
}

function DateRangePicker({
  fromInputId,
  toInputId,
  fromValue,
  toValue,
}: DateRangePickerProps) {
  const [open, setOpen] = React.useState(false);
  const [range, setRange] = React.useState<DateRange | undefined>(() => ({
    from: parseDateValue(fromValue),
    to: parseDateValue(toValue),
  }));

  React.useEffect(() => {
    setRange({
      from: parseDateValue(fromValue),
      to: parseDateValue(toValue),
    });
  }, [fromValue, toValue]);

  const applyRange = React.useCallback(
    (nextRange: DateRange | undefined) => {
      setRange(nextRange);
      syncInput(fromInputId, toInputValue(nextRange?.from));
      syncInput(toInputId, toInputValue(nextRange?.to));
    },
    [fromInputId, toInputId],
  );

  const clearRange = React.useCallback(() => {
    applyRange(undefined);
    setOpen(false);
  }, [applyRange]);

  const today = new Date();

  return (
    <Popover open={open} onOpenChange={setOpen}>
      <PopoverTrigger asChild>
        <button
          type="button"
          className="bsa-date-range-trigger"
          data-empty={!range?.from}
          aria-label="Select event date range"
        >
          <CalendarIcon aria-hidden="true" />
          <span>{formatRangeLabel(range)}</span>
          <ChevronDownIcon aria-hidden="true" />
        </button>
      </PopoverTrigger>
      <PopoverContent className="bsa-date-range-content" align="start">
        <Calendar
          mode="range"
          defaultMonth={range?.from ?? today}
          selected={range}
          onSelect={applyRange}
          numberOfMonths={2}
          className="bsa-date-range-calendar"
          classNames={{
            months: "bsa-date-range-months",
            month: "bsa-date-range-month",
            caption: "bsa-date-range-caption",
            caption_label: "bsa-date-range-caption-label",
            nav: "bsa-date-range-nav",
            nav_button: "bsa-date-range-nav-button",
            nav_button_previous: "bsa-date-range-nav-button-prev",
            nav_button_next: "bsa-date-range-nav-button-next",
            table: "bsa-date-range-table",
            head_row: "bsa-date-range-head-row",
            head_cell: "bsa-date-range-head-cell",
            row: "bsa-date-range-row",
            cell: "bsa-date-range-cell",
            day: "bsa-date-range-day",
            day_range_start: "bsa-date-range-day-start",
            day_range_end: "bsa-date-range-day-end",
            day_range_middle: "bsa-date-range-day-middle",
            day_selected: "bsa-date-range-day-selected",
            day_today: "bsa-date-range-day-today",
            day_outside: "bsa-date-range-day-outside",
            day_disabled: "bsa-date-range-day-disabled",
            day_hidden: "bsa-date-range-day-hidden",
          }}
        />
        <footer className="bsa-date-range-footer">
          <button type="button" onClick={clearRange}>
            <XIcon aria-hidden="true" />
            Clear
          </button>
          <button type="button" onClick={() => setOpen(false)}>
            Apply
          </button>
        </footer>
      </PopoverContent>
    </Popover>
  );
}

export function mountAdminDateRangePickers(): void {
  document.querySelectorAll<HTMLElement>("[data-bsa-date-range-picker]").forEach((element) => {
    const fromInputId = element.dataset.fromInput ?? "";
    const toInputId = element.dataset.toInput ?? "";
    const fromInput = document.getElementById(fromInputId) as HTMLInputElement | null;
    const toInput = document.getElementById(toInputId) as HTMLInputElement | null;

    if (!fromInputId || !toInputId || !fromInput || !toInput) {
      return;
    }

    let root = roots.get(element);

    if (!root) {
      root = createRoot(element);
      roots.set(element, root);
    }

    root.render(
      <DateRangePicker
        fromInputId={fromInputId}
        toInputId={toInputId}
        fromValue={fromInput.value}
        toValue={toInput.value}
      />,
    );
  });
}
