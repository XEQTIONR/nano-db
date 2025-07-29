import AppLayout from '@/layouts/app-layout';
import { LetterOfCredit, type BreadcrumbItem } from '@/types';
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
        title: 'Letters of Credit',
        href: route('lcs.index'),
    },
];


export default function Index({ lcs } : { lcs : { data: LetterOfCredit[], links: { prev: string | undefined, next: string | undefined,}, meta: PaginationMeta } }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="LCs" />
            <div className="flex h-full flex-1 flex-col  gap-4 rounded-xl p-4 overflow-x-auto">
                <DataTable columns={columns} data={lcs.data} meta={lcs.meta} />

                <div className="flex justify-between w-full">
                    <div className="shrink-0 text-sm">
                        Showing { lcs.meta.from } to { lcs.meta.to } of <span className="font-semibold">{ lcs.meta.total }</span>
                    </div>
                    <div>
                        <Pagination className="justify-end">
                            <PaginationContent >
                                {
                                    lcs.meta.links.map(({url, label}, index) => {
                                        return (
                                        <PaginationItem>
                                            { index == 0 
                                                && <PaginationPrevious key={index} href={url ?? "#"} /> }
                                            { index == (lcs.meta.links.length - 1) 
                                                && <PaginationNext key={index} href={url ?? "#"} /> }
                                            { (index > 0 && index < (lcs.meta.links.length - 1))
                                                && <PaginationLink isActive={parseInt(label) == lcs.meta.current_page} href={url}>{label}</PaginationLink> 
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
