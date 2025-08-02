import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { type BreadcrumbItem as BreadcrumbItemType } from '@/types';
import { Separator } from "@/components/ui/separator"

import { ReactNode } from 'react';

import { Button } from '@/components/ui/button';
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"

import { Badge } from '@/components/ui/badge';

import { Filter, FilterX, Plus } from 'lucide-react';


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

export function AppSidebarHeaderControls({ filters } : { filters: string[]}) {
    return (
        <div className="flex items-center gap-2 justify-end">
            {
                filters.length > 0 && 
                (<Tooltip>
                    <TooltipTrigger asChild>
                        <Button className="hover:cursor-pointer text-xs" size="icon" variant="destructive"> <FilterX /> </Button>
                    </TooltipTrigger>
                    <TooltipContent>
                        <p>Remove filters</p>
                    </TooltipContent>
                </Tooltip>)
            }
            
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
