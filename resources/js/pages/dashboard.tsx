import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { currencyFormat } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const today = () => {
    const date = new Date()

    return date.toDateString();
}

export default function Dashboard({
    count, 
    count_items, 
    expenditure, 
    revenue,
    count_percent,
    count_items_percent,
    revenue_percent,
    expenditure_percent,
} : {
    count: number, 
    count_items: number, 
    expenditure: number, 
    revenue: number,
    count_percent: number,
    count_items_percent: number,
    revenue_percent: number,
    expenditure_percent: number,
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="w-full flex justify-between">
                    <h1 className="text-2xl md:text-4xl font-bold mb-4 mt-2">Dashboard</h1>
                </div>
                <div className="grid auto-rows-min gap-4 lg:grid-cols-2 xl:grid-cols-4">
                    <div className="p-5 aspect-video overflow-hidden border-none rounded-xl bg-[#121212]">
                        <div className="flex justify-between flex-col h-full">
                            <div>
                                <h3 className="font-bold text-2xl lg:text-xl xl:text-lg">{new Date().toLocaleDateString('en-US', { weekday: 'long' })}</h3>
                            </div>
                            <span className="text-8xl md:text-7xl font-bold">{new Date().getDate()}<sup>th</sup></span>
                            <span className="text-lg font-semibold">
                                 {new Date().toLocaleDateString('en-US', { month: 'long' })}, {new Date().getFullYear()}
                            </span>
                        </div>
                    </div>
                    
                </div>
                <div className="grid auto-rows-min gap-4 lg:grid-cols-2 xl:grid-cols-4">
                    <div className="p-5 aspect-video overflow-hidden border-none rounded-xl bg-[#121212]">
                        <div className="flex justify-between flex-col h-full">
                            <div>
                                <h3 className="font-bold text-2xl lg:text-xl xl:text-lg">Number of orders</h3>
                            </div>
                            <span className="text-5xl md:text-7xl lg:text-5xl xl:text-4xl font-bold">{count}</span>
                            <span className="text-lg xl:text-sm">{count_percent.toFixed(1)}% than yesterday</span>
                        </div>
                    </div>
                    <div className="p-5 aspect-video overflow-hidden border-none rounded-xl bg-[#121212]">
                        
                        <div className="flex justify-between flex-col h-full">
                            <div>
                                <h3 className="font-bold text-2xl lg:text-xl xl:text-lg">Number of items sold</h3>
                            </div>
                            <span className="text-5xl md:text-7xl lg:text-5xl xl:text-4xl font-bold">{count_items}</span>
                            <span className="text-lg xl:text-sm">{count_items_percent.toFixed(1)}% than yesterday</span>
                        </div>
                    </div>
                    <div className="p-5 aspect-video overflow-hidden border-none rounded-xl bg-[#121212]">
                        <div className="flex justify-between flex-col h-full">
                            <div>
                                <h3 className="font-bold text-2xl lg:text-xl xl:text-lg">Revenue</h3>
                            </div>
                            <span className="text-5xl md:text-7xl lg:text-5xl xl:text-4xl font-bold">{currencyFormat("BDT", revenue)}</span>
                            <span className="text-lg xl:text-sm">{revenue_percent.toFixed(1)}% than yesterday</span>
                        </div>
                    </div>
                    <div className="p-5 aspect-video overflow-hidden border-none rounded-xl bg-[#121212]">
                        <div className="flex justify-between flex-col h-full">
                            <div>
                                <h3 className="font-bold text-2xl lg:text-xl xl:text-lg">Expenditure</h3>
                            </div>
                            <span className="text-5xl md:text-7xl lg:text-5xl xl:text-4xl font-bold">{currencyFormat("BDT", expenditure)}</span>
                            <span className="text-lg xl:text-sm">{expenditure_percent.toFixed(1)}% than yesterday</span>
                        </div>
                    </div>
                </div>
                
            </div>
        </AppLayout>
    );
}
