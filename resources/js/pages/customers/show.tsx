import AppLayout from "@/layouts/app-layout"
import { BreadcrumbItem, Customer } from "@/types"
import { Head, Link, router } from '@inertiajs/react';
import { Label } from "@/components/ui/label";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"
import { ArrowLeft } from "lucide-react";
import { currencyFormat } from "@/lib/utils";
import { Separator } from "@/components/ui/separator";

export default function Show({ customer } : { 
    customer: {data: Customer}
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Customers",
            href: route('customers.index'),
        },
        {
            title: "ID: " + customer.data.id,
            href: route('customers.show', { customer: customer.data.id }),
        },
    ];
    return (
        <AppLayout 
            breadcrumbs={breadcrumbs}
            controls={
                <div className="flex items-end gap-2 justify-end">
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('customers.index'))} 
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <ArrowLeft />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Back to customers</p>
                            </TooltipContent>
                        </Tooltip>
                </div>
            }
        >
            <Head title={breadcrumbs[0].title} />
            <div className="w-full">
                <h1 className="text-2xl md:text-4xl font-bold pl-4 mt-2">Customer <span className="text-neutral-300 dark:text-neutral-700 text-2xl">ID:{customer.data.id}</span></h1>
                <div className="w-full gap-5 flex flex-wrap lg:flex-nowrap items-start p-4">
                    <Card className="w-full lg:w-1/2 rounded-3xl bg-[#FCFCFC] dark:bg-[#121212]">
                        <CardHeader>
                            <CardTitle>Information</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="id">Customer ID</Label>
                                    <span className="font-semibold">{customer.data.id}</span>
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="name">Customer Name</Label>
                                    <span>{customer.data.name}</span>
                                </div>
                            </div>
                            
                            <div className="flex gap-6 mb-6">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Phone #</Label>
                                    <span>{customer.data.phone}</span>
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="address">Address</Label>
                                    <p>{customer.data.address}</p>
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full">
                                    <Label className="text-xs" htmlFor="lc_num">Notes</Label>
                                    {customer.data.notes}
                                </div>
                            </div>
                            <Separator />
                            <h2 className="mt-4 mb-6 font-semibold">Summary</h2>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="id">Customer Since</Label>
                                    <span className="font-semibold">
                                        {new Date(customer.data.created_at).toDateString()}
                                    </span>
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="name">Total Orders</Label>
                                    <span>{customer.data.orders?.length}</span>
                                </div>
                            </div>
                            
                            <div className="flex gap-6 mb-6">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Lifetime Value</Label>
                                    <span>{currencyFormat("BDT",(customer.data.orders?.reduce((prev, order) => prev + (order.grand_total ?? 0), 0) ?? 0))}</span>
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="address">Payments Total</Label>
                                    <p>{currencyFormat("BDT",(customer.data.orders?.reduce((prev, order) => prev + (order.payments_total ?? 0), 0) ?? 0))}</p>
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Commission Total</Label>
                                    <span>{currencyFormat("BDT",(customer.data.orders?.reduce((prev, order) => prev + (order.commission ?? 0), 0) ?? 0))}</span>
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="address">Current Amount Owed (Balance)</Label>
                                    <span>{
                                        currencyFormat("BDT", customer.data.orders?.reduce((prev, order) => prev + (order.balance ?? 0) , 0) ?? 0)    
                                    }</span>
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full">
                                    <Label className="text-xs" htmlFor="lc_num">Notes</Label>
                                    {customer.data.notes}
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <div className="flex flex-col gap-4 w-full lg:w-1/2">
                        <Card className="flex flex-col rounded-3xl">
                            <CardHeader>
                                <CardTitle>Orders</CardTitle>
                                <CardDescription>All orders placed by customer ID # {customer.data.id}</CardDescription>
                            </CardHeader>
                            <CardContent className="max-h-[70vh] overflow-scroll">
                            <Table>
                                <TableHeader className="sticky top-0">
                                    <TableRow className="hover:bg-transparent bg-card">
                                        <TableHead className="font-medium ">Order #</TableHead>
                                        <TableHead className="font-medium text-right">Grand Total</TableHead>
                                        <TableHead className="font-medium text-right">Amount Paid</TableHead>
                                        <TableHead className="font-medium text-right">Commission</TableHead>
                                        <TableHead className="font-medium text-right">Balance</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody className="text-xs">
                                { 
                                    customer.data.orders?.map(({order_num, grand_total, payments_total, commission, balance}, index) => (
                                        <TableRow className="hover:bg-transparent">
                                            <TableCell>
                                                <Link className="hover:underline font-medium" href={route('orders.show', {order: order_num})}>{order_num}</Link>    
                                            </TableCell>
                                            <TableCell className="text-right">{ currencyFormat("BDT", grand_total ?? 0)}</TableCell>
                                            <TableCell className="text-right">{ currencyFormat("BDT", payments_total ?? 0)}</TableCell>
                                            <TableCell className="text-right">{ currencyFormat("BDT", commission ?? 0)}</TableCell>
                                            <TableCell className="text-right">{ currencyFormat("BDT", balance ?? 0)}</TableCell>
                                        </TableRow>
                                    )) 
                                }
                                </TableBody>
                                {/* <TableFooter className="bg-transparent">
                                    <TableRow>
                                        <TableCell></TableCell>
                                        <TableCell>Total</TableCell>
                                        <TableCell className="text-center">
                                            {letterOfCredit.data?.items?.reduce((prev, { qty }) => prev + qty, 0)} 
                                        </TableCell>
                                        <TableCell className="text-right">
                                        </TableCell>
                                        <TableCell className="text-right">
                                            {currencyFormat(
                                                letterOfCredit.data.currency_code,
                                                letterOfCredit.data?.items?.reduce((prev, { qty, unit_price }) => prev + (qty * unit_price), 0) ?? 0
                                            )} 
                                        </TableCell>
                                    </TableRow>
                                </TableFooter> */}
                            </Table>
                            </CardContent>
                        </Card>
                        
                    </div>
                </div>
            </div>
            
        </AppLayout> 
    )
}