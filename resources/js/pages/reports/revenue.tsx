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
import { Payment } from "@/types";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { useState } from "react";

const chartConfig = {
  sumPayments: {
    label: "Total revenue (current)",
    
    icon: Tag,
  },
  sumLastPayments: {
    label: "Total revenue (last period)",
    
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


export default function RevenueReport({ 
    type, 
    chart_data, 
    date, 
    count, 
    count_percent, 
    payments,
    routeName, 
    revenue, 
    revenue_percent  
} : { 
    type: ReportType, 
    chart_data: [], 
    date: string, 
    count: number, 
    count_percent: number,
    payments: {data: Payment[]},
    routeName: string,
    revenue: number,
    revenue_percent: number
})  {

    const breadcrumbs = [{
        title: "Reports", 
        href: route('reports.revenue')
    },{
        title: "Revenue Report", 
        href: route('reports.revenue')
    }, {
        title: type.charAt(0).toUpperCase() + type.slice(1) + " ", 
        href: route('reports.revenue', { type })
    }, {
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

    const paymentsPerPage = 15
    const [paymentPage, setPaymentPage] = useState(0)

    return <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Revenue Report"></Head>
        <div className="w-full basis-1/10 ">
            <div className="p-4 flex flex-col gap-4 overflow-y-scroll">
                <h1 className="text-2xl md:text-4xl font-bold mt-2">Revenue Report <span className="text-muted text-2xl">
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
                                                        title="Revenue"
                                                        subtitle={revenue_percent.toFixed(2) + "% since last " + reportTypeMappings[type]}
                                                        stat={revenue}
                                                        currencyCode="BDT"
                                                        decimalPlaces={2}
                                                    />
                                                    <DashboardCard 
                                                        className="w-1/2"
                                                        title="# of payments"
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
                                                                dataKey="sumPayments"
                                                                type="linear"
                                                                stroke="var(--chart-4)"
                                                                strokeWidth={2}
                                                                dot={false}
                                                            />
                                                            <Line
                                                                dataKey="sumLastPayments"
                                                                type="linear"
                                                                stroke="var(--chart-2)"
                                                                strokeWidth={2}
                                                                dot={false}
                                                            />
                                                        </LineChart>
                                                    </ChartContainer>
                                                </div>
                                            </div>
                                            <div className="w-full lg:w-1/3 flex flex-col gap-4">
                                                {/* <DashboardCard 
                                                    className="w-full shrink-0  break-after-page"
                                                    title="# of items"
                                                    subtitle={count_items_percent.toFixed(2) + "% since last " + reportTypeMappings[type]}
                                                    stat={count_items}
                                                /> */}
                                                <Card className="overflow-x-scroll print:hidden max-h-[75vh]">
                                                    <CardHeader>
                                                        <CardTitle className="mt-2">Payments</CardTitle>
                                                        {/* <CardAction className="flex items-center gap-2">
                                                                <Button onClick={() => setPaymentPage(paymentPage == 0 ?  (Math.ceil(payments.data.length/paymentsPerPage) - 1) : (paymentPage - 1))} className="cursor-pointer" variant="ghost" size="icon"><ChevronLeft /></Button>
                                                                <span className="text-xs">{paymentPage + 1}/{Math.ceil(payments.data.length/paymentsPerPage)}</span>
                                                                <Button disabled={paymentPage == (Math.ceil(payments.data.length/paymentsPerPage) - 1)} onClick={() => setPaymentPage((paymentPage + 1) % Math.ceil(payments.data.length/paymentsPerPage))} className="cursor-pointer" variant="ghost" size="icon"><ChevronRight /></Button>
                                                        </CardAction> */}
                                                    </CardHeader>
                                                    <CardContent className="h-full grow-0 overflow-y-scroll">
                                                        <Table className="print:overflow-y-visible">
                                                            <TableHeader>
                                                                <TableRow>
                                                                    <TableHead className="font-semibold">Trans ID</TableHead>
                                                                    <TableHead className="font-semibold text-center">Order #</TableHead>
                                                                    <TableHead className="font-semibold text-right">Amount Paid</TableHead>
                                                                </TableRow>
                                                            </TableHeader>
                                                            <TableBody className="text-xs overflow-y-scroll">
                                                            {
                                                                payments.data.map((payment) => (
                                                                    <TableRow>
                                                                        <TableCell>{payment.transaction_id}</TableCell>
                                                                        <TableCell className="text-center hover:underline cursor-pointer" onClick={() => router.visit(route('orders.show', { order: payment.order_num }))}>{payment.order_num}</TableCell>
                                                                        <TableCell className="text-right">{currencyFormat("BDT", payment.amount)}</TableCell>
                                                                    </TableRow>
                                                                ))
                                                            }
                                                            </TableBody>
                                                        </Table>
                                                    </CardContent>
                                                </Card>
                                                <Card className="h-full overflow-x-scroll hidden print:flex print:mb-[300%]">
                                                    <CardHeader>
                                                        <CardTitle>Payments</CardTitle>
                                                    </CardHeader>
                                                    <CardContent className="print:overflow-y-visible">
                                                        <Table className="print:overflow-y-visible">
                                                            <TableHeader>
                                                                <TableRow>
                                                                    <TableHead>Trans ID</TableHead>
                                                                    <TableHead className="text-center">Order #</TableHead>
                                                                    <TableHead className="text-right">Amount Paid</TableHead>
                                                                </TableRow>
                                                            </TableHeader>
                                                            <TableBody className="print:overflow-y-visible">
                                                            {
                                                                payments.data.map((payment) => (
                                                                    <TableRow>
                                                                        <TableCell>{payment.transaction_id}</TableCell>
                                                                        <TableCell className="text-center hover:underline cursor-pointer" onClick={() => router.visit(route('orders.show', { order: payment.order_num }))}>{payment.order_num}</TableCell>
                                                                        <TableCell className="text-right">{currencyFormat("BDT", payment.amount)}</TableCell>
                                                                    </TableRow>
                                                                ))
                                                            }
                                                            </TableBody>
                                                        </Table>
                                                    </CardContent>
                                                </Card>
                                                <h1 className="hidden print:block">end</h1>
                                                
                                                
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