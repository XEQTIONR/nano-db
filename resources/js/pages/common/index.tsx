import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DataTable } from '@/components/data-table';
import { columns as tyreCols } from "@/pages/tyres/components/columns";
import { columns as lcCols } from "@/pages/lcs/components/columns";
import { columns as consignmentCols } from '../consignments/components/columns';
import { columns as containerCols } from '../containers/components/columns';
import { columns as customerCols } from '../customers/components/columns';
import {
  Pagination,
  PaginationContent,
  PaginationItem,
  PaginationLink,
  PaginationNext,
  PaginationPrevious,
} from "@/components/ui/pagination"
import { PaginationMeta } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '',
        href: '',
    },
];


export default function Index<T>({ items, link, title, type } : { 
    items: { 
        data: T[], 
        links: { 
            prev: string | undefined,
            next: string | undefined
        },
        meta: PaginationMeta
    }
    link: string
    title: string
    type: string 
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
        case "tyre":
        default: 
            cols = tyreCols
    }

    breadcrumbs[0].title = title
    breadcrumbs[0].href = link

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col  gap-4 rounded-xl p-4 overflow-x-auto">
                <DataTable columns={cols} data={items.data} meta={items.meta} />

                <div className="flex justify-between w-full">
                    <div className="shrink-0 text-sm">
                        Showing { items.meta.from } to { items.meta.to } of <span className="font-semibold">{ items.meta.total }</span>
                    </div>
                    <div>
                        <Pagination className="justify-end">
                            <PaginationContent >
                                {
                                    items.meta.links.map(({url, label}, index) => {
                                        return (
                                        <PaginationItem>
                                            { index == 0 
                                                && <PaginationPrevious key={index} href={url ?? "#"} /> }
                                            { index == (items.meta.links.length - 1) 
                                                && <PaginationNext key={index} href={url ?? "#"} /> }
                                            { (index > 0 && index < (items.meta.links.length - 1))
                                                && <PaginationLink isActive={parseInt(label) == items.meta.current_page} href={url}>{label}</PaginationLink> 
                                            } 
                                        </PaginationItem>
                                    )})
                                }
                            </PaginationContent>
                        </Pagination>
                    </div>
                </div>
                
            </div>
        </AppLayout>
    );
}
