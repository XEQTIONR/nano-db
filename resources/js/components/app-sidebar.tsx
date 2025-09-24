import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type NavCollapseGroup } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Anchor, BanknoteArrowDown, BookOpen, ChartColumnIncreasing, Folder, HandCoins, LayoutGrid, Layers, LoaderPinwheel, Smile, Landmark } from 'lucide-react';
import AppLogo from './app-logo';
import { useEffect, useState } from 'react';


const relativeUrl = (url: string) => url.slice((window.location.protocol + "//" + window.location.hostname + ((window.location.port.length > 0) ? (":" + window.location.port) : "")).length)

const mainNavItems = (): NavCollapseGroup[] => [
    {
        title: 'Bank',
        icon: Landmark,
        links: [
            { title: "Letters of credit", href: relativeUrl(route('lcs.index'))},
            { title: "Bank accounts", href: relativeUrl(route('bank_accounts.index'))},
        ],
    },
    {
        title: 'Consignments',
        icon: Anchor,
        links: [
            { title: "Consignments", href: relativeUrl(route('consignments.index'))},
            { title: "Containers", href: relativeUrl(route('containers.index'))},
            { title: "Waste", href: relativeUrl(route('waste.index'))},
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
            { title: "Create new expense", href: relativeUrl(route('expenses.create'))},
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
            { title: "All products", href: relativeUrl(route('tyres.index'))},
            { title: "Current inventory", href: relativeUrl(route('stocks.index'))},
        ]
    },
    
    {
        title: 'Reports',
        icon: ChartColumnIncreasing,
        links: [
            { title: "Summary", href: relativeUrl(route('reports.summary')) },
            { title: "Sales", href: relativeUrl(route('reports.sales')) },
            { title: "Revenue", href: relativeUrl(route('reports.revenue')) },
            { title: "Expense", href: relativeUrl(route('reports.expenses')) },

        ]
    },
];

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/react-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#react',
    //     icon: BookOpen,
    // },
];

export function AppSidebar() {

    const page = usePage()

    const [navItems, setNavItems] = useState(() => mainNavItems().map(item => {
        if (item.links.some(link => page.url.startsWith(link.href))) {
            item.isActive = true
        }
        return item
    }))

    useEffect(() => {
        setNavItems(() => mainNavItems().map(item => {
            if (item.links.some(link => page.url.startsWith(link.href))) {
                item.isActive = true
            }
            return item
        }))
    }, [page.url])
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
                <NavMain items={navItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
