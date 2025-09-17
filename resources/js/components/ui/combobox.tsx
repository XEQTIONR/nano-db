
import { useRef, useState } from "react"
import { Check, ChevronsUpDown } from "lucide-react"
import { cn, debounce } from "@/lib/utils"
import { useDebouncedCallback } from 'use-debounce';
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

import { type StrOrNum, Option } from "@/types"


export function Combobox({
  className = "", 
  dataType = "string",
  debounceTimeOut = 300, 
  getOptions = undefined,
  onSelect = undefined,
  options = [], 
  type = "single",
  value = undefined,
  placeholder = undefined
} : {
  className?: string
  dataType?: "string" | "float" | "int" 
  debounceTimeOut?: number,
  getOptions?: (search: string) => Promise<Option[]>,
  onSelect?: (x: StrOrNum|StrOrNum[]) => void,
  options?: Option[], 
  type?: "single" | "multiple", 
  value?: StrOrNum | StrOrNum[],
  placeholder: string | undefined
}) {
  const [open, setOpen] = useState<boolean>(false)
  const [localValue, setLocalValue] = useState<StrOrNum | StrOrNum[]>(value ?? (type == "single" ? "" : []))
  const [localOptions, setLocalOptions] = useState<Option[]>(options)
  const [selectedOptions, setSelectedOptions] = useState<Option[]>([])

  const inputRef = useRef(null)

  const debounced = useDebouncedCallback(
    async (str) => {
      if (getOptions) {
        const opts: Option[] = await getOptions(str)
        setLocalOptions([...opts])
      }
    },
    debounceTimeOut
  )

  const setValue = (val: StrOrNum | StrOrNum[]) => {
    setLocalValue(val)

    if (onSelect) {
      onSelect(val)
    }
  }
  return (
    <Popover open={open} onOpenChange={(isOpen) => {
            setOpen(isOpen)
    }}>
      <PopoverTrigger className={className} asChild>
        <Button
          ref={inputRef}
          variant="outline"
          role="combobox"
          aria-expanded={open}
          className="grow justify-between pr-1.5"
        >
          {
            type == "single"
                ? (localValue
                    ? localOptions.find((item) => item.value == localValue)?.label ?? localValue
                    : placeholder ?? "Select option ...")
                : ((Array.isArray(localValue) && localValue.length > 0)
                    ? localValue.map((v: StrOrNum) => selectedOptions.find((option) => option.value == v)?.label ?? v.toString())
                        .join(", ")
                    : placeholder ?? "Select options ...")

            }
        <span className="hover:cursor-pointer hover:bg-white dark:hover:bg-neutral-700 dark:text-neutral-400 dark:hover:text-gray-50 p-1 rounded">
          <ChevronsUpDown className="" />
        </span>
        </Button>
      </PopoverTrigger>
      <PopoverContent align="end" className={"grow p-0 "} style={{ width: inputRef.current?.offsetWidth + 'px' }}>
        <Command shouldFilter={false}>
          <CommandInput 
            onValueChange={(str: string) => debounced(str)} 
            placeholder="Search" 
            className="h-9"
          />
          <CommandList>
            <CommandEmpty>No option found.</CommandEmpty>
            <CommandGroup>
              {localOptions.map((option) => (
                <CommandItem
                  key={option.value}
                  value={option.value.toString()} // check if this to string should be here
                  onSelect={(currentValue) => {
                    if (type == "single") {
                      let cv;
                        switch (dataType) {
                          case "float":
                            cv = parseFloat(currentValue)
                            setValue(cv === localValue ? 0 : cv)
                            break
                          case "int":
                            cv = parseInt(currentValue)
                            setValue(cv === localValue ? 0 : cv)
                            break
                          case "string":
                          default:
                            cv = currentValue
                            setValue(cv === localValue ? "" : cv)
                        }
                        
                        setOpen(false)
                    } else if (Array.isArray(localValue) && currentValue) { // type == "multiple"
                        let cv;
                        switch (dataType) {
                          case "float":
                            cv = parseFloat(currentValue)
                            break
                          case "int":
                            cv = parseInt(currentValue)
                            break
                          case "string":
                          default:
                            cv = currentValue
                        }
                      
                        if (localValue.indexOf(cv) == -1 ) {
                            setValue([...localValue, cv])
                            setSelectedOptions([...selectedOptions, option])
                        } else {
                            setValue([...localValue.filter((v) => v !== cv)])
                            setSelectedOptions([...selectedOptions.filter(
                              ({value, label}) => !(option.value == value && option.label == label)
                            )])
                        }
                    }
                  }}
                >
                  {option.label}
                  <Check
                    className={cn(
                      "ml-auto",
                      (type === "single" && localValue === option.value) || (Array.isArray(localValue) && localValue.indexOf(option.value.toString()) > -1)
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