import * as React from "react";
import { Check, ChevronsUpDown } from "lucide-react";
import { CircleFlag } from "react-circle-flags";
import { countries } from "country-data-list";
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from "./ui/command";
import { Popover, PopoverContent, PopoverTrigger } from "./ui/popover";
import "./RegistrationCountryDropdown.css";

type Country = {
  alpha2: string;
  alpha3: string;
  name: string;
  status: string;
};

type RegistrationCountryDropdownProps = {
  id?: string;
  value: string;
  onChange: (value: string) => void;
  invalid?: boolean;
};

const countryOptions = countries.all
  .filter(
    (country: Country) =>
      country.alpha2 &&
      country.alpha3 &&
      country.name &&
      country.status === "assigned",
  )
  .sort((first: Country, second: Country) => first.name.localeCompare(second.name));

const findCountry = (value: string) => {
  const normalized = value.trim().toUpperCase();

  if (!normalized) return undefined;

  return countryOptions.find(
    (country: Country) =>
      country.alpha2.toUpperCase() === normalized ||
      country.alpha3.toUpperCase() === normalized,
  );
};

export function RegistrationCountryDropdown({
  id,
  value,
  onChange,
  invalid = false,
}: RegistrationCountryDropdownProps) {
  const [open, setOpen] = React.useState(false);
  const selectedCountry = React.useMemo(() => findCountry(value), [value]);

  const selectCountry = React.useCallback(
    (country: Country) => {
      onChange(country.alpha2.toUpperCase());
      setOpen(false);
    },
    [onChange],
  );

  return (
    <Popover open={open} onOpenChange={setOpen}>
      <PopoverTrigger asChild>
        <button
          id={id}
          type="button"
          className="auth-country-dropdown__trigger"
          data-empty={!selectedCountry}
          aria-invalid={invalid}
          aria-label="Select country"
        >
          {selectedCountry ? (
            <span className="auth-country-dropdown__value">
              <CircleFlag countryCode={selectedCountry.alpha2.toLowerCase()} height={20} />
              <span>{selectedCountry.name}</span>
            </span>
          ) : (
            <span className="auth-country-dropdown__placeholder">Select country</span>
          )}
          <ChevronsUpDown size={16} aria-hidden="true" />
        </button>
      </PopoverTrigger>
      <PopoverContent className="auth-country-dropdown__content" align="start">
        <Command className="auth-country-dropdown__command">
          <CommandInput placeholder="Search country..." />
          <CommandList className="auth-country-dropdown__list">
            <CommandEmpty className="auth-country-dropdown__empty">
              No country found.
            </CommandEmpty>
            <CommandGroup>
              {countryOptions.map((country: Country) => (
                <CommandItem
                  key={country.alpha2}
                  value={`${country.name} ${country.alpha2} ${country.alpha3}`}
                  className="auth-country-dropdown__item"
                  onSelect={() => selectCountry(country)}
                >
                  <span className="auth-country-dropdown__item-main">
                    <CircleFlag countryCode={country.alpha2.toLowerCase()} height={20} />
                    <span>{country.name}</span>
                  </span>
                  <Check
                    size={16}
                    className={country.alpha2 === selectedCountry?.alpha2 ? "is-selected" : ""}
                    aria-hidden="true"
                  />
                </CommandItem>
              ))}
            </CommandGroup>
          </CommandList>
        </Command>
      </PopoverContent>
    </Popover>
  );
}
