import AppLayout from "@/layouts/app-layout";
import { Button } from '@/components/ui/button'
import { Head, usePage, router } from "@inertiajs/react";
import { 
    Card,
    CardContent, 
    CardDescription, 
    CardHeader,
    CardTitle
} from "@/components/ui/card";
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"
import {
  Drawer,
  DrawerTrigger,
} from "@/components/ui/drawer"
import { 
    Table,
    TableHeader,
    TableHead,
    TableBody,
    TableRow,
    TableCell, 
} from "@/components/ui/table"

import { Label } from "@/components/ui/label"
import { ArrowLeft, Banknote, Plus, ReceiptText, Undo2 } from "lucide-react"
import { Order, type BreadcrumbItem } from '@/types'
import { useEffect } from "react";
import { toast } from 'sonner';
import CreateForm from "@/pages/payments/components/create-form";
import { currencyFormat } from "@/lib/utils";

export default function Show({ order } : { 
    order: { 
        data: Order
} }) {


    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Orders',
            href: route('orders.index'),
        },
        {
            title: '# ' + order.data.order_num,
            href: route('orders.show', { order: order.data.order_num }),
        },
    ];

    const data = order.data

    const { notification } = usePage<{ notification : {
        message: string
    }}>().props

    useEffect(() => {
        if (notification) {
            toast.success(notification.message, {
                action: {
                    label: "Print",
                    onClick: () => window.print()
                }
            })
        }
    }, [notification])

    useEffect(() => {
        window.oncontextmenu = () => false

        return () => {
            window.oncontextmenu = null
        }
    }, [])

    return (
        <Drawer>
            <AppLayout 
                breadcrumbs={breadcrumbs}
                controls={
                    <div className="flex items-end gap-2 justify-end">
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('orders.index'))} 
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <ArrowLeft />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Back to Orders</p>
                            </TooltipContent>
                        </Tooltip>
                        { order.data.balance > 0 && <Tooltip>
                            <TooltipTrigger asChild>
                                <DrawerTrigger>
                                    <Button
                                        className="hover:cursor-pointer text-xs" 
                                        size="icon" 
                                        variant="ghost"
                                    >
                                        <Banknote />
                                    </Button>
                                </DrawerTrigger>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Add Payment</p>
                            </TooltipContent>
                        </Tooltip>}
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('orders.receipt', { order: order.data.order_num }))} 
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <ReceiptText />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>View Receipt</p>
                            </TooltipContent>
                        </Tooltip>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('orders.returns.create', { order: order.data.order_num }))}
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <Undo2 />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Return Items</p>
                            </TooltipContent>
                        </Tooltip>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('orders.create'))}
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <Plus />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>New order</p>
                            </TooltipContent>
                        </Tooltip>
                    </div>
                }
            >
                <Head />
                <div className="w-full">
                    <h1 className="text-2xl md:text-4xl font-bold pl-4 mt-2">Order <span className="text-muted text-2xl">#{data.order_num}</span></h1>
                </div>
                <div className="flex flex-wrap xl:flex-nowrap pt-4 mb-6 px-4 print:py-0 gap-6">
                    <Card className="w-full overflow-x-scroll xl:w-1/2 print:w-full print:border-0 print:shadow-none dark:bg-neutral-900 dark:border-none">
                        <CardHeader>
                            <CardTitle>Details</CardTitle>
                        </CardHeader>
                        <CardContent className="flex flex-col justify-between h-full font-mono">
                            
                                <div className="flex flex-col gap-6">
                                    
                                    <div className="flex flex-col gap-4 lg:flex-row lg:justify-between">
                                        <div className="w-full lg:w-1/3 flex">
                                            <div className="flex flex-col gap-2">
                                                <Label className="font-semibold">Customer</Label>
                                                <div className="flex flex-col gap-0.5">
                                                    <span className=" text-xs">
                                                        Customer ID: { data.customer_id }
                                                    </span>
                                                    <span className=" text-sm">
                                                        { data.customer_name }
                                                    </span>
                                                    <span className=" text-xs">
                                                        { data.customer?.address }
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="w-full lg:w-1/3 flex ">
                                            <div className="flex flex-col gap-2">
                                                <Label className="font-semibold">Order date</Label>
                                                <span className=" text-sm">{ (new Date(data.order_on)).toDateString() }</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex flex-col mt-4">
                                        <Table>
                                            <TableHeader>
                                                <TableRow>
                                                    <TableHead className="text-center">#</TableHead>
                                                    <TableHead>Item</TableHead>
                                                    <TableHead className="text-center">Qty</TableHead>
                                                    <TableHead className="text-right">Price</TableHead>
                                                    <TableHead className="text-right">Total</TableHead>
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody>
                                            {
                                                data.items?.map(({tyre_id, qty, unit_price, item_total}, i) => {
                                                    
                                                    const tyre = data.contents?.find((item) => item.tyre_id == tyre_id)

                                                    return (
                                                        <TableRow key={i}>
                                                            <TableCell className="text-center font-bold">{ i+1 }</TableCell>
                                                            <TableCell>
                                                                ({tyre_id}) {tyre?.brand} {tyre?.size} {tyre?.pattern} {tyre?.lisi}
                                                            </TableCell>
                                                            <TableCell className="text-center">{qty}</TableCell>
                                                            <TableCell className="text-right">{currencyFormat('BDT', unit_price)}</TableCell>
                                                            <TableCell className="text-right">{currencyFormat('BDT', item_total)}</TableCell>
                                                        </TableRow>
                                                    )
                                                })
                                            }
                                            <TableRow className='font-bold'>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Sub total</TableCell>
                                                <TableCell className="text-center">{data.count}</TableCell>
                                                <TableCell className="text-right"></TableCell>
                                                <TableCell className="text-right">{currencyFormat('BDT', data.sub_total)}</TableCell>
                                            </TableRow>
                                            {
                                                data.discount_percent > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Discount</TableCell>
                                                    <TableCell className="text-center">-</TableCell>
                                                    <TableCell className="text-right">{data.discount_percent} %</TableCell>
                                                    <TableCell className="text-right">{currencyFormat('BDT',(data.sub_total * (data.discount_percent/100)))}</TableCell>
                                                </TableRow>
                                            }
                                            {
                                                data.discount_amount > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Discount</TableCell>
                                                    <TableCell className="text-center">-</TableCell>
                                                    <TableCell className="text-right">{currencyFormat('BDT', data.discount_amount)}</TableCell>
                                                    <TableCell className="text-right">{currencyFormat('BDT', data.discount_amount)}</TableCell>
                                                </TableRow>
                                            }
                                            
                                            {
                                                data.tax_percentage > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Tax</TableCell>
                                                    <TableCell className="text-center">+</TableCell>
                                                    <TableCell className="text-right">{data.tax_percentage + " %"}</TableCell>
                                                    <TableCell className="text-right">{currencyFormat('BDT', (data.sub_total * (data.tax_percentage/100)))}</TableCell>
                                                </TableRow>
                                            }
                                            {
                                                data.tax_amount > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Tax</TableCell>
                                                    <TableCell className="text-center">+</TableCell>
                                                    <TableCell className="text-right">{currencyFormat('BDT', data.tax_amount)}</TableCell>
                                                    <TableCell className="text-right">{currencyFormat('BDT', data.tax_amount)}</TableCell>
                                                </TableRow>
                                            }
                                            <TableRow className="font-bold">
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Grand Total</TableCell>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell className="text-right"></TableCell>
                                                <TableCell className="text-right">{currencyFormat('BDT', data.grand_total ?? 0)}</TableCell>
                                            </TableRow>
                                            </TableBody>
                                        </Table>
                                    </div>
                                </div>
                        </CardContent>
                    </Card>
                    <Card className="w-full overflow-x-scroll xl:w-1/2 print:w-full print:border-0 print:shadow-none bg-neutral-50 dark:bg-neutral-900 border-none dark:border-none">
                        <CardHeader>
                            <CardTitle>Payments</CardTitle>
                            <CardDescription>Payment made for order # {data.order_num}</CardDescription>
                        </CardHeader>
                        <CardContent className="flex flex-col justify-between h-full font-mono">
                            
                                    <div className="flex flex-col mt-4">
                                        <Table>
                                            <TableHeader>
                                                <TableRow>
                                                    <TableHead className="text-center">Trans ID</TableHead>
                                                    <TableHead>Date</TableHead>
                                                    <TableHead className="text-right">Owing</TableHead>
                                                    <TableHead className="text-right">Payment</TableHead>
                                                    <TableHead className="text-right">Balance</TableHead>
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody className="text-xs">
                                            {
                                                data.payments?.map(({transaction_id, created_at, amount}, i) => {
                                                    return (
                                                        <TableRow key={transaction_id}>
                                                            <TableCell className="text-center">{ transaction_id }</TableCell>
                                                            <TableCell>
                                                                {created_at &&(new Date(created_at)).toDateString()}
                                                            </TableCell>
                                                            <TableCell className="text-right">৳ {data.grand_total && data.payments && (
                                                                data.grand_total 
                                                                    - data.payments.slice(0, i).
                                                                        reduce((acc, cur) => acc + cur.amount, 0)
                                                            ).toFixed(2)}
                                                            </TableCell>
                                                            <TableCell className="text-right">- {currencyFormat('BDT', amount)}</TableCell>
                                                            <TableCell className="text-right">৳ { currencyFormat('BDT', (
                                                                data.grand_total ?? 0 
                                                                    - data.payments.slice(0, i).reduce((acc, cur) => acc + cur.amount, 0)
                                                                    - amount
                                                            ))}
                                                            </TableCell>
                                                        </TableRow>
                                                    )
                                                })
                                            }
                                            </TableBody>
                                        </Table>
                                    </div>
                        </CardContent>
                    </Card>
                </div>
                {   
                    data.returns && data.returns.length > 0 &&
                    (<div className="flex flex-wrap xl:flex-nowrap pb-8 px-4 print:py-0 gap-6">
                        <div className="hidden lg:flex lg:w-1/2 spacer" />
                        <Card className="w-full overflow-x-scroll xl:w-1/2 print:w-full print:border-0 print:shadow-none">
                            <CardHeader>
                                <CardTitle>Returns</CardTitle>
                                <CardDescription>Items previously from the order that have been returned</CardDescription>
                            </CardHeader>
                            <CardContent className="flex flex-col justify-between h-full font-mono">
                                
                                    <div className="flex flex-col gap-6">
                                        <div className="flex flex-col mt-4">
                                            <Table>
                                                <TableHeader>
                                                    <TableRow>
                                                        <TableHead className="text-center">#</TableHead>
                                                        <TableHead>Item</TableHead>
                                                        <TableHead className="text-center">Qty</TableHead>
                                                        <TableHead className="text-right">Price</TableHead>
                                                        <TableHead className="text-right">Total</TableHead>
                                                    </TableRow>
                                                </TableHeader>
                                                <TableBody className="text-xs">
                                                {
                                                    data.returns_consolidated?.map(({tyre_id, qty, unit_price, tyre}, i) => {
                                                        return (
                                                            <TableRow key={i}>
                                                                <TableCell className="text-center">{ i+1 }</TableCell>
                                                                <TableCell>
                                                                    ({tyre_id}) {tyre?.brand} {tyre?.size} {tyre?.pattern} {tyre?.lisi}
                                                                </TableCell>
                                                                <TableCell className="text-center">{qty}</TableCell>
                                                                <TableCell className="text-right">৳ {unit_price}</TableCell>
                                                                <TableCell className="text-right">৳ {(qty * unit_price).toFixed(2)}</TableCell>
                                                            </TableRow>
                                                        )
                                                    })
                                                }
                                                    <TableRow>
                                                        <TableCell className="text-center"></TableCell>
                                                        <TableCell>
                                                            Total
                                                        </TableCell>
                                                        <TableCell className="text-center">{data.returns_consolidated?.reduce((prev, { qty }) => prev + qty, 0)}</TableCell>
                                                        <TableCell className="text-right">৳ </TableCell>
                                                        <TableCell className="text-right">৳ {data.returns_consolidated?.reduce((prev, { qty, unit_price }) => prev + (qty * unit_price), 0).toFixed(2)}</TableCell>

                                                    </TableRow>
                                                
                                                </TableBody>
                                            </Table>
                                        </div>
                                    </div>
                            </CardContent>
                        </Card>
                        
                    </div>)
                }
                
                <CreateForm order={order} />
            </AppLayout>
        </Drawer>
    )
}