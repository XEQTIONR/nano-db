import DashboardCard from "@/components/ui/dashboard-card";
// import { Select, SelectItem, SelectContent, SelectTrigger, SelectValue } from "@/components/ui/select";
import AppLayout from "@/layouts/app-layout";
import { Head } from "@inertiajs/react";
//import { SelectItem } from "@radix-ui/react-select";
import { useState } from "react";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { LoaderCircleIcon, Tag } from "lucide-react";
import {
  ChartConfig,
  ChartContainer,
  ChartTooltip,
  ChartTooltipContent,
} from "@/components/ui/chart"
import { CartesianGrid, Line, LineChart, XAxis, YAxis } from "recharts"
import { currencyFormat } from "@/lib/utils";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Order } from "@/types";

const chartConfig = {
  sumOrderGrandTotal: {
    label: "Total sold today",
    
    icon: Tag,
  },
  sumLastOrderGrandTotal: {
    label: "Total sold",
    
    icon: Tag,
  },
} satisfies ChartConfig

export default function SalesReport({ type, chart_data, date, count, count_percent, count_items, count_items_percent, orders, sales, sales_percent  } 
    : { type: string, date: string, orders: {data: Order[]}}) {

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
            <div className="p-4 flex flex-col gap-4 max-h-[92vh] overflow-y-scroll">
                <h1 className="text-2xl md:text-4xl font-bold mt-2">Sales Report</h1>
                <div className="w-full">
                    {/* <Select value={typ}
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
                    </Select> */}
                    <Tabs defaultValue="day" className="w-full">
                        <TabsList className="mb-1">
                            <TabsTrigger className="cursor-pointer" onClick={() => console.log('account tigger')} value="day">Day</TabsTrigger>
                            <TabsTrigger className="cursor-pointer" value="spin">Password</TabsTrigger>
                            <TabsTrigger className="cursor-pointer" value="month">Month</TabsTrigger>
                            <TabsTrigger className="cursor-pointer" value="year">Year</TabsTrigger>
                        </TabsList>
                        <TabsContent value="day">
                            <div className="w-full flex flex-col lg:flex-row gap-4">
                                {/* <div className="w-full flex gap-4">
                                    <DashboardCard 
                                        className="w-1/3"
                                        title="Sales"
                                        subtitle={sales_percent + "% since yesterday"}
                                        stat={sales}
                                        currencyCode="BDT"
                                        decimalPlaces={2}
                                    />
                                    <DashboardCard 
                                        className="w-1/3"
                                        title="# of orders"
                                        subtitle={count_percent + "% since yesterday"}
                                        stat={count}
                                    />
                                    
                                </div>
                                <div className="w-full flex gap-4">
                                    <div className="w-2/3">
                                        
                                    </div>
                                    <div className="w-1/3 bg-red-300">x</div>
                                </div> */}
                                    {/* <DashboardCard 
                                        className="w-1/3"
                                        title="Sales"
                                        subtitle={sales_percent + "% since yesterday"}
                                        stat={sales}
                                        currencyCode="BDT"
                                        decimalPlaces={2}
                                    />
                                    <DashboardCard 
                                        className="w-1/3"
                                        title="# of orders"
                                        subtitle={count_percent + "% since yesterday"}
                                        stat={count}
                                    />  */}
                                <div className="w-full lg:w-2/3 flex flex-col gap-4">
                                    <div className="w-full flex gap-4">
                                        <DashboardCard 
                                            className="w-1/2"
                                            title="Sales"
                                            subtitle={sales_percent + "% since yesterday"}
                                            stat={sales}
                                            currencyCode="BDT"
                                            decimalPlaces={2}
                                        />
                                        <DashboardCard 
                                            className="w-1/2"
                                            title="# of orders"
                                            subtitle={count_percent + "% since yesterday"}
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
                                                    stroke="var(--chart-2)"
                                                    strokeWidth={2}
                                                    dot={false}
                                                />
                                                <Line
                                                    dataKey="sumLastOrderGrandTotal"
                                                    type="linear"
                                                    stroke="var(--chart-3)"
                                                    strokeWidth={2}
                                                    dot={false}
                                                />
                                            </LineChart>
                                        </ChartContainer>
                                    </div>
                                </div>
                                <div className="w-full lg:w-1/3 flex flex-col gap-4">
                                    <DashboardCard 
                                        className="w-full shrink-0"
                                        title="# of items"
                                        subtitle={count_items_percent + "% since yesterday"}
                                        stat={count_items}
                                    />
                                    <Card className="h-full">
                                        <CardHeader>
                                            <CardTitle>Orders</CardTitle>
                                        </CardHeader>
                                        <CardContent>
                                            <Table>
                                                <TableHeader>
                                                    <TableRow>
                                                        <TableHead>Order #</TableHead>
                                                        <TableHead className="text-center">Customer ID</TableHead>
                                                        <TableHead className="text-center"># of items</TableHead>
                                                        <TableHead className="text-right">Grand Total</TableHead>
                                                    </TableRow>
                                                </TableHeader>
                                                <TableBody>
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
                        <TabsContent className="w-full absolute bottom-[45vh]" value="spin">                            
                            <LoaderCircleIcon className="block mx-auto my-4 animate-spin" size={30} />
                        </TabsContent>
                    </Tabs>
                </div>
                
            </div>
        </div>
    </AppLayout>
}