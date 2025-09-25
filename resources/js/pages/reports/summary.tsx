import AppLayout from "@/layouts/app-layout";
import { Button } from "@/components/ui/button";
import DashboardCard from "@/components/ui/dashboard-card";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { CartesianGrid, Line, LineChart, Bar, BarChart, XAxis, YAxis } from "recharts"
import {
  ChartConfig,
  ChartContainer,
  ChartTooltip,
  ChartTooltipContent,
} from "@/components/ui/chart"
import { BanknoteArrowDown, BanknoteArrowUp, ChevronLeftIcon, ChevronRightIcon, LoaderCircleIcon, Tag } from "lucide-react";
import { currencyFormat } from "@/lib/utils";
import { Head, router } from "@inertiajs/react";
import { Expense, Order, Payment } from "@/types";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"

const chartConfig = {
  sumSales: {
    label: "Sales:",
    
    icon: Tag,
  },
  sumRevenue: {
    label: "Revenue:",
    
    icon: BanknoteArrowUp,
  },
  sumExpenses: {
    label: "Expenses:",
    
    icon: BanknoteArrowDown,
  },
  periodSales: {
    label: "Sales:",
    icon: Tag,
  },
  periodRevenue: {
    label: "Revenue:",
    icon: BanknoteArrowUp,
  },

  periodExpense: {
    label: "Expenses:",
    icon: BanknoteArrowDown,
  },
} satisfies ChartConfig

const reportTypes = [ 'daily', 'monthly', 'yearly' ]

type ReportType = 'daily' | 'monthly' | 'yearly'

const reportTypeMappings = {
    daily: 'day',
    monthly: 'month',
    yearly: 'year'
}


