import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type NavCollapseGroup } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Anchor, BanknoteArrowDown, BookOpen, ChartColumnIncreasing, Folder, HandCoins, LayoutGrid, Layers, LoaderPinwheel, Smile, Landmark } from 'lucide-react';
import AppLogo from './app-logo';

import { relativeUrl } from '@/lib/utils';


const mainNavItems: NavCollapseGroup[] = [
    {
        title: 'Bank',
        icon: Landmark,
        links: [
            { title: "Create new letter of credit", href: relativeUrl(route('lcs.create'))},
            { title: "View letters of credit", href: relativeUrl(route('lcs.index'))},
            { title: "View bank accounts", href: relativeUrl(route('bank_accounts.index'))},
        ],
    },
    {
        title: 'Consignments',
        icon: Anchor,
        links: [
            { title: "Create new consignment", href: relativeUrl(route('consignments.create'))},
            { title: "View consignments", href: relativeUrl(route('consignments.index'))},
            { title: "View containers", href: relativeUrl(route('containers.index'))},
            { title: "View waste", href: relativeUrl(route('waste.index'))},
        ]
    },
    {
        title: 'Customers',
        icon: Smile,
        links: [
            { title: "View customers", href: relativeUrl(route('customers.index'))},
        ]
    },
    {
        title: 'Expenses',
        icon: BanknoteArrowDown,
        links: [
            { title: "View expenses", href: relativeUrl(route('expenses.index'))},
            { title: "Create an expense", href: relativeUrl(route('expenses.create'))},
        ]
    },
    {
        title: 'Orders',
        icon: Layers,
        links: [
            { title: "Create new order", href: relativeUrl(route('orders.create'))},
            { title: "View orders", href: relativeUrl(route('orders.index'))},
            { title: "View payments", href: relativeUrl(route('payments.index'))},
        ]
    },
    {
        title: 'Products',
        icon: LoaderPinwheel,
        links: [
            { title: "View all products", href: relativeUrl(route('tyres.index'))},
            { title: "View current inventory", href: relativeUrl(route('stock.index'))},
        ]
    },
    
    {
        title: 'Reports',
        icon: ChartColumnIncreasing,
        links: [
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
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
