
import { useState } from "react"
import { Check, ChevronsUpDown } from "lucide-react"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from "@/components/ui/command"
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover"

interface Option {
    value: string,
    label: string,
}

const frameworks: Option[] = [
  {
    value: "next.js",
    label: "Next.js",
  },
  {
    value: "sveltekit",
    label: "SvelteKit",
  },
  {
    value: "nuxt.js",
    label: "Nuxt.js",
  },
  {
    value: "remix",
    label: "Remix",
  },
  {
    value: "astro",
    label: "Astro",
  },
]

export function Combobox({ type = "single", options = frameworks } : { type?: "single" | "multiple", options?: Option[] }) {
  const [open, setOpen] = useState<boolean>(false)
  const [value, setValue] = useState<string | string[]>(type == "single" ? "" : [])
  return (
    <Popover open={open} onOpenChange={(isOpen) => {
            setOpen(isOpen)
    }}>
      <PopoverTrigger asChild>
        <Button
          variant="outline"
          role="combobox"
          aria-expanded={open}
          className="grow justify-between pr-1.5"
        >
          {
            type == "single"
                ? (value
                    ? options.find((framework) => framework.value === value)?.label
                    : "Select option ...")
                : ((value.length > 0 && Array.isArray(value))
                    ? value.map((v: string) => options.find(({value}) => value == v)?.label)
                        .join(", ")
                    : "Select options ...")

            }
        <span className="hover:cursor-pointer hover:bg-white dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-gray-50 p-1 rounded">
          <ChevronsUpDown className="" />
        </span>
        </Button>
      </PopoverTrigger>
      <PopoverContent align="end" className="grow p-0  w-[19.7rem]">
        <Command>
          <CommandInput placeholder="Search" className="h-9" />
          <CommandList>
            <CommandEmpty>No option found.</CommandEmpty>
            <CommandGroup>
              {options.map((option) => (
                <CommandItem
                  key={option.value}
                  value={option.value}
                  onSelect={(currentValue) => {
                    if (type == "single") {
                        setValue(currentValue === value ? "" : currentValue)
                        setOpen(false)
                    } else if (Array.isArray(value) && currentValue) { // type == "multiple"
                        if (value.indexOf(currentValue) == -1 ) {
                            setValue([...value, currentValue])
                        } else {
                            setValue([...value.filter((v) => v !== currentValue)
                            ])
                        }
                    }
                  }}
                >
                  {option.label}
                  <Check
                    className={cn(
                      "ml-auto",
                      (type === "single" && value === option.value) || (value.indexOf(option.value) > -1)
                        ? "opacity-100" : "opacity-0"
                    )}
                  />
                </CommandItem>
              ))}
            </CommandGroup>
          </CommandList>
        </Command>
      </PopoverContent>
    </Popover>
  )
}