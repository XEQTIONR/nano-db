import AppLayout from '@/layouts/app-layout';
import { Tyre, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DataTable } from '@/components/data-table';
import { columns } from "./components/columns";
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tyres',
        href: route('tyres.index'),
    },
];


export default function Index({ tyres } : { tyres : { data: Tyre[], links: { prev: string | undefined, next: string | undefined,}, meta: PaginationMeta } }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tyres" />
            <div className="flex h-full flex-1 flex-col  gap-4 rounded-xl p-4 overflow-x-auto">
                <DataTable columns={columns} data={tyres.data} meta={tyres.meta} />

                <div className="flex justify-between w-full">
                    <div className="shrink-0 text-sm">
                        Showing { tyres.meta.from } to { tyres.meta.to } of <span className="font-semibold">{ tyres.meta.total }</span>
                    </div>
                    <div>
                        <Pagination className="justify-end">
                            <PaginationContent >
                                {
                                    tyres.meta.links.map(({url, label}, index) => {
                                        return (
                                        <PaginationItem>
                                            { index == 0 
                                                && <PaginationPrevious key={index} href={url ?? "#"} /> }
                                            { index == (tyres.meta.links.length - 1) 
                                                && <PaginationNext key={index} href={url ?? "#"} /> }
                                            { (index > 0 && index < (tyres.meta.links.length - 1))
                                                && <PaginationLink isActive={parseInt(label) == tyres.meta.current_page} href={url}>{label}</PaginationLink> 
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
