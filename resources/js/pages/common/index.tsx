import { router } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout';
import { Customer, Filter, FilterConfig, Tyre, type BreadcrumbItem } from '@/types';
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
import { columns as stockCols } from '@/pages/stock/components/columns';
import { columns as wasteCols, filterConfigs as wasteFilters } from '@/pages/waste/components/columns';
import { columns as expensesCols } from '@/pages/expenses/components/columns';
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
import { useEffect, useRef, useState } from 'react';
import { Drawer } from '@/components/ui/drawer';
import TyreCreateForm from '../tyres/components/create-form';
import CustomerCreateForm from '../customers/components/create-form';
import { Search, X } from 'lucide-react';
import { cn } from '@/lib/utils';
import { useDebouncedCallback } from 'use-debounce';

import { useRemember } from '@inertiajs/react'


const perPageOptions: number[] = [
    25, 50, 100, 500
]


export default function Index<T>({ apiToken, items, link, addLink, sortBy, sortDir, title, type, filters = [], breadcrumbsLinks = [], edit = undefined } : { 
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
    filters?: Filter[],
    breadcrumbsLinks?: BreadcrumbItem[]
    edit?: { data: Customer | Tyre }
}) {
    const { notification } = usePage<{ notification : {
        message: string
        link: string
        selected_key?: string
        selected_value?: string
    }}>().props

    const searchInput = useRef(null)
    const [q, setQ] = useRemember("")

    useEffect(() => {
        if (edit) {
            setDrawerOpen(true)
        }
    }, [edit])

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
        if (notification?.selected_value && notification?.selected_key) {
                setSelectedKey(notification.selected_key)
                setSelectedVal(notification.selected_value)
        }
    }, [notification])

    const debounced = useDebouncedCallback(
        async (val: {entries: string[][], filterArr: string[]}) => {
          console.log('d func')
          console.log(val)
          const obj = {}

          val.entries.forEach(([param, val]: string[]) => {
            obj[param] = val
          })

          obj.filters = val.filterArr.join(";")
          const url = route(type+'s.index', obj)
          console.log(url)
          router.visit(url, { preserveState: true })
        },
        1000
    )

    useEffect(() => {
        const params = new URLSearchParams(document.location.search)
        const filterStr = params.get('filters')
        const entries = []

        for (const [key, value] of params.entries()) {
            if (key !== 'filters') {
                entries.push([key, value])
            }
        }

        if (filterStr) {
            let filterArr = filterStr?.split(';') ?? []
            let found = false

            if (q.length > 0) {
                filterArr = filterArr.map((filter: string) => {
                    if (filter.split(".", 3) [0] === '*') {
                        found = true
                        const [f, o] = filter.split(".", 3);

                        return f + '.' + o + '.' + q;
                    }
                    return filter
                })
                if (!found) {
                    filterArr.push('*.like.' + q)
                }
            } else {
                filterArr = filterArr.filter((filter: string) => filter.split(".")[0] !== "*")
            }
            debounced({
                filterArr,
                entries
            })
        } else {
            console.log('else')

            if (q == "") {
                //
            } else {
                debounced({
                    filterArr: ["*.like." + q],
                    entries
                })
            }
        }
    }, [q])
    
    const [selectedVal, setSelectedVal] = useState<string|number|undefined>(notification?.selected_value)
    const [selectedKey, setSelectedKey] = useState<string|undefined>(notification?.selected_key)

    useEffect(() => {
        if (selectedVal && selectedKey) {
            setTimeout(() => {
                setSelectedKey(undefined)
                setSelectedVal(undefined)
            }, 7000)
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
        case "expense":
            cols = expensesCols
            filterConfigs = []
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

    const breadcrumbs = [...breadcrumbsLinks, {title: title, href: link}]

    const [drawerOpen, setDrawerOpen] = useState(false)

    
    return (
        <Drawer onOpenChange={(isOpen) => {
            setDrawerOpen(isOpen)
            if (edit) {
                router.visit(route(type + 's.index'))
            }
        }} open={drawerOpen}>
            <AppLayout breadcrumbs={breadcrumbs} controls={<AppSidebarHeaderControls addLink={addLink} apiToken={apiToken} filters={filters} filterConfigs={filterConfigs} />}>
                <Head title={title} />
                
                <div className="w-full basis-1/10">
                    <div className="mt-6 pl-4 flex gap-4">
                        <h1 className="text-2xl md:text-4xl font-bold">{title}</h1>
                        <div className="text-xl border-2 border-transparent text-neutral-500 focus-within:border-neutral-400 dark:focus-within:border-neutral-500 flex items-center px-1.5 gap-1.5 rounded-lg">
                            <Search className="mx-1 hover:cursor-pointer dark:hover:stroke-neutral-400" onClick={() => {
                                if (searchInput.current)
                                    searchInput.current.focus()
                            }} size={26} />
                            <input 
                                 
                                onChange={({target}) => setQ(target.value)} 
                                ref={searchInput} className="w-full text-neutral-900 dark:text-neutral-200 text-sm border-none outline-none focus:border-none focus:outline-0 ring-0" 
                                type="text" 
                            />
                            <X onClick={() => {{
                                if(searchInput.current) {
                                    searchInput.current.value = ""
                                    setQ("")
                                }
                            }} } className={cn(q.length > 0 ? "" : "opacity-0", "mr-1 hover:cursor-pointer")} size={18} />
                        </div>
                    </div>
                </div>
                <div className="flex basis-9/10 flex-col  gap-4 px-4 pb-4 overflow-x-auto">
                    <div className="overflow-y-scroll max-h-[77vh] rounded-md border">
                        <DataTable selectedValue={selectedVal} primaryKey={selectedKey} columns={cols} data={items.data} meta={items.meta} sortBy={sortBy} sortDir={sortDir} />
                    </div>
                    <div className="flex justify-between w-full">
                        <div className="shrink-0 text-sm flex items-center gap-7">
                            <div>
                                Showing { items.meta.from } to { items.meta.to } of <span className="font-semibold">{ items.meta.total }</span>
                            </div>
                            <div className="hidden md:flex gap-3 items-center">
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
                                <PaginationContent className="hidden md:flex" >
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
                                <PaginationContent className="md:hidden" >
                                    {
                                        items.meta.links.map((paginationItem, index) => {
                                            if (index == 0) {
                                                return (<PaginationItem><PaginationPrevious key={index} href={paginationItem.url ?? "#"} /></PaginationItem>)
                                            } else if (index == (items.meta.links.length - 1)) {
                                                return (<PaginationItem><PaginationNext key={index} href={paginationItem.url ?? "#"} /></PaginationItem>)
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
                        <TyreCreateForm edit={ edit }/>
                    ) 
                }
                {
                    type == 'customer'
                    && (
                        <CustomerCreateForm edit={ edit } />
                    ) 
                }
            </AppLayout>
        </Drawer>
    );
}
