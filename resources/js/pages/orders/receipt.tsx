import AppLayout from "@/layouts/app-layout";
import { Button } from '@/components/ui/button'
import { Head, usePage, router } from "@inertiajs/react";
import { 
    Card,
    CardContent 
} from "@/components/ui/card";
import {
  Drawer,
  DrawerTrigger,
} from "@/components/ui/drawer"
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"

import { 
    Table,
    TableHeader,
    TableHead,
    TableBody,
    TableRow,
    TableCell, 
} from "@/components/ui/table"

import { Label } from "@/components/ui/label"
import { ArrowLeft, Banknote, Plus, Printer, Undo2 } from "lucide-react"
import { Order, type BreadcrumbItem } from '@/types'
import { useEffect } from "react";
import { toast } from 'sonner';
import CreateForm from "../payments/components/create-form";

export default function OrderReceipt({ order } : { order: { data: Order } }) {

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Orders',
            href: route('orders.index'),
        },
        {
            title: '# ' + order.data.order_num,
            href: route('orders.show', { order: order.data.order_num }),
        },
        {
            title: 'Receipt',
            href: route('orders.receipt', { order: order.data.order_num }),
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

    return (
        <Drawer>
            <AppLayout 
                breadcrumbs={breadcrumbs}
                controls={
                    <div className="flex items-end gap-2 justify-end">
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('orders.show', {order: order.data.order_num}))}
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <ArrowLeft />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Back to <span className="font-semibold">#{order.data.order_num}</span></p>
                            </TooltipContent>
                        </Tooltip>
                        <Tooltip>
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
                        </Tooltip>
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => window.print()} 
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <Printer />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Print</p>
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
                <div className="flex h-full justify-center py-8 print:py-0">
                    <Card className="w-1/2 print:w-full print:border-0 print:shadow-none">
                        <CardContent className="flex flex-col justify-between h-full font-mono">
                            
                                <div className="flex flex-col gap-6">
                                    <div className="w-full flex items-center gap-2">
                                        <img src="/crosscountry.svg" className="w-20 h-20" />
                                        <div className="flex flex-col gap-0">
                                            <h2 className="text-xl font-bold ">Cross Country</h2>
                                            <span className="text-xs ">7/5 Ring Road, Shyamoli, Dhaka 1207</span>
                                        </div>
                                    </div>
                                    <div className="w-3/4 border-b-[1px]">
                
                                    </div>
                                    <h1 className="text-center  font-semibold text-xl">Invoice</h1>
                                    
                                    <div className="w-full flex justify-between">
                                        
                                        <div className="flex items-center gap-2 w-2/3 ml-2">
                                            
                                            <Label className="font-semibold">Order #</Label>
                                            <span className=" text-xl font-bold">{ data.order_num }</span>
                                        </div>
                                        <div className="flex flex-col gap-2 w-1/3">
                                            <span className=" text-sm">{ (new Date(data.order_on)).toDateString() }</span>
                                        </div>
                                    </div>
                                    <div className="w-full flex justify-between">
                                        
                                        <div className="flex flex-col gap-2 w-1/3 ml-2">
                                            
                                            <Label className="font-semibold">Bill to</Label>
                                            <div className="flex flex-col gap-0.5">
                                            
                                            <span className=" text-sm">
                                                { data.customer_name }
                                            </span>
                                            <span className=" text-xs">
                                                { data.customer?.address }
                                            </span>
                                            <span className=" text-xs mt-2">
                                                ID: { data.customer_id }
                                            </span>
                                            </div>
                                        </div>
                                        <div className="flex flex-col gap-2 w-1/3">
                                            <Label className="font-semibold">Beneficiary</Label>
                                            <div className="flex flex-col gap-0.5">
                                            <span className="text-sm">
                                                Cross Country
                                            </span>
                                            <span className="text-xs">
                                                7/5 Ring road, Shyamoli, <br />
                                                Dhaka-1207
                                            </span>
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
                                                        <TableRow>
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
                                <div className="w-1/2 flex flex-col items-center gap-4">
                                    <div className="w-full h-2 border-b-2"></div>
                                    Authrorized Signature
                                </div>
                        </CardContent>
                    </Card>
                </div>
                <CreateForm order={order} />
            </AppLayout>
        </Drawer>
    )
}