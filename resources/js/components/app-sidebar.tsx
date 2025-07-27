import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type NavCollapseGroup } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Anchor, BookOpen, ChartColumnIncreasing, Folder, HandCoins, LayoutGrid, Layers, LoaderPinwheel, Smile, Landmark } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavCollapseGroup[] = [
    {
        title: 'Bank',
        icon: Landmark,
        links: [
            { title: "Letter of Credit", href: "/lcs"},
            { title: "Proforma Invoice", href: "/lcs/proforma"},
        ],
    },
    {
        title: 'Consignments',
        icon: Anchor,
        links: [
            { title: "View Consignments", href: "/consignments"},
            { title: "View Containers", href: "/containers"},
            { title: "Add Consignment", href: "/consignments/create"},
            { title: "Add Container", href: "/containers/create"},
            { title: "Add Expense", href: "/expense/create"}
        ]
    },
    {
        title: 'Orders',
        icon: Layers,
        links: [
            { title: "View consignments", href: "/consignments"},
            { title: "View containers", href: "/containers"},
            { title: "Add a consignment", href: "/consignments/create"},
            { title: "Add a container", href: "/containers/create"},
            { title: "Add an expense", href: "/expense/create"}
        ]
    },
    {
        title: 'Payments',
        icon: HandCoins,
        links: [
            { title: "View consignments", href: "/consignments"},
            { title: "View containers", href: "/containers"},
            { title: "Add a consignment", href: "/consignments/create"},
            { title: "Add a container", href: "/containers/create"},
            { title: "Add an expense", href: "/expense/create"}
        ]
    },
    {
        title: 'Products',
        icon: LoaderPinwheel,
        links: [
            { title: "View consignments", href: "/consignments"},
            { title: "View containers", href: "/containers"},
            { title: "Add a consignment", href: "/consignments/create"},
            { title: "Add a container", href: "/containers/create"},
            { title: "Add an expense", href: "/expense/create"}
        ]
    },
    {
        title: 'Customers',
        icon: Smile,
        links: [
            { title: "View consignments", href: "/consignments"},
            { title: "View containers", href: "/containers"},
            { title: "Add a consignment", href: "/consignments/create"},
            { title: "Add a container", href: "/containers/create"},
            { title: "Add an expense", href: "/expense/create"}
        ]
    },
    {
        title: 'Reports',
        icon: ChartColumnIncreasing,
        links: [
            { title: "View consignments", href: "/consignments"},
            { title: "View containers", href: "/containers"},
            { title: "Add a consignment", href: "/consignments/create"},
            { title: "Add a container", href: "/containers/create"},
            { title: "Add an expense", href: "/expense/create"}
        ]
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {

    const page = usePage()
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems.map(item => {
                    if (item.links.some(link => link.href === page.url)) {
                        item.isActive = true
                    }
                    return item
                })} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
