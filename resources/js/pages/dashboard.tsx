import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { currencyFormat } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import {
  ChartConfig,
  ChartContainer,
  ChartTooltip,
  ChartTooltipContent,
} from "@/components/ui/chart"

import { Activity, BanknoteArrowDown, BanknoteArrowUp, ChartBar, Send, Tag, TrendingUp } from "lucide-react"
import { Area, AreaChart, CartesianGrid, Line, LineChart, XAxis } from "recharts"


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export const description = "A step area chart"

const chartData = [
  { month: "January", desktop: 186 },
  { month: "February", desktop: 305 },
  { month: "March", desktop: 73 },
  { month: "April", desktop: 73 },
  { month: "May", desktop: null },
  { month: "June", desktop: null },
]

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
    expenditure, 
    revenue,
    count_percent,
    count_items_percent,
    revenue_percent,
    expenditure_percent,
    classified2,
    sales,
    sales_percent,
} : {
    count: number, 
    count_items: number, 
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
                </div>
                {/* <div className="grid auto-rows-min gap-4 lg:grid-cols-2 xl:grid-cols-4">
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
                    
                </div> */}
                <div className="grid auto-rows-min gap-4 lg:grid-cols-2 xl:grid-cols-4 w-full">
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
                                <h3 className="font-bold text-2xl lg:text-xl xl:text-lg">Sales</h3>
                            </div>
                            <span className="text-5xl md:text-7xl lg:text-5xl xl:text-4xl font-bold">{currencyFormat('BDT', sales)}</span>
                            <span className="text-lg xl:text-sm">{sales_percent.toFixed(1)}% than yesterday</span>
                        </div>
                    </div>
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
                </div>
                <div className="flex flex-wrap xl:flex-nowrap gap-4 w-full">
                    <ChartContainer className='w-full xl:w-3/4  border rounded-xl' config={chartConfig}>
                        <LineChart
                            accessibilityLayer
                            data={classified2}
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
                                tickFormatter={(value) => value}
                            />
                            <ChartTooltip
                                cursor={true}
                                content={<ChartTooltipContent 
                                labelFormatter={(label: string) => <span className="">{new Date().toDateString() + " " +label.toUpperCase()}</span>} 
                                // formatter={(val) => <div className="flex w-full justify-between">
                                //     <div className="flex items-center gap-1.5">
                                //         <span className='text-teal-500'><Send size={10} /></span>    
                                //         <span className="font-bold">
                                //             Total sold:
                                //         </span>
                                //     </div>
                                //     <span className='font-mono'>{currencyFormat('BDT', val)}</span>
                                // </div>}
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
                    <div className='w-full xl:w-1/4 relative shrink'>
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
                
            </div>
        </AppLayout>
    );
}
