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
import { Banknote, ReceiptText, Undo2, Minus, Plus, Check, X } from "lucide-react"
import { Order, type BreadcrumbItem } from '@/types'
import { useEffect, useState } from "react";
import { toast } from 'sonner';
import { Input } from "@/components/ui/input";
import { cn } from "@/lib/utils";

export default function Show({ order } : { 
    order: { 
        data: Order
} }) {

    const [prevValue, setPreviousValue] = useState<number>(0)
    const [newPaymentAmount, setNewPaymentAmount] = useState <string|number>(0)
    const [editPaymentAmount, setEditPaymentAmount] = useState(false)
    const [paymentType, setPaymentType] = useState<string|undefined>('cash')

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
            }, 100)
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
                    <div className="mx-auto w-full max-w-md">
                        <DrawerHeader>
                            <DrawerTitle className="text-center">Make a payment</DrawerTitle>
                            <DrawerDescription className="text-center">Order # {order.data.order_num} </DrawerDescription>
                        </DrawerHeader>
                        <div className="px-4 pb-0 pt-8">
                            <div className="flex flex-col justify-center gap-2">
                                <div className="flex justify-between space-x-2 pb-2">
                                    <div>Grand Total</div>
                                    <div className="text-right">
                                        ৳ {order.data.grand_total?.toFixed(2)}
                                    </div>
                                </div>
                                <div className="flex justify-between space-x-2 pb-2">
                                    <div>Payments</div>
                                    <div className="text-right">
                                        ৳ {order.data.payments_total?.toFixed(2)}
                                    </div>
                                </div>
                                { typeof newPaymentAmount == "number" && newPaymentAmount > 0 && (<div className="flex justify-between space-x-2 pb-2">
                                    <div>New Payment</div>
                                    <div className="text-right">
                                        ৳ {newPaymentAmount.toFixed(2)}
                                    </div>
                                </div>)}
                                <div className="flex justify-between space-x-2 border-t-2 pt-3 mb-6">
                                    <div>Balance</div>
                                    <div className="text-right">৳ {
                                        typeof newPaymentAmount == "number" && typeof order.data.balance == "number"
                                        ? (order.data.balance - newPaymentAmount).toFixed(2)
                                        : order.data.balance
                                    }
                                    </div>
                                </div>
                                <div>Pay</div>
                                <div className="flex items-center justify-between h-20">
                                    <Button
                                        variant="outline"
                                        size="icon"
                                        className={cn("h-8 w-8 shrink-0 rounded-full select-none mb-6")}
                                        disabled={(typeof newPaymentAmount == "string")}
                                        onClick={() =>{ 
                                            if (editPaymentAmount) {
                                                setNewPaymentAmount(prevValue)
                                                setEditPaymentAmount(false)
                                            } else {
                                                if (typeof newPaymentAmount == "number") {
                                                    setNewPaymentAmount((amt) => {
                                                        if (typeof amt == "number") {
                                                            if (typeof data.balance == "number" && (amt - 10) < 0) {
                                                                return 0
                                                            }
                                                            return amt - 10
                                                        }
                                                        return newPaymentAmount
                                                    })
                                                }
                                            }
                                        }}
                                        onTouchStart={() => !editPaymentAmount && modifyPaymentAmount(true, false)}
                                        onMouseDown={() => !editPaymentAmount && modifyPaymentAmount(true, false)}
                                        onMouseUp={() => !editPaymentAmount && modifyPaymentAmount(false)}
                                        onTouchEnd={() => !editPaymentAmount && modifyPaymentAmount(false)}
                                    >
                                        {editPaymentAmount ? <X /> : <Minus />}
                                        <span className="sr-only">Decrease</span>
                                    </Button>
                                    <div className="flex flex-col gap-2 items-center">
                                        {
                                            editPaymentAmount ? (
                                                
                                                <div className="w-full flex justify-center">
                                                    <Input
                                                        min={0}
                                                        max={data.balance}
                                                        type="number"
                                                        onChange={({target}) => {

                                                            if (isNaN(parseFloat(target.value))) {
                                                                setNewPaymentAmount(target.value)
                                                            }
                                                            setNewPaymentAmount(
                                                                data.balance 
                                                                ? Math.min(parseFloat(target.value), data.balance)
                                                                : parseFloat(target.value)
                                                            )
                                                        }} 
                                                        value={newPaymentAmount} 
                                                        className="md:text-4xl w-full h-16 text-center font-bold tracking-tighter" 
                                                    />
                                                </div>
                                            ) : (
                                                <div onClick={() => {
                                                    setPreviousValue(newPaymentAmount)
                                                    setEditPaymentAmount(true)
                                                }} className="text-4xl font-bold tracking-tighter">
                                                    {
                                                        typeof newPaymentAmount == "number"
                                                        ? newPaymentAmount.toFixed(2)
                                                        : newPaymentAmount
                                                    }
                                                </div>
                                            )
                                        }
                                        
                                        <div className="text-muted-foreground text-[0.70rem] uppercase">
                                            Taka
                                        </div>
                                    </div>
                                    <Button
                                        variant="outline"
                                        size="icon"
                                        className={cn("h-8 w-8 shrink-0 rounded-full select-none mb-6")}
                                        disabled={(typeof newPaymentAmount == "string")}
                                        onClick={() =>{
                                            if (editPaymentAmount) {
                                                setEditPaymentAmount(false)
                                            } else {
                                                if (typeof newPaymentAmount == "number") {
                                                    setNewPaymentAmount((amt) => {
                                                        if (typeof amt == "number") {
                                                            if (typeof data.balance == "number" && (amt + 10) > data.balance) {
                                                                return data.balance
                                                            }
                                                            return amt + 10
                                                        }
                                                        return newPaymentAmount
                                                    })
                                                }
                                            }
                                            
                                        }}
                                        onTouchStart={() => !editPaymentAmount && modifyPaymentAmount(true)}
                                        onMouseDown={() => !editPaymentAmount && modifyPaymentAmount(true)}
                                        onTouchEnd={() => !editPaymentAmount && modifyPaymentAmount(false)}
                                        onMouseUp={() => !editPaymentAmount && modifyPaymentAmount(false)}
                                    >
                                        {
                                            editPaymentAmount ? <Check /> : <Plus />
                                        }
                                        
                                        <span className="sr-only">Increase</span>
                                    </Button>
                                </div>
                                <div className="my-4">
                                    <Select value={paymentType} onValueChange={(value) => {
                                        setPaymentType(value)
                                    }}>
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
                            </div>
                        </div>
                        <DrawerFooter>
                            <Button
                                disabled={
                                    editPaymentAmount 
                                    || typeof newPaymentAmount == 'number'
                                        && (newPaymentAmount <= 0 
                                            ||  ( !!order.data.balance && newPaymentAmount > order.data.balance)
                                        )
                                } 
                                onClick={() => {
                                    router.post(route('payments.store'), {
                                        order_num: order.data.order_num,
                                        payment_amount: newPaymentAmount,
                                        payment_type: paymentType
                                    })
                                }}
                            >Make Payment</Button>
                            <DrawerClose asChild>
                                <Button onClick={() => setNewPaymentAmount(0)} variant="outline">Cancel</Button>
                            </DrawerClose>
                        </DrawerFooter>
                    </div>
                </DrawerContent>
            </AppLayout>
        </Drawer>
    )
}