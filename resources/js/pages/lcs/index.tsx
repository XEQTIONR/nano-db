import AppLayout from '@/layouts/app-layout';
import { LetterOfCredit, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DataTable } from '@/components/data-table';
import { columns } from "./components/columns";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Letters of Credit',
        href: route('lcs.index'),
    },
];

export default function LCIndex({ lcs } : { lcs : { data: LetterOfCredit[]}}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="LCs" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <DataTable columns={columns} data={lcs.data} />
            </div>
        </AppLayout>
    );
}
