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
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerFooter,
  DrawerHeader,
  DrawerTitle,
  DrawerTrigger,
} from "@/components/ui/drawer"
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { 
    Table,
    TableHeader,
    TableHead,
    TableBody,
    TableRow,
    TableCell, 
} from "@/components/ui/table"

import { Label } from "@/components/ui/label"
import { Banknote, ReceiptText, Undo2, Minus, Plus } from "lucide-react"
import { Order, type BreadcrumbItem } from '@/types'
import { useEffect, useState } from "react";
import { toast } from 'sonner';

export default function Show({ order } : { 
    order: { 
        data: Order
} }) {

    const [newPaymentAmount, setNewPaymentAmount] = useState(350)

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

    const modifyPaymentAmount = (down: boolean, add: boolean = true) => {
        if (down && !window.timer) {
            window.timer = setInterval(() => {
                if(down && window.timer) {
                    setNewPaymentAmount((amt) => (add ? (amt + 10) : (amt - 10)))
                }
            },100)
        } else if (window.timer) {
            clearInterval(window.timer)
            delete window.timer
        }
    }

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
                                <DrawerTrigger>
                                <Button
                                    // onClick={() => window.print()} 
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
                        </Tooltip>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('orders.receipt', { order: order.data.order_num}))} 
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
                                    onClick={() => router.visit(route('orders.create'))}
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
                <div className="flex flex-wrap xl:flex-nowrap p-8 print:py-0 gap-6">
                    <Card className="w-full overflow-x-scroll xl:w-1/2 print:w-full print:border-0 print:shadow-none">
                        <CardHeader>
                            <CardTitle>Order # {data.order_num}</CardTitle>
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
                                            <TableBody className="">
                                            {
                                                data.items?.map(({tyre_id, qty, unit_price, item_total}, i) => {
                                                    
                                                    const tyre = data.contents?.find((item) => item.tyre_id == tyre_id)

                                                    return (
                                                        <TableRow key={i}>
                                                            <TableCell className="text-center">{ i+1 }</TableCell>
                                                            <TableCell>
                                                                ({tyre_id}) {tyre?.brand} {tyre?.size} {tyre?.pattern} {tyre?.lisi}
                                                            </TableCell>
                                                            <TableCell className="text-center">{qty}</TableCell>
                                                            <TableCell className="text-right">৳ {unit_price.toFixed(2)}</TableCell>
                                                            <TableCell className="text-right">৳ {(item_total).toFixed(2)}</TableCell>
                                                        </TableRow>
                                                    )
                                                })
                                            }
                                            <TableRow className='font-bold'>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Sub total</TableCell>
                                                <TableCell className="text-center">{data.count}</TableCell>
                                                <TableCell className="text-right"></TableCell>
                                                <TableCell className="text-right">৳ {data.sub_total.toFixed(2)}</TableCell>
                                            </TableRow>
                                            {
                                                data.discount_percent > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Discount</TableCell>
                                                    <TableCell className="text-center">-</TableCell>
                                                    <TableCell className="text-right">{data.discount_percent} %</TableCell>
                                                    <TableCell className="text-right">৳ {(data.sub_total * (data.discount_percent/100)).toFixed(2)}</TableCell>
                                                </TableRow>
                                            }
                                            {
                                                data.discount_amount > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Discount</TableCell>
                                                    <TableCell className="text-center">-</TableCell>
                                                    <TableCell className="text-right">৳ {data.discount_amount.toFixed(2)}</TableCell>
                                                    <TableCell className="text-right">৳ {data.discount_amount.toFixed(2)}</TableCell>
                                                </TableRow>
                                            }
                                            
                                            {
                                                data.tax_percentage > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Tax</TableCell>
                                                    <TableCell className="text-center">+</TableCell>
                                                    <TableCell className="text-right">{data.tax_percentage + " %"}</TableCell>
                                                    <TableCell className="text-right">৳ {(data.sub_total * (data.tax_percentage/100)).toFixed(2)}</TableCell>
                                                </TableRow>
                                            }
                                            {
                                                data.tax_amount > 0 &&
                                                <TableRow>
                                                    <TableCell className="text-center"></TableCell>
                                                    <TableCell>Tax</TableCell>
                                                    <TableCell className="text-center">+</TableCell>
                                                    <TableCell className="text-right">৳ {data.tax_amount.toFixed(2)}</TableCell>
                                                    <TableCell className="text-right">৳ {data.tax_amount.toFixed(2)}</TableCell>
                                                </TableRow>
                                            }
                                            <TableRow className="font-bold">
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Grand Total</TableCell>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell className="text-right"></TableCell>
                                                <TableCell className="text-right">৳ {data.grand_total?.toFixed(2)}</TableCell>
                                            </TableRow>
                                            </TableBody>
                                        </Table>
                                    </div>
                                </div>
                        </CardContent>
                    </Card>
                    <Card className="w-full overflow-x-scroll xl:w-1/2 print:w-full print:border-0 print:shadow-none">
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
                                            <TableBody className="">
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
                                                            <TableCell className="text-right">- ৳ {amount.toFixed(2)}</TableCell>
                                                            <TableCell className="text-right">{data.grand_total && data.payments && (
                                                                data.grand_total 
                                                                    - data.payments.slice(0, i).reduce((acc, cur) => acc + cur.amount, 0)
                                                                    - amount
                                                            ).toFixed(2)}
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
                
                <DrawerContent>
                    <div className="mx-auto w-full max-w-sm">
                        <DrawerHeader>
                            <DrawerTitle className="text-center">Order # {order.data.order_num} </DrawerTitle>
                            <DrawerDescription className="text-center">Make a payment</DrawerDescription>
                        </DrawerHeader>
                        <div className="px-4 pb-0 pt-8">
                            <div className="flex flex-col justify-center gap-2">
                                <div className="flex  space-x-2">
                                    <div className="w-2/3">
                                                Grand Total
                                    </div>
                                    <div className="w-1/3 text-right">
                                                {order.data.grand_total}
                                    </div>
                                </div>
                                <div className="flex  space-x-2 pb-3">
                                    <div className="w-2/3">
                                                Payments
                                    </div>
                                    <div className="w-1/3 text-right">
                                                {order.data.payments_total}
                                    </div>
                                </div>
                                <div className="flex  space-x-2 border-t-2 pt-3 mb-6">
                                    <div className="w-2/3">
                                                Balance
                                    </div>
                                    <div className="w-1/3 text-right">
                                                {order.data.balance}
                                    </div>
                                </div>
                                <div>Pay</div>
                                <div className="flex items-center justify-center">
                                <Button
                                    variant="outline"
                                    size="icon"
                                    className="h-8 w-8 shrink-0 rounded-full select-none"
                                    disabled={newPaymentAmount <= 200}
                                    onClick={() => setNewPaymentAmount((amt) => amt -10)}
                                    onTouchStart={() => modifyPaymentAmount(true, false)}
                                    onMouseDown={() => modifyPaymentAmount(true, false)}
                                    onMouseUp={() => modifyPaymentAmount(false)}
                                    onTouchEnd={() => modifyPaymentAmount(false)}
                                >
                                    <Minus />
                                    <span className="sr-only">Decrease</span>
                                </Button>
                                <div className="flex-1 text-center">
                                    <div className="text-7xl font-bold tracking-tighter">
                                        {newPaymentAmount}
                                    </div>
                                    <div className="text-muted-foreground text-[0.70rem] uppercase">
                                        Calories/day
                                    </div>
                                </div>
                                <Button
                                    variant="outline"
                                    size="icon"
                                    className="h-8 w-8 shrink-0 rounded-full select-none"
                                    onClick={() => setNewPaymentAmount((amt) => amt + 10)}
                                    onTouchStart={() => modifyPaymentAmount(true)}
                                    onMouseDown={() => modifyPaymentAmount(true)}
                                    onTouchEnd={() => modifyPaymentAmount(false)}
                                    onMouseUp={() => modifyPaymentAmount(false)}
                                >
                                    <Plus />
                                    <span className="sr-only">Increase</span>
                                </Button>
                                </div>
                                <div className="mt-4">
                                    <Select>
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select payment type" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                            <SelectLabel>Payment types</SelectLabel>
                                                <SelectItem value="unknown">Unknown</SelectItem>
                                                <SelectItem value="cash">Cash</SelectItem>
                                                <SelectItem value="deposit">Bank Deposit</SelectItem>
                                                <SelectItem value="check">Check</SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div className="flex  space-x-2 pt-4 mb-4">
                                    <Select>
                                        <SelectTrigger className="w-full">
                                            <SelectValue placeholder="Select bank account" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectGroup>
                                            <SelectLabel>Bank accounts</SelectLabel>
                                                <SelectItem value="unknown">Unknown</SelectItem>
                                                <SelectItem value="cash">Cash</SelectItem>
                                                <SelectItem value="deposit">Bank Deposit</SelectItem>
                                                <SelectItem value="check">Check</SelectItem>
                                            </SelectGroup>
                                        </SelectContent>
                                    </Select>
                                    
                                </div>
                                <div className="flex  space-x-2 border-t-2  pt-4 mb-6">
                                    <div className="w-2/3">
                                                New Balance
                                    </div>
                                    <div className="w-1/3 text-right">
                                                {order.data.balance}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <DrawerFooter>
                            <Button>Submit</Button>
                            <DrawerClose asChild>
                                <Button variant="outline">Cancel</Button>
                            </DrawerClose>
                        </DrawerFooter>
                    </div>
                </DrawerContent>
            </AppLayout>
        </Drawer>
    )
}