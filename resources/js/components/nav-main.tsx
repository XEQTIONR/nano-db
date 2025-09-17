import { SidebarGroup, SidebarGroupContent, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem, useSidebar } from '@/components/ui/sidebar';
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

import { relativeUrl } from '@/lib/utils';

export function NavMain({ items = [] }: { items: NavCollapseGroup[] }) {
    const page = usePage();
    const iconSize = 16;
    const iconStroke = 2;
    const colorClasses = "text-neutral-800 dark:text-neutral-100"
    const [current, setCurrent] = useState(-1);

    const { open } = useSidebar()


    function SidebarCollapsibleMenuItem ({ item, index }: {item: NavCollapseGroup, index: number}) {
        return (
        <Collapsible
            key={item.title}
            asChild 
            className="group/collapsible"
            open={index == current || item.links.some((link) => relativeUrl(window.location.href).startsWith(link.href))}
            onOpenChange={(opened) => opened ? setCurrent(index) : null}
        >
            <SidebarMenuItem>
                <CollapsibleTrigger asChild>
                    <SidebarMenuButton tooltip={item.title}>
                        {item.icon && <span><item.icon className={colorClasses} size={iconSize}  strokeWidth={iconStroke} /></span>}
                        {<span className={"overflow-x-visible text-nowrap font-normal " + colorClasses}>{item.title}</span>}
                        <ChevronRight className={"ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90 "
                            + colorClasses
                        } />
                    </SidebarMenuButton>
                </CollapsibleTrigger>
                <CollapsibleContent>
                    <SidebarMenuSub>
                        {
                            item.links.map((link) => (
                                <SidebarMenuSubItem>
                                    <SidebarMenuSubButton isActive={page.url === link.href || page.url.startsWith(link.href)}>
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
            <MenubarTrigger ref={elem} className="py-[9px]   hover:bg-neutral-100 dark:hover:bg-neutral-800">
                {
                    !isOpen 
                        ? (<Tooltip>
                            <TooltipTrigger>{item.icon && <item.icon className={colorClasses} size={iconSize} strokeWidth={iconStroke} />}</TooltipTrigger>
                            <TooltipContent side="right">{item.title}</TooltipContent>
                        </Tooltip>)
                        : (item.icon && <item.icon className={colorClasses} size={iconSize} strokeWidth={iconStroke} />)
                }
            </MenubarTrigger>
            <MenubarContent side="right">
                <MenubarItem disabled><span className="text-xs uppercase font-thin tracking-widest">{item.title}</span></MenubarItem>
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
            <SidebarMenu className={open ? "flex flex-col mt-4 " : "hidden"}>
                <SidebarMenuItem>
                    <Link href={route('dashboard')}>
                        <SidebarMenuButton isActive={route().current('dashboard')} tooltip="Dashboard">
                            <LayoutGrid className={colorClasses} size={iconSize}  strokeWidth={iconStroke} />
                            <span className={"overflow-x-visible text-nowrap font-normal " + colorClasses}>Dashboard</span>
                        </SidebarMenuButton>
                    </Link>
                </SidebarMenuItem>
                { items.map((item, index) => <SidebarCollapsibleMenuItem item={item} index={index} />) }
            </SidebarMenu>
            
            <Menubar className={"hidden mt-6 " + (!open ? "md:flex flex-col items-start bg-transparent p-0 border-none shadow-none" : "")} asChild={false}>
                    <MenubarMenu>
                        <MenubarTrigger className="py-[9px]   hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <Tooltip>
                                <TooltipTrigger>
                                    <Link href={route('dashboard')}>
                                        <LayoutGrid className={colorClasses} size={iconSize}  strokeWidth={iconStroke} />
                                    </Link>
                                </TooltipTrigger>
                                <TooltipContent side="right">Dashboard</TooltipContent>
                            </Tooltip>
                        </MenubarTrigger>
                    </MenubarMenu>
                    { items.map((item) => (<SidebarHoveringMenuItem item={item} />)) }
            </Menubar>
            </SidebarGroupContent>
        </SidebarGroup>
    );
}
