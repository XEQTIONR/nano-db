import { SidebarGroup, SidebarGroupContent, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem, SidebarMenuSubLabel, useSidebar } from '@/components/ui/sidebar';
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


import { ChevronRight, LayoutGrid } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';

import { cn } from '@/lib/utils';

export function NavMain({ items = [] }: { items: NavCollapseGroup[] }) {
    const page = usePage();
    const iconSize = 16;
    const iconStroke = 2;
    const colorClasses = "text-neutral-800 dark:text-neutral-100"
    const [current, setCurrent] = useState(-1);


    const { open } = useSidebar();

    function SidebarCollapsibleMenuItem ({ item, index }: {item: NavCollapseGroup, index: number}) {
        return (
        <Collapsible
            key={item.title}
            asChild 
            className="group/collapsible"
            open={item.isActive ?? (current == index)}
            onOpenChange={(opened) => setCurrent((opened ? index : -1))}
        >
            <SidebarMenuItem>
                <CollapsibleTrigger asChild>
                    <SidebarMenuButton className="cursor-pointer" tooltip={item.title}>
                        {item.icon && <span><item.icon className={colorClasses} size={iconSize}  strokeWidth={iconStroke} /></span>}
                        {<span className={cn("overflow-x-visible text-nowrap font-normal mx-2", colorClasses)}>{item.title}</span>}
                        <ChevronRight className={cn(
                            "ml-auto transition-all duration-200 group-data-[state=open]/collapsible:rotate-90",
                            colorClasses
                        )} />
                    </SidebarMenuButton>
                </CollapsibleTrigger>
                <CollapsibleContent className="pt-0.5">
                    <SidebarMenuSub> 
                        {
                            item.links.map((link) => (
                                <Link href={link.href}>
                                    <SidebarMenuSubItem>
                                        <SidebarMenuSubLabel isActive={page.url === link.href || (page.url.startsWith(link.href) && !page.url.endsWith('create'))}>
                                                <span className="overflow-x-visible text-nowrap">{link.title}</span>
                                        </SidebarMenuSubLabel>
                                    </SidebarMenuSubItem>
                                </Link>
                            ))
                        }
                    </SidebarMenuSub>
                </CollapsibleContent>
            </SidebarMenuItem>
        </Collapsible>)
    }

    function SidebarHoveringMenuItem({ item }: {item: NavCollapseGroup}) {
        const elem = useRef<HTMLButtonElement>(null);
        const [isOpen, setIsOpen] = useState(false);

        useEffect(() => {
            if (elem.current) {
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function() {
                        const state = (elem.current?.getAttribute('data-state'))
                        if (isOpen != (state === 'open')) {
                            setIsOpen(state === 'open') 
                        }
                    });
                });
                observer.observe(elem.current, {attributes: true})

                return () => observer.disconnect()
            }
        }, [isOpen])
        return (<MenubarMenu>
            <MenubarTrigger ref={elem} className={cn(
                "py-[9px] hover:bg-accent cursor-pointer",
                item.isActive && "bg-accent "
            )}>
                {
                    !isOpen 
                        ? (<Tooltip>
                            <TooltipTrigger className="cursor-pointer">{item.icon && <item.icon className={colorClasses} size={iconSize} strokeWidth={iconStroke} />}</TooltipTrigger>
                            <TooltipContent side="right">{item.title}</TooltipContent>
                        </Tooltip>)
                        : (item.icon && <item.icon className={colorClasses} size={iconSize} strokeWidth={iconStroke} />)
                }
            </MenubarTrigger>
            <MenubarContent side="right">
                <MenubarItem disabled><span className="text-xs font-semibold">{item.title}</span></MenubarItem>
                {
                    item.links.map((link) => (<MenubarItem disabled={page.url === link.href}>
                        <Link href={link.href}>
                            <span className="overflow-x-visible text-nowrap text-sm">{link.title}</span>
                        </Link>
                    </MenubarItem>))
                }
            </MenubarContent>
        </MenubarMenu>)
    }

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupContent>
            <SidebarMenu className={"flex flex-col mt-4 " + (!open ? "md:hidden" : "")}>
                <SidebarMenuItem>
                    <Link href={route('dashboard')}>
                        <SidebarMenuButton className="cursor-pointer" isActive={route().current('dashboard')} tooltip="Dashboard">
                            <LayoutGrid className={colorClasses} size={iconSize}  strokeWidth={iconStroke} />
                            <span className={"overflow-x-visible text-nowrap font-normal mx-2 " + colorClasses}>Dashboard</span>
                        </SidebarMenuButton>
                    </Link>
                </SidebarMenuItem>
                { items.map((item, index) => <SidebarCollapsibleMenuItem item={item} index={index} />) }
            </SidebarMenu>
            
            <Menubar className={cn(
                "hidden mt-4 ",
                (!open && "md:flex flex-col items-start bg-transparent p-0 border-none shadow-none"))} asChild={false}
            >
                <Link href={route('dashboard')}>
                    <MenubarMenu>
                        <MenubarTrigger className={cn("py-[9px] hover:bg-accent cursor-pointer", route().current('dashboard') && 'bg-accent')}>
                            <Tooltip>
                                <TooltipTrigger className="cursor-pointer">
                                        <LayoutGrid className={colorClasses} size={iconSize}  strokeWidth={iconStroke} />
                                </TooltipTrigger>
                                <TooltipContent side="right">Dashboard</TooltipContent>
                            </Tooltip>
                        </MenubarTrigger>
                    </MenubarMenu>
                </Link>
                { items.map((item) => (<SidebarHoveringMenuItem item={item} />)) }
            </Menubar>
            </SidebarGroupContent>
        </SidebarGroup>
    );
}
