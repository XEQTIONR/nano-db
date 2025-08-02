import { router } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DataTable } from '@/components/data-table';
import { columns as tyreCols } from "@/pages/tyres/components/columns";
import { columns as lcCols } from "@/pages/lcs/components/columns";
import { columns as consignmentCols } from '@/pages/consignments/components/columns';
import { columns as containerCols } from '@/pages/containers/components/columns';
import { columns as customerCols } from '@/pages/customers/components/columns';
import { columns as orderCols } from '@/pages/orders/components/columns';
import { columns as paymentCols } from '@/pages//payments/components/columns';
import { columns as stockCols } from '@/pages//stock/components/columns';
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

import { AppSidebarHeaderControls } from '@/components/app-sidebar-header';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '',
        href: '',
    },
]

const perPageOptions: number[] = [
    25, 50, 100, 500
]


export default function Index<T>({ items, link, sortBy, sortDir, title, type, filters = [] } : { 
    items: { 
        data: T[], 
        links: { 
            prev: string | undefined,
            next: string | undefined
        },
        meta: PaginationMeta
    }
    link: string
    sortBy: string
    sortDir: 'asc' | 'desc'
    title: string
    type: string 
    filters?: string[]
}) {
    let cols = []
    switch (type) {
        case "lc":
            cols = lcCols
            break
        case "consignment":
            cols = consignmentCols
            break
        case "container":
            cols = containerCols
            break
        case "customer":
            cols = customerCols
            break
        case "order":
            cols = orderCols
            break
        case "payment":
            cols = paymentCols
            break
        case "stock":
            cols = stockCols
            break
        case "tyre":
        default: 
            cols = tyreCols
    }

    breadcrumbs[0].title = title
    breadcrumbs[0].href = link

    return (
        <AppLayout breadcrumbs={breadcrumbs} controls={<AppSidebarHeaderControls filters={filters} />}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col  gap-4 rounded-xl p-4 overflow-x-auto">
                
                <DataTable columns={cols} data={items.data} meta={items.meta} sortBy={sortBy} sortDir={sortDir} />

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
        </AppLayout>
    );
}
