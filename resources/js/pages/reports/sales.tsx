import AppLayout from "@/layouts/app-layout";
import { Button } from "@/components/ui/button";
import DashboardCard from "@/components/ui/dashboard-card";
import { Card, CardAction, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { CartesianGrid, Line, LineChart, XAxis, YAxis } from "recharts"
import {
  ChartConfig,
  ChartContainer,
  ChartTooltip,
  ChartTooltipContent,
} from "@/components/ui/chart"
import { ChevronLeft, ChevronLeftIcon, ChevronRight, ChevronRightIcon, LoaderCircleIcon, Tag } from "lucide-react";
import { currencyFormat } from "@/lib/utils";
import { Head, router } from "@inertiajs/react";
import { Order } from "@/types";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { useState } from "react";
import { Pagination, PaginationNext, PaginationPrevious } from "@/components/ui/pagination";

const chartConfig = {
  sumOrderGrandTotal: {
    label: "Total sold today",
    
    icon: Tag,
  },
  sumLastOrderGrandTotal: {
    label: "Total sold yesterday",
    
    icon: Tag,
  },
} satisfies ChartConfig

const reportTypes = [ 'daily', 'monthly', 'yearly' ]

type ReportType = 'daily' | 'monthly' | 'yearly'

const reportTypeMappings = {
    daily: 'day',
    monthly: 'month',
    yearly: 'year'
}


export default function SalesReport({ 
    type, 
    chart_data, 
    date, 
    count, 
    count_percent, 
    count_items, 
    count_items_percent, 
    orders,
    routeName, 
    sales, 
    sales_percent  
} : { 
    type: ReportType, 
    chart_data: [], 
    date: string, 
    count: number, 
    count_percent: number,
    count_items: number,
    count_items_percent: number,
    orders: {data: Order[]},
    routeName: string,
    sales: number,
    sales_percent: number
})  {

    const breadcrumbs = [{
        title: "Reports", 
        href: route('reports.sales')
    },{
        title: "Sales Report", 
        href: route('reports.sales')
    },{
        title: type.charAt(0).toUpperCase() + type.slice(1) + " ", 
        href: route('reports.sales', { type })
    },{
        title: type == "daily" 
            ? new Date(date).toDateString()  
            : type == "monthly"
                ? new Intl.DateTimeFormat('en-IN', { month: "short", year: "numeric" }).format(new Date(date))
                : new Date(date).getFullYear(),

        href: route('reports.revenue', { date, type })
    }]

    const go = (unit: number) => {
        const localDate = new Date(date)

        switch(type) {
            case "yearly":
                localDate.setDate(1)
                localDate.setFullYear(localDate.getFullYear() + unit)
            break;
            case "monthly":
                localDate.setDate(1)
                localDate.setMonth(localDate.getMonth() + unit)
            break;
            case "daily":
                localDate.setDate(localDate.getDate() + unit)
            break;
        }

        router.visit(route(routeName, {
            date: localDate.toISOString().split('T')[0],
            type: type
        }))
    }

    const goNext = () => go(1)

    const goPrevious = () => go(-1)

    const dateOptions = {
        weekday: "short",
        month: "short",
        day: "numeric",
    };

    const nextLabel = () => {

        const d = new Date(date)

        switch(type) {

            case "yearly":
                if (d.getFullYear() === (new Date().getFullYear())) {
                    return "Next Year"
                } else if (d.getFullYear() === (new Date().getFullYear() - 1)) {
                    return "Current year"
                }

                d.setFullYear(d.getFullYear() + 1)
                return  new Intl.DateTimeFormat("en-IN", { year: "numeric" }).format(d)
            case "monthly":
                if (d.getFullYear() === (new Date().getFullYear())
                    && d.getMonth() === (new Date().getMonth())) {
                    return "Next Month"
                } else if (
                    (
                        d.getFullYear() === (new Date().getFullYear())
                        && d.getMonth() === (new Date().getMonth() - 1)
                    )
                    ||
                    (
                        d.getFullYear() === (new Date().getFullYear() - 1)
                        && d.getMonth() === 12
                    )
                ) {
                    return "Current Month"
                }

                d.setMonth(d.getMonth() + 1)
                return  new Intl.DateTimeFormat("en-IN", { month: "short", year: "numeric" }).format(d)
            
            case "daily":
            default:
                if (d.toDateString() === new Date().toDateString()) {
                    return "Tomorrow"
                } 
                
                if (true) {
                    const dt = new Date()
                    dt.setDate(dt.getDate() - 1)
                    if (d.toDateString() === dt.toDateString()) {
                        return "Today"
                    }
                }
                
                d.setDate(d.getDate() + 1)
                return  new Intl.DateTimeFormat("en-IN", dateOptions).format(d)
        }

        
    }

    const previousLabel = () => {

        const d = new Date(date)

        switch(type) {

            case "yearly":
                if (d.getFullYear() === (new Date().getFullYear())) {
                    return "Last Year"
                } else if (d.getFullYear() === (new Date().getFullYear() + 1)) {
                    return "Current year"
                }
                
                d.setFullYear(d.getFullYear() - 1)
                return  new Intl.DateTimeFormat("en-IN", { year: "numeric" }).format(d)
                
            case "monthly":
                if (d.getFullYear() === (new Date().getFullYear())
                    && d.getMonth() === (new Date().getMonth())) {
                    return "Last Month"
                } else if (
                    (
                        d.getFullYear() === (new Date().getFullYear())
                        && d.getMonth() === (new Date().getMonth() + 1)
                    )
                    ||
                    (
                        d.getFullYear() === (new Date().getFullYear() + 1)
                        && d.getMonth() === 1
                    )
                ) {
                    return "Current Month"
                }

                d.setMonth(d.getMonth() - 1)
                return  new Intl.DateTimeFormat("en-IN", { month: "short", year: "numeric" }).format(d)
            case "daily":
            default:
                if (d.toDateString() === new Date().toDateString()) {
                    return "Yesterday"
                }

                if (true) {
                    const dt = new Date()
                    dt.setDate(dt.getDate() + 1)
                    if (d.toDateString() === dt.toDateString()) {
                        return "Today"
                    }
                }
                
                d.setDate(d.getDate() - 1)
                return  new Intl.DateTimeFormat("en-IN", dateOptions).format(d)
        }
    }

    const orderPerPage = 10
    const [orderPage, setOrderPage] = useState(0)

    return <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Sales Report"></Head>
        <div className="w-full basis-1/10 ">
            <div className="p-4 flex flex-col gap-4 overflow-y-scroll">
                <h1 className="text-2xl md:text-4xl font-bold mt-2">Sales Report <span className="text-muted text-2xl">
                        {type == "daily" && date}
                        { type == "monthly" && new Date(date).toLocaleString('default', { month: 'long', year: "numeric" })}
                        { type == "yearly" && new Date(date).toLocaleString('default', { year: "numeric" })}
                    </span>
                </h1>
                <div className="w-full">
                    <Tabs defaultValue={type} className="w-full">
                        <div className="w-full flex justify-between">
                            <TabsList className="mb-1 print:hidden">
                                {
                                    reportTypes.map((reportType) => (
                                        <TabsTrigger 
                                            className="cursor-pointer" 
                                            value={reportType}
                                            onClick={() => {
                                                router.visit(route(routeName, {
                                                    type: reportType
                                                }))
                                            }}
                                        >
                                            {reportType.charAt(0).toUpperCase() + reportType.slice(1)}
                                        </TabsTrigger>
                                    ))
                                }
                            </TabsList>
                            <div className="flex justify-end gap-2 print:hidden">

                                <Button type="button" className="cursor-pointer" onClick={goPrevious} variant="secondary">
                                    <ChevronLeftIcon /> {previousLabel()}
                                </Button>
                                <Button type="button" className="cursor-pointer" onClick={goNext} variant="secondary">
                                    {nextLabel()} <ChevronRightIcon />
                                </Button>
                            </div>
                        </div>
                        {
                            reportTypes.map((reportType) => {
                                if (reportType == type) {
                                    return <TabsContent value={reportType}>
                                        <div className="w-full flex flex-col lg:flex-row gap-4">
                                            <div className="w-full lg:w-2/3 flex flex-col gap-4">
                                                <div className="w-full flex gap-4">
                                                    <DashboardCard 
                                                        className="w-1/2"
                                                        title="Sales"
                                                        subtitle={sales_percent.toFixed(2) + "% since last " + reportTypeMappings[type]}
                                                        stat={sales}
                                                        currencyCode="BDT"
                                                        decimalPlaces={2}
                                                    />
                                                    <DashboardCard 
                                                        className="w-1/2"
                                                        title="# of orders"
                                                        subtitle={count_percent.toFixed(2) + "% since last " + reportTypeMappings[type]}
                                                        stat={count}
                                                    />
                                                </div>
                                                <div className="w-full">
                                                    <ChartContainer className='w-full h-[50vh] md:h-[55vh] border rounded-xl' config={chartConfig}>    
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
                                                                tickFormatter={(value) => value}
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
                                                                stroke="var(--chart-3)"
                                                                strokeWidth={2}
                                                                dot={false}
                                                            />
                                                            <Line
                                                                dataKey="sumLastOrderGrandTotal"
                                                                type="linear"
                                                                stroke="var(--chart-4)"
                                                                strokeWidth={2}
                                                                dot={false}
                                                            />
                                                        </LineChart>
                                                    </ChartContainer>
                                                </div>
                                            </div>
                                            <div className="w-full lg:w-1/3 flex flex-col gap-4">
                                                <DashboardCard 
                                                    className="w-full shrink-0  break-after-page"
                                                    title="# of items"
                                                    subtitle={count_items_percent.toFixed(2) + "% since last " + reportTypeMappings[type]}
                                                    stat={count_items}
                                                />
                                                <Card className="h-full overflow-x-scroll print:hidden print:mb-[100%]">
                                                    <CardHeader>
                                                        <CardTitle className="mt-2">Orders</CardTitle>
                                                        <CardAction className="flex items-center gap-2">
                                                                <Button disabled={orderPage == 0} onClick={() => setOrderPage(orderPage == 0 ?  (Math.ceil(orders.data.length/orderPerPage) - 1) : (orderPage - 1))} className="cursor-pointer" variant="ghost" size="icon"><ChevronLeft /></Button>
                                                                <span className="text-xs">{orderPage + 1}/{Math.ceil(orders.data.length/orderPerPage)}</span>
                                                                <Button disabled={orderPage == (Math.ceil(orders.data.length/orderPerPage) - 1)} onClick={() => setOrderPage((orderPage + 1) % Math.ceil(orders.data.length/orderPerPage))} className="cursor-pointer" variant="ghost" size="icon"><ChevronRight /></Button>
                                                        </CardAction>
                                                    </CardHeader>
                                                    <CardContent className="print:overflow-y-visible">
                                                        <Table className="print:overflow-y-visible">
                                                            <TableHeader>
                                                                <TableRow>
                                                                    <TableHead>Order #</TableHead>
                                                                    <TableHead className="text-center font-semibold">Customer ID</TableHead>
                                                                    <TableHead className="text-center font-semibold"># of items</TableHead>
                                                                    <TableHead className="text-right font-semibold">Grand Total</TableHead>
                                                                </TableRow>
                                                            </TableHeader>
                                                            <TableBody className="print:overflow-y-visible">
                                                            {
                                                                orders.data.slice(orderPage * orderPerPage, (orderPage+1) * orderPerPage).map((order) => (
                                                                    <TableRow>
                                                                        <TableCell onClick={() => router.visit(route('orders.show', { order: order.order_num }))} className="hover:underline cursor-pointer">{order.order_num}</TableCell>
                                                                        <TableCell className="text-center">{order.customer_id}</TableCell>
                                                                        <TableCell className="text-center">{order.count}</TableCell>
                                                                        <TableCell className="text-right">{ currencyFormat("BDT", order.grand_total) }</TableCell>
                                                                    </TableRow>
                                                                ))
                                                            }
                                                            </TableBody>
                                                        </Table>
                                                    </CardContent>
                                                </Card>
                                                <Card className="h-full overflow-x-scroll hidden print:flex print:mb-[100%]">
                                                    <CardHeader>
                                                        <CardTitle>Orders</CardTitle>
                                                    </CardHeader>
                                                    <CardContent className="print:overflow-y-visible">
                                                        <Table className="print:overflow-y-visible">
                                                            <TableHeader>
                                                                <TableRow>
                                                                    <TableHead>Order #</TableHead>
                                                                    <TableHead className="text-center">Customer ID</TableHead>
                                                                    <TableHead className="text-center"># of items</TableHead>
                                                                    <TableHead className="text-right">Grand Total</TableHead>
                                                                </TableRow>
                                                            </TableHeader>
                                                            <TableBody className="print:overflow-y-visible">
                                                            {
                                                                orders.data.map((order) => (
                                                                    <TableRow>
                                                                        <TableCell>{order.order_num}</TableCell>
                                                                        <TableCell className="text-center">{order.customer_id}</TableCell>
                                                                        <TableCell className="text-center">{order.count}</TableCell>
                                                                        <TableCell className="text-right">{ currencyFormat("BDT", order.grand_total) }</TableCell>
                                                                    </TableRow>
                                                                ))
                                                            }
                                                            </TableBody>
                                                        </Table>
                                                    </CardContent>
                                                </Card>
                                                
                                                
                                            </div>
                                            
                                        </div>
                                    </TabsContent>
                                } 
                                return <TabsContent value={reportType} className="w-full absolute bottom-[45vh]">                            
                                    <LoaderCircleIcon className="block mx-auto my-4 animate-spin" size={30} />
                                </TabsContent>

                                
                            })
                        }
                    </Tabs>
                </div>
                
            </div>
        </div>
    </AppLayout>
}