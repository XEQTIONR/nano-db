import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';

import { ArrowLeft, Minus, Plus, X } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"

import { Input } from '@/components/ui/input';
import { useState } from 'react';
import { InvoiceItem, Order } from '@/types'
import { Label } from '@/components/ui/label';

import { Select, SelectTrigger, SelectContent, SelectValue, SelectGroup, SelectItem, SelectLabel } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';

interface ValueAndPercentage {
    value: number,
    percentage: number
}
export default function Returns({order} : {order: { data: Order }}) {

    const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Orders',
        href: route('orders.index'),
    },
    {
        title: '# ' + order.data.order_num,
        href: route('orders.show', {order: order.data.order_num}),
    },
    {
        title: 'Returns',
        href: route('orders.returns.create', {order: order.data.order_num}),
    },
];
    const [items] = useState<InvoiceItem[]|undefined>(() => order.data.items?.map((itm) => {
            return {
                ...itm,
                id: itm.tyre_id,
            }
        })
    )

    const [returnItems, setReturnItems] = useState<InvoiceItem[]>(() => {
        return items?.map((item) => {
            return {
                ...item,
                qty: 0
            }
        }) ?? []
    })

    const [tax, setTax] = useState<ValueAndPercentage>(() => {
        return {
            value: order.data.tax_amount,
            percentage: order.data.tax_percentage
        }
    })

    const [discount, setDiscount] = useState<ValueAndPercentage>(() => {
        return {
            value: order.data.discount_amount,
            percentage: order.data.discount_percent
        }
    })


    const [currentTax, setCurrentTax] = useState<{
        type?: "percentage" | "amount",
        value?: string
    } | undefined>(undefined)

    const [currentDiscount, setCurrentDiscount] = useState<{
        type?: "percentage" | "amount",
        value?: string
    } | undefined>(undefined)


    const [editDiscocunt, setEditDiscount] = useState(false)
    const [editTax, setEditTax] = useState(false)

    const totalQty = () => 
        (items?.reduce((acc, current) => acc + current.qty, 0) ?? 0)
    - (returnItems.reduce((acc, current) => acc + current.qty, 0))

    const subTotal = () => 
        (items?.reduce((acc, current) => acc + (current.qty * current.unit_price), 0) ?? 0)
    - (returnItems.reduce((acc, current) => acc + (current.qty * current.unit_price), 0))
    
    const totalDiscount = () => {
        
        if (discount) {
            return (discount.value ?? 0) + (subTotal() ?? 0 * (discount.percentage/100))
        }

        return 0
    }

    const totalTax = () => {

        if (tax) {
            return (tax.value ?? 0) + (subTotal() ?? 0 * (tax.percentage/100))
        }

        return 0
    }

    const grandTotal = () => (subTotal() ?? 0) - totalDiscount() + totalTax()

    const paymentsTotal = () => order.data?.payments?.reduce((prev, payment) => prev + payment.amount, 0) ?? 0
    
    const balance = () => grandTotal() - paymentsTotal()

    return (
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
                </div>
            }    
        >
            <Head title="Return Items" />
            <div className="flex flex-col h-full items-start rounded-xl p-4">
                <div className="w-full pt-5 flex gap-4 items-start">
                    <div className={cn(
                        "w-full transition-all relative flex flex-wrap xl:flex-nowrap gap-4",
                    )}>
                        <Card className="w-full xl:w-1/2">
                            <CardHeader>
                                <CardTitle>Order #{order.data.order_num}</CardTitle>
                                <CardDescription>Select items from your order to return</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="flex flex-col gap-6">
                                    <div className="w-full flex">
                                        <div className="flex flex-col gap-4 w-full md:w-1/2 xl:w-1/3">
                                            <Label>Order Date</Label>
                                            { order.data.order_on.toString() }
                                        </div>
                                    </div>
                                    <div className="flex flex-col gap-4">
                                        <Label>
                                            Customer
                                        </Label>
                                        { order.data.customer_id }
                                    </div>
                                </div>
                                <Table className="mb-4">
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead className="">#</TableHead>
                                            <TableHead>Item</TableHead>
                                            <TableHead className="text-center max-w-40">Qty</TableHead>
                                            <TableHead className="text-center max-w-28">Price</TableHead>
                                            <TableHead className="text-right">Total</TableHead>
                                            
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody className="font-mono">
                                    { 
                                        items && items.map(({brand, size, pattern, lisi, qty, unit_price, id } , index) => (
                                            <TableRow className="hover:bg-transparent text-xs">
                                                <TableCell className="font-bold">{index + 1}</TableCell>
                                                <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                                <TableCell className="text-center max-w-40">
                                                    <div className="flex gap-1">
                                                    <Button onClick={() => {
                                                        const ret = [...returnItems]

                                                        if (ret[index].qty > 0) {
                                                            ret[index].qty--
                                                        }
                                                        return setReturnItems(ret)
                                                    }} variant="ghost" size="icon"><Minus /></Button>
                                                    <Input
                                                        onChange={({target}) => {
                                        

                                                            const ret = [...returnItems]
  
                                                            let quantity = parseInt(target.value)

                                                            if (Number.isNaN(quantity) || quantity < 0) {
                                                                quantity = 0
                                                            } else if (quantity > items[index].qty) {
                                                                quantity = items[index].qty
                                                            }
                                                            console.log(target.value)
                                                            ret[index].qty = items[index].qty - quantity
                                                            setReturnItems(ret)
                                                            
                                                        }}
                                                        className={cn("text-center")} 
                                                        value={qty - returnItems[index].qty} 
                                                    />
                                                    <Button onClick={() => {
                                                        const ret = [...returnItems]

                                                        if (items[index].qty > ret[index].qty) {
                                                            ret[index].qty++
                                                        }
                                                        return setReturnItems(ret)
                                                    }} variant="ghost" size="icon"><Plus /></Button>
                                                    </div> 
                                                </TableCell>
                                                <TableCell className="text-right max-w-28">
                                                    ৳ {unit_price}
                                                </TableCell>
                                                <TableCell className="text-right">
                                                ৳ {
                                                    (items[index].qty - returnItems[index].qty) * items[index].unit_price
                                                }
                                                </TableCell>
                                                
                                            </TableRow>
                                        )) 
                                    }
                                    <TableRow className="font-semibold">
                                        <TableCell></TableCell>
                                        <TableCell>Sub total</TableCell>
                                        <TableCell className="text-center max-w-24">{totalQty()}</TableCell>
                                        <TableCell className="max-w-28"></TableCell>
                                        <TableCell className="text-right">৳ {subTotal().toFixed(2)}</TableCell>

                                    </TableRow>
                                    {
                                        discount.percentage > 0 &&
                                        <TableRow className="font-semibold">
                                            <TableCell></TableCell>
                                            <TableCell>Discount</TableCell>
                                            <TableCell className="max-w-24"></TableCell>
                                            <TableCell className="text-right max-w-28">{discount.percentage} %</TableCell>
                                            <TableCell className="text-right">- ৳ {(subTotal() * (discount.percentage/100.0)).toFixed(2)}</TableCell>
    
                                        </TableRow>
                                    }
                                    {
                                        discount.value > 0 &&
                                        <TableRow className="font-semibold">
                                            <TableCell></TableCell>
                                            <TableCell>Discount</TableCell>
                                            <TableCell className="max-w-24"></TableCell>
                                            <TableCell className="text-right max-w-28">৳ {discount.value.toFixed(2)}</TableCell>
                                            <TableCell className="text-right">- ৳ {discount.value.toFixed(2)}</TableCell>
                                            
                                        </TableRow>
                                    }
                                    {
                                        tax.percentage > 0 &&
                                        <TableRow className="font-semibold">
                                            <TableCell></TableCell>
                                            <TableCell>Tax</TableCell>
                                            <TableCell className="max-w-24"></TableCell>
                                            <TableCell className="text-right max-w-28">{tax.percentage} %</TableCell>
                                            <TableCell className="text-right">+ ৳ {(subTotal() * (tax.percentage/100.0)).toFixed(2)}</TableCell>
                                            
                                        </TableRow>
                                    }
                                    {
                                        tax.value > 0 &&
                                        <TableRow className="font-semibold">
                                            <TableCell></TableCell>
                                            <TableCell>Tax</TableCell>
                                            <TableCell className="max-w-24"></TableCell>
                                            <TableCell className="text-right max-w-28">৳ {tax.value.toFixed(2)} %</TableCell>
                                            <TableCell className="text-right">+ ৳ {tax.value.toFixed(2)}</TableCell>
                                            
                                        </TableRow>
                                    }
                                    <TableRow className="font-extrabold">
                                        <TableCell></TableCell>
                                        <TableCell>Grand total</TableCell>
                                        <TableCell className="text-center max-w-24"></TableCell>
                                        <TableCell className="max-w-28"></TableCell>
                                        <TableCell className="text-right">৳ {grandTotal().toFixed(2)}</TableCell>
                                   
                                    </TableRow>
                                     <TableRow className="font-extrabold">
                                        <TableCell></TableCell>
                                        <TableCell>Payments total</TableCell>
                                        <TableCell className="text-center max-w-24">{order.data.payments?.length ?? ""}</TableCell>
                                        <TableCell className="max-w-28"></TableCell>
                                        <TableCell className="text-right">৳ {paymentsTotal().toFixed(2)}</TableCell>
                                   
                                    </TableRow>
                                    <TableRow className="font-extrabold">
                                        <TableCell></TableCell>
                                        <TableCell>Amount owed</TableCell>
                                        <TableCell className="text-center max-w-24"></TableCell>
                                        <TableCell className="max-w-28"></TableCell>
                                        <TableCell className="text-right">৳ {balance().toFixed(2)}</TableCell>
                                   
                                    </TableRow>
                                    </TableBody>
                                </Table>
                                { editDiscocunt && <div className="flex flex-col gap-2">
                                        <Label className="font-bold">Add Discount</Label>
                                        <form onSubmit={(e) => {
                                            e.preventDefault()
                                            
                                            switch(currentDiscount?.type) {
                                                case "amount":
                                                    setDiscount({
                                                        ...discount,
                                                        value: currentDiscount.value ? parseFloat(currentDiscount.value) : 0
                                                    })
                                                break
                                                case "percentage":
                                                    setDiscount({
                                                        ...discount,
                                                        percentage: currentDiscount.value ? parseFloat(currentDiscount.value) : 0
                                                    })

                                            }
                                            setEditDiscount(false)
                                        }} className="w-full flex py-5 gap-4 justify-between items-end">
                                            <div className="flex gap-4">
                                            <div className="flex flex-col gap-2">
                                                <Label>Type</Label>
                                                <Select onValueChange={(value:  "percentage" | "amount") => setCurrentDiscount({
                                                    ...currentDiscount, type: value
                                                })}>
                                                    <SelectTrigger className="w-44">
                                                        <SelectValue placeholder="Select discount type" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            <SelectItem value="percentage">Percentage</SelectItem>
                                                            <SelectItem value="amount">Amount</SelectItem>
                                                        </SelectGroup>
                                                    </SelectContent>
                                                </Select>
                                            </div>
                                            <div className="flex flex-col gap-2">
                                                <Label>Value</Label>
                                                <Input
                                                    onChange={({target}) => setCurrentDiscount({...currentDiscount, value: target.value})} 
                                                    value={currentDiscount?.value ? parseFloat(currentDiscount.value) : 0} className="max-w-44 text-right">
                                                </Input>
                                            </div>
                                            </div>
                                            <Button size="sm" type="submit">Submit</Button>
                                        </form>
                                    </div>
                                }


                                { editTax && <div className="flex flex-col gap-2">
                                        <Label className="font-bold">Add Tax</Label>
                                        <form onSubmit={(e) => {
                                            e.preventDefault()
                                            
                                            switch(currentTax?.type) {
                                                case "amount":
                                                    setTax({
                                                        ...tax,
                                                        value: currentTax.value ? parseFloat(currentTax.value) : 0
                                                    })
                                                break
                                                case "percentage":
                                                    setTax({
                                                        ...tax,
                                                        percentage: currentTax.value ? parseFloat(currentTax.value) : 0
                                                    })

                                            }
                                            setEditTax(false)
                                        }} className="w-full flex py-5 gap-4 justify-between items-end">
                                            <div className="flex gap-4">
                                            <div className="flex flex-col gap-2">
                                                <Label>Type</Label>
                                                <Select onValueChange={(value:  "percentage" | "amount") => setCurrentTax({
                                                    ...currentTax, type: value
                                                })}>
                                                    <SelectTrigger className="w-44">
                                                        <SelectValue placeholder="Select tax type" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            <SelectItem value="percentage">Percentage</SelectItem>
                                                            <SelectItem value="amount">Amount</SelectItem>
                                                        </SelectGroup>
                                                    </SelectContent>
                                                </Select>
                                            </div>
                                            <div className="flex flex-col gap-2">
                                                <Label>Value</Label>
                                                <Input
                                                    onChange={({target}) => setCurrentTax({...currentTax, value: target.value})} 
                                                    value={currentTax?.value ?? 0} className="max-w-44 text-right">
                                                </Input>
                                            </div>
                                            </div>
                                            <Button size="sm" type="submit">Submit</Button>
                                        </form>
                                    </div>
                                }
                                {
                                    items && items.length > 0 && (
                                        <div className="w-full flex justify-end gap-3">
                                            <Button onClick={() => {
                                                setEditDiscount(true)
                                            }} variant="outline" size="sm">Add Discount</Button>
                                            <Button onClick={() => {
                                                setEditTax(true)
                                            }} variant="outline" size="sm">Add Tax</Button>
                                            <Button 
                                                variant="secondary"
                                                disabled={grandTotal() < 0} 
                                                size="sm"
                                                onClick={() => {
                                                    router.post(route('orders.returns.store', {
                                                        order: order.data.order_num
                                                    }), {
                                                        returns: returnItems.filter(({qty}) => qty > 0)
                                                            .map(({id, qty, unit_price}) => {
                                                                return {
                                                                    id,
                                                                    qty,
                                                                    unit_price,
                                                                }
                                                            }),
                                                        discount_percent: discount.percentage,
                                                        discount_amount: discount.value,
                                                        tax_percentage: tax.percentage,
                                                        tax_amount: tax.value,
                                                        
                                                    })
                                                }}
                                            >
                                                Continue
                                            </Button>
                                        </div>
                                    )
                                }
                            </CardContent>
                        </Card>
                        <Card className="w-full xl:w-1/2">
                            <CardHeader>
                                <CardTitle>Returning</CardTitle>
                                <CardDescription>
                                    Items being returned
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead className="">#</TableHead>
                                            <TableHead>Item</TableHead>
                                            <TableHead className="text-center max-w-24">Qty</TableHead>
                                            <TableHead className="text-center max-w-28">Price</TableHead>
                                            <TableHead className="text-right">Total</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody className="font-mono">
                                    { 
                                        returnItems
                                            .filter(({qty}) => qty > 0)
                                            .map(({id, brand, size, pattern, lisi, qty, unit_price, } , index) => (
                                            <TableRow className="hover:bg-transparent text-xs">
                                                <TableCell className="font-bold">{index + 1}</TableCell>
                                                <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                                <TableCell className="text-center max-w-24">
                                                    {qty} 
                                                </TableCell>
                                                <TableCell className="text-right max-w-28">
                                                    ৳ {unit_price.toFixed(2)}
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    ৳ {
                                                        (returnItems[index].qty * returnItems[index].unit_price).toFixed(2)
                                                    }
                                                </TableCell>
                                                <TableCell>
                                                </TableCell>
                                            </TableRow>
                                        )) 
                                    }
                                        <TableRow className="font-semibold">
                                            <TableCell></TableCell>
                                            <TableCell>Sub total</TableCell>
                                            <TableCell className="text-center max-w-24">{
                                                returnItems.reduce((prev, curr) => curr.qty + prev, 0)    
                                            }</TableCell>
                                            <TableCell className="max-w-28"></TableCell>
                                            <TableCell className="text-right">৳ {
                                                returnItems.reduce((prev, curr) => (curr.qty * curr.unit_price) + prev, 0).toFixed(2)
                                            }</TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    )
}