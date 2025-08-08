"use client"

import { useRef, useState } from "react"
import { format } from "date-fns"
import { Calendar as CalendarIcon, X } from "lucide-react"

import { cn } from "@/lib/utils"
import { Calendar } from "@/components/ui/calendar"
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover"

export function InputCalendar({id, placeholder, initialDate = undefined, className = ""} : {id: string, placeholder?: string, initialDate?: string | undefined, className?: string}) {
  const [date, setDate] = useState<Date|undefined>(() => {
    if (initialDate != undefined) {
      return new Date( initialDate )
    }

    return undefined
  })

  const calendarX = useRef(null)
  const trigger = useRef<HTMLButtonElement>(null)

  const clearAndClose = () => {
    setDate(undefined)
    trigger.current?.click()
  }

  const selectAndClose = (date?: Date) => {
    setDate(date)
    trigger.current?.click()

  }
  return (
    <Popover>
      <PopoverTrigger ref={trigger} asChild>
        <div
            id={id}
            data-empty={!date}
            className={
              cn(
                "dark:bg-neutral-900 hover:cursor-pointer rounded-md border py-1 pl-3 pr-1.5 flex justify-between items-center text-left text-sm font-normal data-[empty=true]:text-muted-foreground w-full",
                className
              )
            }

        >
            {date ? format(date, "PPP") : <span>{placeholder ?? "Pick a date" }</span>}
            <div className="rounded-sm p-1.5 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-700">
            {
                date 
                ? <X ref={calendarX} onClick={clearAndClose} size={14} /> 
                : <CalendarIcon size={14} />
            }
            </div>
        </div>
      </PopoverTrigger>
      <PopoverContent className="w-auto p-0">
        <Calendar captionLayout="dropdown" mode="single" selected={date} onSelect={(date) => selectAndClose(date)} />
      </PopoverContent>
    </Popover>
  )
}