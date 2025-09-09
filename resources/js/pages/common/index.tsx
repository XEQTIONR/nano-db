import { router } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout';
import { Filter, FilterConfig, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DataTable } from '@/components/data-table';
import { columns as bankAccountCols } from "@/pages/bank-accounts/components/columns";
import { columns as tyreCols, filterConfigs as tyreFilters } from "@/pages/tyres/components/columns";
import { columns as lcCols, filterConfigs as lcFilters } from "@/pages/lcs/components/columns";
import { columns as consignmentCols, filterConfigs as consignmentFilters } from '@/pages/consignments/components/columns';
import { columns as containerCols, filterConfigs as containerFilters } from '@/pages/containers/components/columns';
import { columns as customerCols, filterConfigs as customerFilters } from '@/pages/customers/components/columns';
import { columns as orderCols, filterConfigs as orderFilters } from '@/pages/orders/components/columns';
import { columns as paymentCols, filterConfigs as paymentFilters } from '@/pages/payments/components/columns';
import { columns as stockCols } from '@/pages//stock/components/columns';
import { columns as wasteCols, filterConfigs as wasteFilters } from '@/pages/waste/components/columns';
import {
  Pagination,
  PaginationContent,
  PaginationEllipsis,
  PaginationItem,
  PaginationLink,
  PaginationNext,
  PaginationPrevious,
} from "@/components/ui/pagination"
import { PaginationMeta } from '@/types';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { usePage } from '@inertiajs/react'

import { AppSidebarHeaderControls } from '@/components/app-sidebar-header';
import { toast } from 'sonner';
import { useEffect, useState } from 'react';
import { Drawer, DrawerContent } from '@/components/ui/drawer';
import TyreCreateForm from '../tyres/components/create-form';
import CustomerCreateForm from '../customers/components/create-form';



const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '',
        href: '',
    },
]

const perPageOptions: number[] = [
    25, 50, 100, 500
]


export default function Index<T>({ apiToken, items, link, addLink, sortBy, sortDir, title, type, filters = [] } : { 
    apiToken? : string
    items: { 
        data: T[], 
        links: { 
            prev: string | undefined,
            next: string | undefined
        },
        meta: PaginationMeta
    }
    link: string
    addLink?: string
    sortBy: string
    sortDir: 'asc' | 'desc'
    title: string
    type: string 
    filters?: Filter[]
}) {
    const { notification } = usePage<{ notification : {
        message: string
        link: string
        selected_key?: string
        selected_value?: string
    }}>().props

    useEffect(() => {
        if (notification) {
            toast.success(notification.message, {
                action: {
                    label: "View",
                    onClick: () => router.visit(notification.link)
                }
            })

            setDrawerOpen(false)
        }
    }, [notification])
    
    const [selectedVal, setSelectedVal] = useState<string|number|undefined>(notification?.selected_value)
    const [selectedKey, setSelectedKey] = useState<string|undefined>(notification?.selected_key)

    useEffect(() => {
        if (selectedVal && selectedKey) {
            setTimeout(() => {
                setSelectedKey(undefined)
                setSelectedVal(undefined)
            }, 20000)
        }
    }, [selectedVal, selectedKey])
    let cols = []
    let filterConfigs: FilterConfig[] = orderFilters
    switch (type) {
        case "bank_account":
            cols = bankAccountCols
            filterConfigs = []
            break
        case "lc":
            cols = lcCols
            filterConfigs = lcFilters
            break
        case "consignment":
            cols = consignmentCols
            filterConfigs = consignmentFilters
            break
        case "container":
            cols = containerCols
            filterConfigs = containerFilters
            break
        case "customer":
            cols = customerCols
            filterConfigs = customerFilters
            break
        case "order":
            cols = orderCols
            filterConfigs = orderFilters
            break
        case "payment":
            cols = paymentCols
            filterConfigs = paymentFilters
            break
        case "stock":
            cols = stockCols
            break
        case "waste":
            cols = wasteCols
            filterConfigs = wasteFilters
            break
        case "tyre":
        default: 
            cols = tyreCols
            filterConfigs = tyreFilters
    }

    breadcrumbs[0].title = title
    breadcrumbs[0].href = link
    const [drawerOpen, setDrawerOpen] = useState(false)
    return (
        <Drawer onOpenChange={(isOpen) => setDrawerOpen(isOpen)} open={drawerOpen}>
            <AppLayout breadcrumbs={breadcrumbs} controls={<AppSidebarHeaderControls addLink={addLink} apiToken={apiToken} filters={filters} filterConfigs={filterConfigs} />}>
                <Head title={title} />
                <div className="flex h-full flex-1 flex-col  gap-4 rounded-xl p-4 overflow-x-auto">
                    <DataTable selectedValue={selectedVal} primaryKey={selectedKey} columns={cols} data={items.data} meta={items.meta} sortBy={sortBy} sortDir={sortDir} />

                    <div className="flex justify-between w-full">
                        <div className="shrink-0 text-sm flex items-center gap-7">
                            <div>
                                Showing { items.meta.from } to { items.meta.to } of <span className="font-semibold">{ items.meta.total }</span>
                            </div>
                            <div className="flex gap-3 items-center">
                                <Select
                                    defaultValue={items.meta.per_page.toString()}
                                    onValueChange={(val) => {
                                        const url = new URL(window.location.href)

                                        url.searchParams.set('perPage', val)
                                        url.searchParams.set('page', '1')

                                        router.get(url)
                                    }}
                                >
                                    <SelectTrigger className="w-20">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                    {
                                        perPageOptions.includes(items.meta.per_page)
                                        ? perPageOptions.map(perPage => <SelectItem value={perPage.toString()}>{perPage}</SelectItem>)
                                        : [items.meta.per_page, ... perPageOptions].sort((a, b) => a-b)
                                            .map(perPage => <SelectItem value={perPage.toString()}>{perPage}</SelectItem>)
                                    }
                                    </SelectContent>
                                </Select>
                                <span>per page</span>
                            </div>
                        </div>
                        <div>
                            <Pagination className="justify-end">
                                <PaginationContent >
                                    {
                                        items.meta.links.map((paginationItem, index) => {
                                            if (index == 0) {
                                                return (<PaginationItem><PaginationPrevious key={index} href={paginationItem.url ?? "#"} /></PaginationItem>)
                                            } else if (index == (items.meta.links.length - 1)) {
                                                return (<PaginationItem><PaginationNext key={index} href={paginationItem.url ?? "#"} /></PaginationItem>)
                                            } else if (paginationItem.label == "..." && paginationItem.url == null) {
                                                return (<PaginationItem><PaginationEllipsis key={index} /></PaginationItem>)
                                            } else {
                                                return (<PaginationItem>
                                                    <PaginationLink isActive={ parseInt(paginationItem.label) == items.meta.current_page} href={paginationItem.url}>
                                                            {paginationItem.label}
                                                    </PaginationLink>
                                                </PaginationItem>)
                                            }
                                        })
                                    }
                                </PaginationContent>
                            </Pagination>
                        </div>
                    </div>
                    
                </div>
                {
                    type == 'tyre'
                    && (
                        <TyreCreateForm />
                    ) 
                }
                {
                    type == 'customer'
                    && (
                        <CustomerCreateForm />
                    ) 
                }
            </AppLayout>
        </Drawer>
    );
}
