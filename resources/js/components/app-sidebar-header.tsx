import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion"
import { Breadcrumbs } from '@/components/breadcrumbs'
import { SidebarTrigger } from '@/components/ui/sidebar'
import { 
    type BreadcrumbItem as BreadcrumbItemType, 
    type Filter as FilterType, 
    type FilterConfig as FilterConfigType, 
    type FilterOperator as FilterOperatorType,
    type FilterTransformed as FilterTransformedType, 
} from '@/types'
import { Separator } from "@/components/ui/separator"

import { ReactNode, useState } from 'react'

import { Button } from '@/components/ui/button'
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuCheckboxItem,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuShortcut,
  DropdownMenuTrigger,
  DropdownMenuSeparator,
} from "@/components/ui/dropdown-menu"
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"

import { Badge } from '@/components/ui/badge';

import { EllipsisVertical, Filter, FilterX, Plus } from 'lucide-react';

import { router } from '@inertiajs/react';

import {
  Sheet,
  SheetClose,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
} from "@/components/ui/sheet"
import { Combobox } from "./ui/combobox"
import { InputCalendar } from "./ui/input-calendar"
import { cn } from "@/lib/utils"
import axios from 'axios'

export function AppSidebarHeader({ breadcrumbs = [], controls }: { breadcrumbs?: BreadcrumbItemType[], controls?: ReactNode }) {
    return (
        <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex h-3/5 w-full items-center justify-start gap-1 ">
                <SidebarTrigger className="-ml-1 mr-1" />
                <Separator className="mr-3" orientation="vertical" />
                <div className="w-full grow flex items-center justify-between">
                    <Breadcrumbs breadcrumbs={breadcrumbs} />
                    { controls }
                </div>
            </div>
        </header>
    );
}