export default function SummaryReport({
    routeName, 
    type, 
    chart_data,
    date,
    
    orders,
    expenses,
    payments,

    ordersCount,
    expensesCount,
    paymentsCount,

    ordersCountPercent,
    expensesCountPercent,
    paymentsCountPercent,

    revenue,
    totalSales,
    totalExpenses,

    revenue_percent,
    sales_percent,
    expense_percent,  
} : {
    routeName: string, 
    type: ReportType, 
    chart_data: [], 
    date: string,
    
    orders: { data: Order[] },
    expenses: { data: Expense[] },
    payments: { data: Payment[] },

    ordersCount: number,
    expensesCount: number,
    paymentsCount: number,

    ordersCountPercent: number,
    expensesCountPercent: number,
    paymentsCountPercent: number,

    revenue: number,
    totalSales: number,
    totalExpenses: number,

    revenue_percent: number,
    sales_percent: number,
    expense_percent: number, 
})  {

    const breadcrumbs = [{
        title: "Reports", 
        href: route('reports.summary')
    },{
        title: "Summary Report", 
        href: route('reports.summary')
    },{
        title: type.charAt(0).toUpperCase() + type.slice(1) + " ", 
        href: route('reports.summary', { type })
    }, {
        title: type == "daily" 
            ? new Date(date).toDateString()  
            : type == "monthly"
                ? new Intl.DateTimeFormat('en-IN', { month: "short", year: "numeric" }).format(new Date(date))
                : new Date(date).getFullYear(),

        href: route('reports.summary', { date, type })
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

    return <AppLayout breadcrumbs={breadcrumbs}>

        <Head title="Summary Report"></Head>
        <div className="w-full h-[90vh]">
            <div className="p-4 flex flex-col gap-4">
                <h1 className="text-2xl md:text-4xl font-bold mt-2">Summary <span className="text-neutral-300 dark:text-neutral-700 text-2xl">
                        { type == "daily" && date }
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
                                            <div className="w-full flex flex-col gap-4">
                                                <div className="w-full flex flex-col lg:flex-row gap-4">
                                                    <DashboardCard 
                                                        className="w-full lg:w-1/3"
                                                        title="Sales"
                                                        subtitle={
                                                            (sales_percent > 0 ? "+" : "")
                                                            + sales_percent.toFixed(1) + "% since last " + reportTypeMappings[type]
                                                        }
                                                        stat={totalSales}
                                                        currencyCode="BDT"
                                                        decimalPlaces={2}
                                                    />
                                                    <DashboardCard 
                                                        className="w-full lg:w-1/3"
                                                        title="Revenue"
                                                        currencyCode="BDT"
                                                        subtitle={
                                                            (revenue_percent > 0 ? "+" : "")
                                                            + revenue_percent.toFixed(1) + "% since last " + reportTypeMappings[type]
                                                        }
                                                        stat={revenue}
                                                        decimalPlaces={2}
                                                    />
                                                    <DashboardCard
                                                        decimalPlaces={2} 
                                                        className="w-full lg:w-1/3"
                                                        title="Total expenses"
                                                        currencyCode="BDT"
                                                        currencySymbolColorClass="text-rose-500"
                                                        subtitle={
                                                            (expense_percent > 0 ? "+" : "")
                                                            + expense_percent.toFixed(1) + "% since last " + reportTypeMappings[type]
                                                        }
                                                        stat={totalExpenses}
                                                    />
                                                </div>
                                                <div className="w-full flex flex-col lg:flex-row gap-4">
                                                    <ChartContainer className='w-full lg:w-1/2 print:h-1/3 border rounded-xl' config={chartConfig}>    
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
                                                                    
                                                                // labelFormatter={(label: string) => <span className="">{new Date().toDateString() + " " +label.toUpperCase()}</span>} 
                                                                className='pb-2 min-w-3xs' />}
                                                            />
                                                            <Line
                                                                dataKey="sumSales"
                                                                type="linear"
                                                                stroke="var(--chart-3)"
                                                                strokeWidth={2}
                                                                dot={false}
                                                            />
                                                            <Line
                                                                dataKey="sumRevenue"
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
                                                    <ChartContainer className="w-full lg:w-1/2 print:h-1/3 border rounded-xl" config={chartConfig}>
                                                        <BarChart accessibilityLayer data={chart_data}>
                                                            <CartesianGrid vertical={false} />
                                                            <XAxis
                                                            dataKey="hours"
                                                            tickLine={false}
                                                            tickMargin={10}
                                                            axisLine={false}
                                                            // tickFormatter={(value) => value.slice(0, 3)}
                                                            />
                                                            <ChartTooltip
                                                            cursor={false}
                                                            content={<ChartTooltipContent
                                                                // formatter={(val,) => val} 
                                                                // labelFormatter={(label: string) => <span className="">{new Date().toDateString() + " " +label.toUpperCase()}</span>} 
                                                                className='pb-2 min-w-3xs' />}
                                                            />
                                                            <Bar dataKey="periodSales" fill="var(--color-chart-3)" radius={3} />
                                                            <Bar dataKey="periodRevenue" fill="var(--color-chart-2)" radius={3} />
                                                            <Bar dataKey="periodExpense" fill="var(--color-chart-5)" radius={3} />
                                                        </BarChart>
                                                    </ChartContainer>
                                                </div>
                                                <div className="w-full flex flex-col lg:flex-row gap-4 print:mt-16  break-after-page">
                                                
                                                    <DashboardCard 
                                                        className="w-full lg:w-1/3"
                                                        title="# of Orders"
                                                        subtitle={
                                                            (ordersCountPercent > 0 ? "+" : "")
                                                            + ordersCountPercent.toFixed(1) + "% since last " + reportTypeMappings[type]
                                                        }
                                                        stat={ordersCount}
                                                    />
                                                    <DashboardCard 
                                                        className="w-full lg:w-1/3"
                                                        title="# of payments"
                                                        subtitle={
                                                            (paymentsCountPercent > 0 ? "+" : "")
                                                            + paymentsCountPercent.toFixed(1) + "% since last " + reportTypeMappings[type]
                                                        }
                                                        stat={paymentsCount}
                                                    />
                                                    <DashboardCard 
                                                        className="w-full lg:w-1/3"
                                                        title="# of expenses"
                                                        subtitle={
                                                            (expensesCountPercent > 0 ? "+" : "")
                                                            + expensesCountPercent.toFixed(1) + "% since last " + reportTypeMappings[type]
                                                        }
                                                        stat={expensesCount}
                                                    />
                                                </div>
                                                <div className="w-full flex flex-col lg:flex-row gap-4">
                                                    <Card className="w-full lg:w-1/3">
                                                        <CardHeader>
                                                            <CardTitle>Orders</CardTitle>
                                                        </CardHeader>
                                                        <CardContent className="not-print:h-96 not-print:overflow-y-scroll">
                                                            <Table>
                                                                <TableHeader className="sticky top-0 bg-card">
                                                                    <TableHead className="font-semibold">Order #</TableHead>
                                                                    <TableHead className="font-semibold">Date</TableHead>
                                                                    <TableHead className="font-semibold text-right">Total</TableHead>
                                                                </TableHeader>
                                                                <TableBody className="">
                                                                {
                                                                    orders.data.map(({ order_num, order_on, grand_total }) => (
                                                                        <TableRow>
                                                                            <TableCell>{order_num}</TableCell>
                                                                            <TableCell>{order_on}</TableCell>
                                                                            <TableCell className="text-right">{ currencyFormat("BDT", grand_total)}</TableCell>
                                                                        </TableRow>
                                                                    ))
                                                                }
                                                                </TableBody>
                                                            </Table>
                                                        </CardContent>
                                                    </Card>
                                                    <Card className="w-full lg:w-1/3">
                                                        <CardHeader>
                                                            <CardTitle>Payments</CardTitle>
                                                        </CardHeader>
                                                        <CardContent className="not-print:h-96 not-print:overflow-y-scroll">
                                                            <Table>
                                                                <TableHeader className="sticky top-0 bg-card">
                                                                    <TableHead className="font-semibold">Trans ID</TableHead>
                                                                    <TableHead className="font-semibold">Order #</TableHead>
                                                                    <TableHead className="font-semibold">Type</TableHead>
                                                                    <TableHead className="font-semibold text-right">Amount</TableHead>
                                                                </TableHeader>
                                                                <TableBody>
                                                                {
                                                                    payments.data.map(({ transaction_id, order_num, type, amount  }) => (
                                                                        <TableRow>
                                                                            <TableCell>{transaction_id}</TableCell>
                                                                            <TableCell>{order_num}</TableCell>
                                                                            <TableCell>{type}</TableCell>
                                                                            <TableCell className="text-right">{ currencyFormat("BDT", amount)}</TableCell>
                                                                        </TableRow>
                                                                    ))
                                                                }
                                                                </TableBody>
                                                            </Table>
                                                        </CardContent>
                                                    </Card>
                                                    <Card className="w-full lg:w-1/3">
                                                        <CardHeader>
                                                            <CardTitle>Expenses</CardTitle>
                                                        </CardHeader>
                                                        <CardContent className="not-print:h-96 not-print:overflow-y-scroll">
                                                            <Table>
                                                                <TableHeader>
                                                                    <TableHead>ID</TableHead>
                                                                    <TableHead>Date</TableHead>
                                                                    <TableHead>Type</TableHead>
                                                                    <TableHead className="text-right">Amount</TableHead>
                                                                </TableHeader>
                                                                <TableBody>
                                                                {
                                                                    expenses.data.map(({ id, amount, expensable_type, date }) => (
                                                                        <TableRow>
                                                                            <TableCell>{id}</TableCell>
                                                                            <TableCell>{date}</TableCell>
                                                                            <TableCell>{expensable_type}</TableCell>
                                                                            <TableCell className="text-right">{ currencyFormat("BDT", amount)}</TableCell>
                                                                        </TableRow>
                                                                    ))
                                                                }
                                                                </TableBody>
                                                            </Table>
                                                        </CardContent>
                                                    </Card>
                                                </div>
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