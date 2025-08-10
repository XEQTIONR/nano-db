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

export function InputCalendar({
  id, 
  placeholder, 
  date = undefined, 
  className = "", 
  timeZone = "Asia/Dhaka",
  onChange = undefined
} : {
  id: string, 
  placeholder?: string, 
  date?: string | undefined,
  className?: string, 
  timeZone?: string
  onChange?: (date: Date | undefined) => void
}) {
  const [currentDate, setCurrentDate] = useState<Date|undefined>(() => {
    if (date != undefined) {
      return new Date( date )
    }

    return undefined
  })

  const calendarX = useRef(null)
  const trigger = useRef<HTMLButtonElement>(null)

  const clearAndClose = () => {
    setCurrentDate(undefined)

    if (onChange) {
      onChange(undefined)
    }
    trigger.current?.click()
  }

  const selectAndClose = (date?: Date) => {
    setCurrentDate(date)

    if (onChange) {
      onChange(date)
    }
    trigger.current?.click()

  }
  return (
    <Popover>
      <PopoverTrigger ref={trigger} asChild>
        <div
            id={id}
            data-empty={!currentDate}
            className={
              cn(
                "dark:bg-neutral-900 hover:cursor-pointer rounded-md border py-1 pl-3 pr-1.5 flex justify-between items-center text-left text-sm font-normal data-[empty=true]:text-muted-foreground w-full",
                className
              )
            }

        >
            {currentDate ? format(currentDate, "PPP") : <span>{placeholder ?? "Pick a date" }</span>}
            <div className="rounded-sm p-1.5 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-700">
            {
                currentDate 
                ? <X ref={calendarX} onClick={clearAndClose} size={14} /> 
                : <CalendarIcon size={14} />
            }
            </div>
        </div>
      </PopoverTrigger>
      <PopoverContent className="w-auto p-0">
        <Calendar 
          timeZone={timeZone} 
          captionLayout="dropdown" 
          mode="single" 
          selected={currentDate} 
          onSelect={(date) => selectAndClose(date)} 
        />
      </PopoverContent>
    </Popover>
  )
}