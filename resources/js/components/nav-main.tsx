import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from '@/components/ui/sidebar';
import { type NavCollapseGroup } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@radix-ui/react-collapsible';

import {
  Menubar,
  MenubarContent,
  MenubarItem,
  MenubarMenu,
  MenubarTrigger,
} from "@/components/ui/menubar"

import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"


import { ChevronRight } from 'lucide-react';
import { useState } from 'react';

import { useSidebar } from '@/components/ui/sidebar';

export function NavMain({ items = [] }: { items: NavCollapseGroup[] }) {
    const page = usePage();

    const [current, setCurrent] = useState(-1)


    const { open } = useSidebar()

function SidebarCollapsibleMenuItem ({ item, index }: {item: NavCollapseGroup, index: number}) {
    return (
    <Collapsible
        key={item.title}
        asChild 
        className="group/collapsible"
        open={item.isActive ?? (current == index)}
        onOpenChange={(opened) => setCurrent((opened ? index : -1))}
    >
        <SidebarMenuItem className="my-1">
            <CollapsibleTrigger asChild>
                <SidebarMenuButton tooltip={item.title}>
                    {item.icon && <item.icon />}
                    {<span className="overflow-x-visible text-nowrap">{item.title}</span>}
                    <ChevronRight className="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90" />
                </SidebarMenuButton>
            </CollapsibleTrigger>
            <CollapsibleContent>
                <SidebarMenuSub>
                    {
                        item.links.map((link) => (
                            <SidebarMenuSubItem>
                                <SidebarMenuSubButton isActive={page.url === link.href}>
                                    <Link href={link.href}>
                                        <span className="overflow-x-visible text-nowrap">{link.title}</span>
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        ))
                    }
                </SidebarMenuSub>
            </CollapsibleContent>
        </SidebarMenuItem>
    </Collapsible>)
}

function SidebarMenubarMenuItem({ item }: {item: NavCollapseGroup}) {
    return (<MenubarMenu>
        <Tooltip>
            <TooltipTrigger>
                <MenubarTrigger className="py-[9px]  border dark:border-neutral-900 dark:hover:bg-neutral-800">
                    {item.icon && <item.icon width={16} height={16} />}
                    
                </MenubarTrigger>
            </TooltipTrigger>
            <TooltipContent side="right">
                <p>{item.title}</p>
            </TooltipContent>
        </Tooltip>
        <MenubarContent>
        {
            item.links.map((link) => (<MenubarItem disabled={page.url === link.href}>
                <Link href={link.href}>
                    <span className="overflow-x-visible text-nowrap">{link.title}</span>
                </Link>
            </MenubarItem>))
        }
        </MenubarContent>
    </MenubarMenu>)
}

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>Platform</SidebarGroupLabel>
            <SidebarMenu className={"flex flex-col " + (!open ? "md:hidden" : "")}>
                { items.map((item, index) => <SidebarCollapsibleMenuItem item={item} index={index} />) }
            </SidebarMenu>
            <Menubar className={"hidden " + (!open ? "md:flex flex-col bg-transparent border-0" : "")} asChild={false}>
                    { items.map((item) => (<SidebarMenubarMenuItem item={item} />)) }
            </Menubar>
        </SidebarGroup>
    );
}
