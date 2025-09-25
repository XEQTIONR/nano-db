import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { currencyFormat } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import DashboardCard from '@/components/ui/dashboard-card';
import {
  ChartConfig,
  ChartContainer,
  ChartTooltip,
  ChartTooltipContent,
} from "@/components/ui/chart"

import { Activity, BanknoteArrowDown, BanknoteArrowUp, ChartBar, Send, Tag, TrendingUp } from "lucide-react"
import { Area, AreaChart, CartesianGrid, Line, LineChart, XAxis, YAxis } from "recharts"
import AppLogoIcon from '@/components/app-logo-icon';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export const description = "A step area chart"

const chartConfig = {
  sumOrderGrandTotal: {
    label: "Total sold",
    
    icon: Tag,
  },

  sumPayments: {
    label: "Revenue",
    
    icon: BanknoteArrowUp
  },

  sumExpenses: {
    label: "Expenditure",
    
    icon: BanknoteArrowDown
  },
} satisfies ChartConfig

export default function Dashboard({
    count, 
    count_items, 
    chart_data,
    expenditure, 
    revenue,
    count_percent,
    count_items_percent,
    revenue_percent,
    expenditure_percent,
    sales,
    sales_percent,
} : {
    count: number, 
    count_items: number,
    chart_data: [], 
    expenditure: number, 
    revenue: number,
    count_percent: number,
    count_items_percent: number,
    revenue_percent: number,
    expenditure_percent: number,
    sales: number,
    sales_percent: number,
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex flex-col gap-4 max-h-[92vh] w-full overflow-y-scroll items-start rounded-xl p-4">
                <div className="w-full flex justify-between">
                    <h1 className="text-2xl md:text-4xl font-bold mb-4 mt-2">Dashboard</h1>
                    <AppLogoIcon className='bg-amber-50' size='80' />
                </div>
                <div className='w-full flex gap-4 items-start flex-wrap xl:flex-nowrap'>
                    <div className="flex flex-wrap gap-4 w-full xl:w-3/4">
                        <div className="flex gap-4 w-full flex-col xl:flex-row">
                            <DashboardCard 
                                className="w-full xl:w-1/3"
                                
                                

                                title="Sales"
                                    stat={sales}
                                    decimalPlaces={2}
                                    currencyCode="BDT"
                                    subtitle={
                                        (sales_percent > 0 ? "+" : "")
                                        + sales_percent.toFixed(1) + "% than yesterday"
                                    }
                            />
                            <div className='flex flex-col md:flex-row gap-4 xl:w-2/3'>
                                <DashboardCard 
                                    className="w-full"
                                    title="Revenue"
                                    stat={revenue}
                                    decimalPlaces={2}
                                    currencyCode="BDT"
                                    subtitle={
                                        (revenue_percent > 0 ? "+" : "")
                                        + revenue_percent.toFixed(1) + "% than yesterday"
                                    }
                                />
                                <DashboardCard 
                                    className="w-full"
                                    title="Expenditure"
                                    stat={expenditure}
                                    currencyCode="BDT"
                                    currencySymbolColorClass='text-rose-500 dark:text-rose-700'
                                    decimalPlaces={2}
                                    subtitle={
                                        (expenditure_percent > 0 ? "+" : "")
                                        + expenditure_percent.toFixed(1) + "% than yesterday"
                                    }
                                />
                            </div>
                        </div>
                        
                        <ChartContainer className='w-full h-[50vh] md:h-[60vh] border rounded-xl' config={chartConfig}>    
                            <LineChart
                                accessibilityLayer
                                data={chart_data}
                                margin={{
                                    left: 24,
                                    right: 24,
                                    top: 24,
                                    bottom: 24,
                                }}
                            >
                                <CartesianGrid vertical={false} />
                                <XAxis
                                    dataKey="hours"
                                    tickLine={true}
                                    axisLine={false}
                                    tickMargin={10}
                                    // minTickGap={50}
                                    tickFormatter={(value) => value.toUpperCase()}
                                />
                                <YAxis mirror tickFormatter={(value) =>  value == 0 ? "" : currencyFormat("BDT", value)} />
                                <ChartTooltip
                                    cursor={true}
                                    content={<ChartTooltipContent 
                                    labelFormatter={(label: string) => <span className="">{new Date().toDateString() + " " +label.toUpperCase()}</span>} 
                                    className='pb-2 min-w-3xs' />}
                                />
                                <Line
                                    dataKey="sumOrderGrandTotal"
                                    type="linear"
                                    stroke="var(--chart-4)"
                                    strokeWidth={2}
                                    dot={false}
                                />
                                <Line
                                    dataKey="sumPayments"
                                    type="linear"
                                    stroke="var(--chart-2)"
                                    strokeWidth={2}
                                    dot={false}
                                />
                                <Line
                                    dataKey="sumExpenses"
                                    type="linear"
                                    stroke="var(--chart-5)"
                                    strokeWidth={2}
                                    dot={false}
                                />
                            </LineChart>
                        </ChartContainer>
                    </div>
                    <div className="flex flex-col md:flex-row xl:flex-col gap-4 w-full xl:w-1/4">
                        <DashboardCard 
                            className="w-full md:w-1/2 xl:w-full"
                            title="# of items sold"
                            stat={count_items}
                            subtitle={count_items_percent.toFixed(1) + "% than yesterday"}
                        />
                        <DashboardCard 
                            className="w-full md:w-1/2 xl:w-full"
                            title="# of orders"
                                stat={count}
                                subtitle={count_percent.toFixed(1) + "% than yesterday"}

                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
