import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from '@/components/ui/sidebar';
import { type NavItem, type NavCollapseGroup } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@radix-ui/react-collapsible';
import { ChevronRight } from 'lucide-react';
import { useState } from 'react';

export function NavMain({ items = [] }: { items: NavCollapseGroup[] }) {
    const page = usePage();

    const [current, setCurrent] = useState(-1)

    return (
        <SidebarGroup className="px-2 py-0">
            <SidebarGroupLabel>Platform</SidebarGroupLabel>
            <SidebarMenu>
                {items.map((item, index) => (
                    <Collapsible
                        key={item.title}
                        asChild 
                        className="group/collapsible"
                        open={item.isActive ?? (current == index)}
                        onOpenChange={(open) => setCurrent((open ? index : -1))}
                    >
                    <SidebarMenuItem>
                        <CollapsibleTrigger asChild>
                            <SidebarMenuButton tooltip={item.title}>
                                {item.icon && <item.icon />}
                                <span className="overflow-x-visible text-nowrap">{item.title}</span>
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
                    </Collapsible>
                ))}
            </SidebarMenu>
        </SidebarGroup>
    );
}
