import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from "@/components/ui/accordion"
import { Breadcrumbs } from '@/components/breadcrumbs'
import { SidebarTrigger } from '@/components/ui/sidebar'
import { FilterConfig, type BreadcrumbItem as BreadcrumbItemType } from '@/types'
import { Separator } from "@/components/ui/separator"

import { ReactNode, useState } from 'react'

import { Button } from '@/components/ui/button'
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuShortcut,
  DropdownMenuTrigger,
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

export function AppSidebarHeaderControls({ filters, filterOptions } : { filters: string[][], filterOptions: FilterConfig[] }) {
    console.log('filters', filters)
    console.log('filterOptions', filterOptions)

    const groups = Object.groupBy(filterOptions, ({ group }) => group )

    console.log('groups: ', groups)

    // const transFormFilterParam = (filterOption: FilterConfig) => {

    //     const key = filterOption.key

    //     const [field, op, strVal] : string[] = filters.find((arr) => arr[0] === key) ?? []


    //     if (field) {
    //         switch(filterOption.dataType) {
    //             case "int":
    //                 return [field, op, parseFloat(strVal)]

    //             case "float":
    //                 return [field, op, parseFloat(strVal)]

    //             case "string":
    //             default:
    //                 return [field, op, strVal]
    //         }
    //     }

    //     return [field, op, undefined]
    // }
    
    const trasformFiltersToQuery = ([key, op, val] : [string, string, string]) => {

        // const key = filterOption.key
        // const val = filterOption.
        const ret: unknown[] = [key]
        const filterOption = filterOptions.find((option) => option.key === key)

        if (filterOption) {

            switch(op) {
                case "<":
                    ret.push("lt")
                    break
                case "<=":
                    ret.push("lte")
                    break
                case "=":
                    ret.push("eq")
                    break
                case ">=":
                    ret.push("gte")
                    break
                case ">":
                    ret.push("gt")
                    break
                case "<>":
                    ret.push("ne")
                    break
            }


            switch(filterOption.dataType) {
                case "int":
                    ret.push(parseInt(val))
                    break

                case "float":
                    ret.push(parseFloat(val))
                    break
                    
                case "string":
                default:
                    ret.push(val)
            }
        }
        

        return ret

    }

    const [currentFilters, setCurrentFilters] = useState(() => {
        
        return filters.map((filter) => {
            const [a, b, c] = filter
            return trasformFiltersToQuery([a, b, c])
        })
    })

    const filter = () => {
        console.log('filter:')
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
                                                            field.inputType == "select" && <Combobox type="multiple" />
                                                        }
                                                        
                                                        {
                                                            field.inputType == "date" && <InputCalendar initialDate={currentFilters.find((f) => f[0] === field.key)?.[2]}  id={key} />
                                                        }

                                                        {
                                                            (field.inputType == "number" ||  field.inputType == "text")
                                                            && <Input
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
                                                                <DropdownMenuGroup>
                                                                <DropdownMenuItem disabled>Not Selected</DropdownMenuItem>
                                                                <DropdownMenuItem>
                                                                    Equals
                                                                    <DropdownMenuShortcut>=</DropdownMenuShortcut>
                                                                </DropdownMenuItem>
                                                                <DropdownMenuItem>
                                                                    Doesn't equal
                                                                    <DropdownMenuShortcut>!=</DropdownMenuShortcut>
                                                                </DropdownMenuItem>
                                                                <DropdownMenuItem>
                                                                    Greater Than
                                                                    <DropdownMenuShortcut>{">"}</DropdownMenuShortcut>
                                                                </DropdownMenuItem>
                                                                <DropdownMenuItem>
                                                                    Less Than
                                                                    <DropdownMenuShortcut>{"<"}</DropdownMenuShortcut>
                                                                </DropdownMenuItem>
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
                    {/* <div className="grid flex-1 auto-rows-min gap-6 px-4">
                        <div className="grid gap-3">
                            <Label htmlFor="sheet-demo-username">Username</Label>
                            <Input id="sheet-demo-username" defaultValue="@peduarte" />
                        </div>
                        <div className="grid gap-3">
                            <Label htmlFor="sheet-demo-name">Name</Label>
                            <Input id="sheet-demo-name" defaultValue="Pedro Duarte" />
                        </div>
                        <div className="w-3/4">{JSON.stringify(filterOptions)}</div>
                    </div> */}
                    <SheetFooter>
                        <Button onClick={filter} type="button">Apply filters</Button>
                        <SheetClose asChild>
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