export function AppSidebarHeaderControls({ apiToken, filters, filterOptions } : { apiToken?: string, filters: FilterType[], filterOptions: FilterConfigType[] }) {

    const groups = Object.groupBy(filterOptions, ({ group }) => group )
    console.log('groups:', groups)
    const trasformFiltersToQuery = ([key, op, val] : FilterType): FilterTransformedType => {
        let operation = "eq"
        let value: number | string = ""
        const filterOption = filterOptions.find((option) => option.key === key)

        if (filterOption) {
            switch(op) {
                case "<":
                    operation = "lt"
                    break
                case "<=":
                    operation = "lte"
                    break
                case "=":
                    operation = "eq"
                    break
                case ">=":
                    operation = "gte"
                    break
                case ">":
                    operation = "gt"
                    break
                case "<>":
                    operation = "ne"
                    break
            }

            switch(filterOption.dataType) {
                case "int":
                    value = parseInt(val)
                    break
                case "float":
                    value = parseFloat(val)
                    break
                case "string":
                default:
                    value = val
            }
        }
        

        return [key, operation, value]

    }

    const lastFilters = filters.map((filter) => trasformFiltersToQuery(filter))

    const [currentFilters, setCurrentFilters] = useState<FilterTransformedType[]>(() =>
        filters.map((filter) => trasformFiltersToQuery(filter))
    )

    const updateCurrentFilterValue = (key: string, value: string | number) => {
        const i = currentFilters.findIndex((filter) => filter[0] === key)

        if (i == -1) {
            if (value !== "") {
                setCurrentFilters([...currentFilters, [key, 'eq', value]])
            }
        } else {
            const filters = [...currentFilters]
            filters[i][2] = value

            if (value == "") {
                setCurrentFilters(filters.filter((_, idx) => idx !== i))
            } else {
                setCurrentFilters(filters)
            }

        }
    }

    const updateCurrentFilterOperator = (key: string, op: FilterOperatorType | undefined) => {
        const i = currentFilters.findIndex((filter) => filter[0] === key)

        if (i != -1) {
            const filters = [...currentFilters]
            filters[i][1] = op

            setCurrentFilters(filters)
        }
    }


    return (
        <div className="flex items-end gap-2 justify-end">
            {
                filters.length > 0 && 
                (<Tooltip>
                    <TooltipTrigger asChild>
                        <Button 
                            className="hover:cursor-pointer text-xs" 
                            size="icon" 
                            variant="destructive"
                            onClick={() => {
                                const url = new URL(window.location.href)
                                url.searchParams.delete('filters')
                                router.get(url)
                            }}
                        >
                            <FilterX />
                        </Button>
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>Remove filters</p>
                    </TooltipContent>
                </Tooltip>)
            }
            <Sheet>
                <SheetTrigger>
                    <Tooltip>
                        <TooltipTrigger>
                            <Button className="hover:cursor-pointer relative" variant="ghost" size="icon">
                                <Filter className="block m-auto" />
                                { 
                                    filters.length > 0 && 
                                    (<Badge className="bg-emerald-500 text-emerald-500 font-bold hover:text-black rounded-full w-2 h-2 absolute right-2 top-2  hover:w-5 hover:h-5 hover:right-0 hover:top-0 transition-all duration-300  p-0 text-xs ">
                                        {filters.length}
                                    </Badge>)
                                }
                            </Button>
                        </TooltipTrigger>
                        <TooltipContent>
                            <p>Filter</p>
                        </TooltipContent>
                    </Tooltip>
                </SheetTrigger>
                <SheetContent>
                    <SheetHeader className="pb-0">
                        <SheetTitle>Filters</SheetTitle>
                        <SheetDescription>Filter the items based on criteria.</SheetDescription>
                    </SheetHeader>
                    
                    <Accordion className="" type="single">
                        {
                            Object.entries(groups).map(([key, fields]) => (
                                <AccordionItem className="" value={key}>
                                    <AccordionTrigger className="px-4">{key}</AccordionTrigger>
                                     <AccordionContent className="pl-4 pr-1.5">
                                        <div className="flex flex-col gap-6 mt-1 ">
                                            { fields?.map((field) => (
                                                <div key={key} className="flex flex-col">
                                                    <Label className="text-xs text-muted-foreground">{field.label}</Label>
                                                    <div className="w-full flex gap-2 mt-1  items-center">
                                                        {
                                                            field.inputType == "select" 
                                                                && <Combobox 
                                                                        type="multiple"
                                                                        getOptions={async () => {
                                                                            if (field.endpoint) {
                                                                                const result = await axios.get(field.endpoint, {
                                                                                    headers: {
                                                                                        Authorization: 'Bearer ' + apiToken
                                                                                    }
                                                                                })
                                                                                console.log('result:', result.data.items)
                                                                            }
                                                                        }}
                                                                    />
                                                        }
                                                        
                                                        {
                                                            field.inputType == "date" 
                                                                && <InputCalendar 
                                                                        date={currentFilters.find((f) => f[0] === field.key)?.[2].toString()}
                                                                        onChange={(date) => updateCurrentFilterValue(field.key, date?.toISOString().split("T")[0] ?? "")}
                                                                        id={key} 
                                                                    />
                                                        }

                                                        {
                                                            (field.inputType == "number" ||  field.inputType == "text")
                                                            && <Input
                                                                    onChange={({target}) => {
                                                                        switch(field.dataType) {
                                                                            case "int":
                                                                                updateCurrentFilterValue(field.key, isNaN(parseInt(target.value)) ? 0 : parseInt(target.value))
                                                                                break
                                                                            case "float":
                                                                                updateCurrentFilterValue(field.key, isNaN(parseFloat(target.value)) ? 0 : parseFloat(target.value))
                                                                                break
                                                                            case "string":
                                                                            default:
                                                                                updateCurrentFilterValue(field.key, target.value)
                                                                        }
                                                                    }}
                                                                    value={currentFilters.find((f) => f[0] === field.key)?.[2]} 
                                                                    className={cn(field.inputType == "number" && "text-right")}
                                                                    placeholder={(field.inputType == "number") ? "0" : ""}
                                                                />
                                                        }
                                                        <DropdownMenu>
                                                            <DropdownMenuTrigger asChild>
                                                                <Button size="icon" variant="ghost">
                                                                    <EllipsisVertical />
                                                                </Button>
                                                            </DropdownMenuTrigger>
                                                            <DropdownMenuContent className="w-56" align="start">
                                                                <DropdownMenuLabel>Where</DropdownMenuLabel>
                                                                <DropdownMenuSeparator />
                                                                <DropdownMenuGroup>
                                                                <DropdownMenuCheckboxItem
                                                                    disabled={!(currentFilters.find((f) => f[0] === field.key)?.[2])}
                                                                    checked={currentFilters.find(([key]) => key == field.key)?.[1] === undefined} 
                                                                    onClick={() => updateCurrentFilterOperator(field.key, undefined)}
                                                                >
                                                                    Not Selected
                                                                </DropdownMenuCheckboxItem>
                                                                <DropdownMenuCheckboxItem 
                                                                    checked={currentFilters.find(([key]) => key == field.key)?.[1] === "eq"} 
                                                                    onClick={() => updateCurrentFilterOperator(field.key, "eq")}
                                                                >
                                                                    Equals
                                                                    <DropdownMenuShortcut>=</DropdownMenuShortcut>
                                                                </DropdownMenuCheckboxItem>
                                                                <DropdownMenuCheckboxItem
                                                                     checked={currentFilters.find(([key]) => key == field.key)?.[1] === "ne"} 
                                                                    onClick={() => updateCurrentFilterOperator(field.key, "ne")}
                                                                >
                                                                    Doesn't equal
                                                                    <DropdownMenuShortcut>!=</DropdownMenuShortcut>
                                                                </DropdownMenuCheckboxItem>
                                                                <DropdownMenuCheckboxItem
                                                                    checked={currentFilters.find(([key]) => key == field.key)?.[1] === "gt"} 
                                                                    onClick={() => updateCurrentFilterOperator(field.key, "gt")}
                                                                >
                                                                    Greater Than
                                                                    <DropdownMenuShortcut>{">"}</DropdownMenuShortcut>
                                                                </DropdownMenuCheckboxItem>
                                                                <DropdownMenuCheckboxItem
                                                                    checked={currentFilters.find(([key]) => key == field.key)?.[1] === "gte"} 
                                                                    onClick={() => updateCurrentFilterOperator(field.key, "gte")}
                                                                >
                                                                    Greater Than or Equals
                                                                    <DropdownMenuShortcut>{">="}</DropdownMenuShortcut>
                                                                </DropdownMenuCheckboxItem>
                                                                <DropdownMenuCheckboxItem
                                                                    checked={currentFilters.find(([key]) => key == field.key)?.[1] === "lt"}
                                                                    onClick={() => updateCurrentFilterOperator(field.key, "lt")}
                                                                >
                                                                    Less Than
                                                                    <DropdownMenuShortcut>{"<"}</DropdownMenuShortcut>
                                                                </DropdownMenuCheckboxItem>
                                                                <DropdownMenuCheckboxItem
                                                                    checked={currentFilters.find(([key]) => key == field.key)?.[1] === "lte"}
                                                                    onClick={() => updateCurrentFilterOperator(field.key, "eq")}
                                                                >
                                                                    Less Then or Equals
                                                                    <DropdownMenuShortcut>{"<="}</DropdownMenuShortcut>
                                                                </DropdownMenuCheckboxItem>
                                                                </DropdownMenuGroup>
                                                            </DropdownMenuContent>
                                                        </DropdownMenu>
                                                    </div>
                                                </div>
                                            ))}
                                            
                                        </div>
                                     </AccordionContent>
                                </AccordionItem>
                            ))
                        }
                    </Accordion>
                    <SheetFooter>
                        <Button 
                            type="button"
                            onClick={() => {
                                const url =  new URL(window.location.href)
                                const filterQuery = 
                                    currentFilters
                                        .map((filter: FilterTransformedType) => filter.join("."))
                                        .join(";")
                                console.log(filterQuery)

                                url.searchParams.set('filters', filterQuery)

                                router.get(url)
                                                        
                            }}
                        >
                            Apply filters
                        </Button>
                        <SheetClose onClick={() => setCurrentFilters([...lastFilters])} asChild>
                            <Button variant="outline">Close</Button>
                        </SheetClose>
                    </SheetFooter>
                </SheetContent>
            </Sheet>
            <Tooltip>
                <TooltipTrigger asChild>
                    <Button className="hover:cursor-pointer" size="icon" variant="ghost"><Plus /></Button>
                </TooltipTrigger>
                <TooltipContent>
                    <p>Add new</p>
                </TooltipContent>
            </Tooltip>
        </div>
    )
}
