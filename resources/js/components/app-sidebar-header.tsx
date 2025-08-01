import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { type BreadcrumbItem as BreadcrumbItemType } from '@/types';
import { Separator } from "@/components/ui/separator"
import { Filter, FilterX, Plus, X } from 'lucide-react';

import { Button } from '@/components/ui/button';
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"


export function AppSidebarHeader({ breadcrumbs = [] }: { breadcrumbs?: BreadcrumbItemType[] }) {
    return (
        <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex h-3/5 w-full items-center justify-start gap-1 ">
                <SidebarTrigger className="-ml-1 mr-1" />
                <Separator className="mr-3" orientation="vertical" />
                <div className="w-full grow flex items-center justify-between">
                    <Breadcrumbs breadcrumbs={breadcrumbs} />
                    <div className="flex items-center gap-2 justify-end">
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button className="hover:cursor-pointer text-xs" size="icon" variant="destructive"> <FilterX /> </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Remove filters</p>
                            </TooltipContent>
                        </Tooltip>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button className="hover:cursor-pointer" size="icon" variant="ghost"><Filter /></Button>
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
                </div>
            </div>
        </header>
    );
}
