import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from '@/components/ui/sidebar';
import { type NavItem, type NavCollapseGroup } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@radix-ui/react-collapsible';
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu"
import {
  Menubar,
  MenubarCheckboxItem,
  MenubarContent,
  MenubarItem,
  MenubarMenu,
  MenubarRadioGroup,
  MenubarRadioItem,
  MenubarSeparator,
  MenubarShortcut,
  MenubarSub,
  MenubarSubContent,
  MenubarSubTrigger,
  MenubarTrigger,
} from "@/components/ui/menubar"

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
                    { open && <span className="overflow-x-visible text-nowrap">{item.title}</span>}
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

function SidebarDropdownMenuItem ({ item }: {item: NavCollapseGroup}) {
    return (
        <DropdownMenu>
            <DropdownMenuTrigger>
                <SidebarMenuButton tooltip={item.title}>
                    {item.icon && <item.icon />}
                </SidebarMenuButton>
            </DropdownMenuTrigger>
            <DropdownMenuContent side="right" sideOffset={20} align="end">
                <DropdownMenuLabel>{item.title}</DropdownMenuLabel>
                <DropdownMenuSeparator />
                {
                    item.links.map((link) => (
                        <DropdownMenuItem>
                                <Link href={link.href}>
                                    <span className="overflow-x-visible text-nowrap">{link.title}</span>
                                </Link>
                        </DropdownMenuItem>
                    ))
                }
            </DropdownMenuContent>
        </DropdownMenu>
    )
}

function SidebarMenu3Item({ item }: {item: NavCollapseGroup}) {
    return (
      <MenubarMenu>
        <MenubarTrigger className="py-[9px] bg-red-900 border border-neutral-900 flex justify-end">
            {item.icon && <item.icon width={16} height={16} />}
            
        </MenubarTrigger>
        <MenubarContent>
          <MenubarItem>
            New Tab <MenubarShortcut>⌘T</MenubarShortcut>
          </MenubarItem>
          <MenubarItem>
            New Window <MenubarShortcut>⌘N</MenubarShortcut>
          </MenubarItem>
          <MenubarItem disabled>New Incognito Window</MenubarItem>
          <MenubarSeparator />
          <MenubarSub>
            <MenubarSubTrigger>Share</MenubarSubTrigger>
            <MenubarSubContent>
              <MenubarItem>Email link</MenubarItem>
              <MenubarItem>Messages</MenubarItem>
              <MenubarItem>Notes</MenubarItem>
            </MenubarSubContent>
          </MenubarSub>
          <MenubarSeparator />
          <MenubarItem>
            Print... <MenubarShortcut>⌘P</MenubarShortcut>
          </MenubarItem>
        </MenubarContent>
      </MenubarMenu>
   )
}

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>Platform</SidebarGroupLabel>
                {
                    open
                    ? (<SidebarMenu className="flex flex-col">
                        { items.map((item, index) => <SidebarCollapsibleMenuItem item={item} index={index} />) }
                    </SidebarMenu>)
                    : (<>
                        {/* <SidebarMenu className="flex flex-col ">
                            { items.map((item, index) => <SidebarCollapsibleMenuItem item={item} index={index} />) }
                        </SidebarMenu> */}
                        <Menubar className="flex flex-col bg-transparent border-0" asChild={false}>
                            { items.map((item) => (<SidebarMenu3Item item={item} />)) }
                        </Menubar>
                    </>)
                    
                }
        </SidebarGroup>
    );
}
