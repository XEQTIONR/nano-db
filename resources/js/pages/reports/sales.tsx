import DashboardCard from "@/components/ui/dashboard-card";
import { Select, SelectItem, SelectContent, SelectTrigger, SelectValue } from "@/components/ui/select";
import AppLayout from "@/layouts/app-layout";
import { Head } from "@inertiajs/react";
//import { SelectItem } from "@radix-ui/react-select";
import { useState } from "react";

export default function SalesReport({ type, date } : { type: string, date: string}) {

    const breadcrumbs = [{
        title: "Reports", 
        href: route('reports.sales')
    },{
        title: "Sales Report", 
        href: route('reports.sales')
    }]

    const [typ, setTyp] = useState<undefined | string>(undefined)

    return <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Sales Report"></Head>
        <div className="w-full basis-1/10">
            <div className="mt-6 px-4 flex flex-col gap-4">
                <h1 className="text-2xl md:text-4xl font-bold">Sales Report</h1>
                <div className="w-full">
                    <Select value={typ}
                        onValueChange={(x) => setTyp(x)}
                    >
                        <SelectTrigger className="w-50">
                            <SelectValue placeholder="Select a value"/>
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="daily">Daily</SelectItem>
                            <SelectItem value="monthly">Monthly</SelectItem>
                            <SelectItem value="yearly">Yearly</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div className="w-full flex gap-4">
                    <DashboardCard 
                        className="w-1/3"
                        title={type}
                        subtitle="My subtitle"
                        stat={560}
                    />
                    <DashboardCard 
                        className="w-1/3"
                        title="My title"
                        subtitle="My subtitle"
                        stat={560}
                    />
                    <DashboardCard 
                        className="w-1/3"
                        title={date}
                        subtitle="My subtitle"
                        stat={560}
                    />
                </div>
            </div>
        </div>
    </AppLayout>
}