import AppLayout from '@/layouts/app-layout';
import { Head, router } from '@inertiajs/react';


import { Badge } from '@/components/ui/badge';
import { ChevronLeft, ChevronRight, X } from 'lucide-react';
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
  TableCaption
} from "@/components/ui/table"

import { Input } from '@/components/ui/input';
import { InputCalendar } from '@/components/ui/input-calendar';
import { useEffect, useState } from 'react';
import { Customer, InvoiceItem } from '@/types'
import { Label } from '@/components/ui/label';
import { Combobox } from '@/components/ui/combobox';
import ProductsTable from '@/components/product-table';

import axios from 'axios'
import { Select, SelectTrigger, SelectContent, SelectValue, SelectGroup, SelectItem, SelectLabel } from '@/components/ui/select';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Orders',
        href: route('orders.index'),
    },
    {
        title: 'Create New',
        href: route('orders.create'),
    },
];

interface ValueAndPercentage {
    value: number,
    percentage: number
}
export default function Create({apiToken} : {apiToken: string}) {

    const [items, setItems] = useState<InvoiceItem[]>([])
    const [displayItems, setDisplayItems] = useState<{
        qty: string;
        unit_price: string;
        id: number;
        brand: string;
        size: string;
        pattern: string;
        lisi: string;
    }[]>([])

    const [show, setShow] = useState(true)
    const [dir, setDir] = useState(true)
    const [current, setCurrent] = useState(0)
    const steps = [
        'Add Details',
        'Confirm Order'
    ]

    const [customerId, setCustomerId] = useState<number | undefined>(undefined)
    const [customer, setCustomer] = useState<Customer | undefined>(undefined)

    const [ orderDate, setOrderDate ] = useState<Date | undefined>(undefined)

    const [tax, setTax] = useState<ValueAndPercentage>({
        value: 0,
        percentage: 0
    })

    const [discount, setDiscount] = useState<ValueAndPercentage>({
        value: 0,
        percentage: 0
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
    const [someCustomers, setSomeCustomers] = useState<Customer[]>([])

    useEffect(() => {
        setDisplayItems(() => items.map((item) => {
            return {
                ...item,
                qty: item.qty.toString(),
                unit_price: item.unit_price.toString()
            }
        }))
    }, [items])

    const deleteItemAt = ((idx: number) => {
        setItems(items.filter((_, i) => i !== idx))
    })

    const updateQty = ((idx: number, val: string) => {
        setDisplayItems(displayItems.map((item, i) => {
            if (i == idx) {
                item.qty = isNaN(parseInt(val)) ? "0" : val
            }
            return item 
        }))
    })

    const updatePrice = ((idx: number, val: string) => {
        setDisplayItems(displayItems.map((item, i) => {
            if (i == idx) {
                item.unit_price = isNaN(parseFloat(val)) ? "0" : val
            }
            return item
        }))
    })

    const totalQty = () => displayItems.reduce((acc, current) => acc + parseInt(current.qty), 0)

    const subTotal = () => displayItems.reduce((acc, current) => acc + (parseInt(current.qty) * parseFloat(current.unit_price)), 0)
    
    const totalDiscount = () => {
        
        if (discount) {
            return (discount.value ?? 0) + (subTotal() * (discount.percentage/100))
        }

        return 0
    }

    const totalTax = () => {

        if (tax) {
            return (tax.value ?? 0) + (subTotal() * (tax.percentage/100))
        }

        return 0
    }

    const grandTotal = () => subTotal() - totalDiscount() + totalTax()

    const showErrors = false

    const fn = (prev = false) => {
        setDir(prev)
        setShow(false)
        
        if (prev) {
            if ((current - 1) < 0)
                setCurrent(steps.length - 1)
            else
                setCurrent((current - 1))
        } else {
            if (current == 0) {
                if (customerId && customerId > 0 
                    && (items.length > 0) 
                    && orderDate
                    && items.every((item) => item.qty > 0 && item.unit_price > 0)
                ) {
                    setCurrent(1)
                } 
            }
        }
        
        setTimeout(() => setShow(true), 1)
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="New Order" />
            <div className="flex flex-col h-full items-start rounded-xl p-4">
                <div className="w-full flex print:hidden gap-10 items-center">
                    {
                        steps.map( (step, index) => (
                            <div className="flex items-center gap-4">
                                <Badge className={cn(
                                    "h-7 min-w-7 px-1.5 py-1 rounded-full text-xs font-bold font-mono ",
                                    index == current ? "bg-indigo-500 text-white" : "bg-neutral-400 dark:bg-neutral-800 dark:text-neutral-400"
                                )}>
                                    {index + 1}
                                </Badge>
                                <span className={cn(
                                    "text-base font-bold",
                                    index == current ? "dark:text-white text-neutral-950" : "text-neutral-400 dark:text-neutral-700"
                                )}>
                                    {step}
                                </span>
                            </div> 
                        ))
                    }
                    <div className="flex gap-3">
                        <Button disabled={current == 0} className="cursor-pointer" variant="secondary"
                            onClick={() => fn(true)}
                        >
                        <ChevronLeft />
                        </Button>
                        <Button disabled={current == 1} className="cursor-pointer" variant="secondary"
                            onClick={() => fn()}
                        >
                            <ChevronRight />
                        </Button>
                    </div>
                </div>
                { current == 0 && <div className="w-full pt-5 flex gap-4 items-start">
                    <div className={cn(
                        "w-full transition-all relative flex flex-col gap-4 md:w-1/2",
                        show ? "opacity-100" : "opacity-0",
                        !dir && (show ? "-right-0" : "-right-16"), 
                        dir && (show ? "-left-0" : "-left-16")
                    )}>
                        <Card className="w-full">
                            <CardHeader>
                                <CardTitle>Add order details</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="flex flex-col gap-6">
                                    <div className="w-full flex">
                                        <div className="flex flex-col gap-4 w-full md:w-1/2 xl:w-1/3">
                                            <Label>Order Date</Label>
                                            <InputCalendar date={orderDate} onChange={(date) => setOrderDate(date)} id="ordered_on" />
                                        </div>
                                    </div>
                                    <div className="flex flex-col gap-4">
                                        <Label>
                                        {
                                            customerId
                                                ? <> Customer ID: <span className="font-mono font-light">{customerId}</span></>
                                                : "Customer"
                                            
                                        }
                                        </Label>
                                        <Combobox
                                            dataType="int"
                                            onSelect={(customerId) => {
                                                if (customerId === 0) {
                                                    setCustomerId(undefined)
                                                } else if ( typeof customerId == "number") {
                                                    setCustomerId(customerId)
                                                    setCustomer(someCustomers.find(({id}) => id == customerId ))
                                                }
                                            }}
                                            value={customerId}
                                            options={someCustomers.map(({id, name}) => {
                                                return {
                                                    value: id,
                                                    label: name
                                                }
                                            })}
                                            getOptions={async(search: string) => {
                                                const endpoint = route('api.customers.index', {
                                                    filters: "name.like." + search
                                                })
                                                const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })
                                                console.log(response)
                                                setSomeCustomers(response.data.items)
                                                return response.data.items.map(({id, name} : {id: number, name: string}) => {
                                                    return {value: id, label: `${name}`}
                                                })
                                            }}
                                        />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                        <Card className="w-full">
                            <CardHeader>
                                <CardTitle>Add order items</CardTitle>
                            </CardHeader>
                            <CardContent className="flex flex-col gap-8">
                                <Table>
                                    {displayItems.length === 0 && <TableCaption>Add some items from the catalog</TableCaption> }
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead className="">#</TableHead>
                                            <TableHead>Item</TableHead>
                                            <TableHead className="text-center max-w-24">Qty</TableHead>
                                            <TableHead className="text-center max-w-28">Price</TableHead>
                                            <TableHead className="text-right">Total</TableHead>
                                            <TableHead></TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                    { 
                                        displayItems.map(({id, brand, size, pattern, lisi, qty, unit_price}, index) => (
                                            <TableRow>
                                                <TableCell className="font-bold">{index + 1}</TableCell>
                                                <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                                <TableCell className="text-center max-w-24">
                                                    <Input
                                                        onBlur={() => setItems(items.map((item, i) => {
                                                            if (i == index) {
                                                                item.qty = parseInt(displayItems[i].qty)
                                                            }

                                                            return item
                                                        }))} 
                                                        onChange={({target}) => {
                                                            updateQty(index, target.value)}} 
                                                        className={cn("text-center", showErrors && (!(parseInt(qty) > 0)) && "border-red-400")} 
                                                        value={qty} 
                                                    /> 
                                                </TableCell>
                                                <TableCell className="text-right max-w-28">
                                                    <Input
                                                        onBlur={() => setItems(items.map((item, i) => {
                                                            if (i == index) {
                                                                item.unit_price = parseFloat(displayItems[i].unit_price)
                                                            }

                                                            return item
                                                        }))}
                                                        onChange={({target}) => updatePrice(index, target.value)} 
                                                        className={cn("text-right", showErrors && (!(parseFloat(unit_price) > 0)) && "border-red-400")}
                                                        value={unit_price} 
                                                    />
                                                </TableCell>
                                                <TableCell className="text-right">
                                                    {
                                                        (isNaN(parseFloat(displayItems[index].unit_price)) || isNaN(parseInt(displayItems[index].qty)))
                                                        ? "0.00" 
                                                        :(parseInt(displayItems[index].qty) * parseFloat(displayItems[index].unit_price)).toFixed(2)
                                                    }
                                                </TableCell>
                                                <TableCell>
                                                    <Button
                                                        onClick={() => { deleteItemAt(index) }}
                                                        type="button" size="icon" variant="secondary"
                                                    >
                                                        <X />
                                                    </Button>
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
                                        <TableCell></TableCell>
                                    </TableRow>
                                    {
                                        discount.percentage > 0 &&
                                        <TableRow className="font-semibold">
                                            <TableCell></TableCell>
                                            <TableCell>Discount</TableCell>
                                            <TableCell className="max-w-24"></TableCell>
                                            <TableCell className="text-right max-w-28">{discount.percentage} %</TableCell>
                                            <TableCell className="text-right">- ৳ {(subTotal() * (discount.percentage/100.0)).toFixed(2)}</TableCell>
                                            <TableCell>
                                                <Button
                                                    onClick={() => { 
                                                        setDiscount({
                                                            ...discount,
                                                            percentage: 0
                                                        })
                                                     }}
                                                    type="button" size="icon" variant="secondary"
                                                >
                                                    <X />
                                                </Button>
                                            </TableCell>
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
                                            <TableCell>
                                                <Button
                                                    onClick={() => {
                                                        setDiscount({
                                                            ...discount,
                                                            value: 0
                                                        })
                                                    }}
                                                    type="button" size="icon" variant="secondary"
                                                >
                                                    <X />
                                                </Button>
                                            </TableCell>
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
                                            <TableCell>
                                                <Button
                                                    onClick={() => { 
                                                        setTax({
                                                            ...tax,
                                                            percentage: 0
                                                        })
                                                    }}
                                                    type="button" size="icon" variant="secondary"
                                                >
                                                    <X />
                                                </Button>
                                            </TableCell>
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
                                            <TableCell>
                                                <Button
                                                    onClick={() => { 
                                                        setTax({
                                                            ...tax,
                                                            value: 0
                                                        })
                                                    }}
                                                    type="button" size="icon" variant="secondary"
                                                >
                                                    <X />
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                    }
                                    <TableRow className="font-extrabold">
                                        <TableCell></TableCell>
                                        <TableCell>Grand total</TableCell>
                                        <TableCell className="text-center max-w-24">{totalQty()}</TableCell>
                                        <TableCell className="max-w-28"></TableCell>
                                        <TableCell className="text-right">৳ {grandTotal().toFixed(2)}</TableCell>
                                        <TableCell></TableCell>
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
                                <div className="w-full flex justify-end gap-3">
                                    <Button disabled={displayItems.length == 0} onClick={() => {
                                        setEditDiscount(true)
                                    }} variant="outline" size="sm">Add Discount</Button>
                                    <Button disabled={displayItems.length == 0} onClick={() => {
                                        setEditTax(true)
                                    }} variant="outline" size="sm">Add Tax</Button>
                                    <Button disabled={displayItems.length == 0} onClick={() => fn()} variant="secondary" size="sm">
                                        Continue
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                    <div className={cn(
                        "w-full transition-all relative md:w-1/2",
                        show ? "opacity-100" : "opacity-0",
                        !dir && (show ? "-right-0" : "-right-16"), 
                        dir && (show ? "-left-0" : "-left-16")
                    )}>
                        <Card className="w-full">
                            <CardHeader>
                                <CardTitle>Product Catalog</CardTitle>
                                <CardDescription>
                                    All products
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <ProductsTable
                                    showStock={true} 
                                    apiToken={apiToken}
                                    addItem={(item) => {
                                        console.log('additem', item)
                                        setItems([...items, {
                                            ...item,
                                            qty: 1,
                                            unit_price: 0
                                        }])
                                    }} 
                                />
                            </CardContent>
                        </Card>
                    </div>
                    
                </div>}
                { current == 1 && <div className="w-full pt-5 flex gap-4 items-start justify-center">
                    <Card className={cn(
                        "w-1/2 print:w-full transition-all relative print:border-0 print:shadow-none",
                        show ? "opacity-100" : "opacity-0",
                        !dir && (show ? "-right-0" : "-right-16"), 
                        dir && (show ? "-left-0" : "-left-16"),
                    )}>
                        <CardHeader>
                            <CardTitle>
                                Confirm Order
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="w-full flex flex-col gap-4">
                                <div className="w-full flex justify-between">
                                    <div className="flex flex-col gap-2 w-1/3">
                                        <Label className="font-semibold">Order #</Label>
                                        <span className="font-mono text-sm">-</span>
                                    </div>
                                    <div className="flex flex-col gap-2 w-1/3">
                                        <Label className="font-semibold">Customer ID</Label>
                                        <span className="font-mono">
                                            {someCustomers.find(({id}) => id == customerId)?.id}
                                        </span>
                                    </div>
                                    <div className="flex flex-col gap-2 w-1/3">
                                        <Label className="font-semibold">Order Date</Label>
                                        <span className="font-mono text-sm">{orderDate?.toDateString()}</span>
                                    </div>
                                </div>
                                <div className="w-full flex justify-between">
                                    <div className="flex flex-col gap-2 w-2/3">
                                        <Label className="font-semibold">Customer</Label>
                                        <span className="font-mono text-sm">
                                            {someCustomers.find(({id}) => id == customerId)?.name}
                                        </span>
                                    </div>
                                </div>
                                <div className="flex flex-col gap-4">
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
                                        <TableBody className="font-mono">
                                        {
                                            items.map(({id, brand, size, pattern, lisi, qty, unit_price}, i) => (
                                                <TableRow>
                                                    <TableCell className="text-center">{ i+1 }</TableCell>
                                                    <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                                    <TableCell className="text-center">{qty}</TableCell>
                                                    <TableCell className="text-right">৳ {unit_price.toFixed(2)}</TableCell>
                                                    <TableCell className="text-right">৳ {(qty*unit_price).toFixed(2)}</TableCell>
                                                </TableRow>
                                            ))
                                        }
                                        <TableRow className='font-bold'>
                                            <TableCell className="text-center"></TableCell>
                                            <TableCell>Sub total</TableCell>
                                            <TableCell className="text-center">{totalQty()}</TableCell>
                                            <TableCell className="text-right"></TableCell>
                                            <TableCell className="text-right">৳ {subTotal().toFixed(2)}</TableCell>
                                        </TableRow>
                                        {
                                            discount.percentage > 0 &&
                                            <TableRow>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Discount</TableCell>
                                                <TableCell className="text-center">-</TableCell>
                                                <TableCell className="text-right">{discount.percentage} %</TableCell>
                                                <TableCell className="text-right">৳ {(subTotal() * (discount.percentage/100)).toFixed(2)}</TableCell>
                                            </TableRow>
                                        }
                                        {
                                            discount.value > 0 &&
                                            <TableRow>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Discount</TableCell>
                                                <TableCell className="text-center">-</TableCell>
                                                <TableCell className="text-right">৳ {discount.value.toFixed(2)}</TableCell>
                                                <TableCell className="text-right">৳ {discount.value.toFixed(2)}</TableCell>
                                            </TableRow>
                                        }
                                        {
                                            tax.percentage > 0 &&
                                            <TableRow>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Tax</TableCell>
                                                <TableCell className="text-center">+</TableCell>
                                                <TableCell className="text-right">{tax.percentage + "%"}</TableCell>
                                                <TableCell className="text-right">৳ {(subTotal() * (tax.percentage/100)).toFixed(2)}</TableCell>
                                            </TableRow>
                                        }
                                        {
                                            tax.value > 0 &&
                                            <TableRow>
                                                <TableCell className="text-center"></TableCell>
                                                <TableCell>Tax</TableCell>
                                                <TableCell className="text-center">+</TableCell>
                                                <TableCell className="text-right">৳ {tax.value.toFixed(2)}</TableCell>
                                                <TableCell className="text-right">৳ {tax.value.toFixed(2)}</TableCell>
                                            </TableRow>
                                        }
                                        <TableRow className="font-bold">
                                            <TableCell className="text-center"></TableCell>
                                            <TableCell>Grand Total</TableCell>
                                            <TableCell className="text-center"></TableCell>
                                            <TableCell className="text-right"></TableCell>
                                            <TableCell className="text-right">৳ {grandTotal().toFixed(2)}</TableCell>
                                        </TableRow>
                                        </TableBody>
                                    </Table>
                                    <div className="w-full flex justify-end">
                                        <Button
                                            type="button" 
                                            size="sm" 
                                            variant="secondary"
                                            onClick={() => {       
                                                router.post(route('orders.store'), {
                                                    customer_id: customerId,
                                                    order_on: orderDate,
                                                    tax_percentage: tax.percentage,
                                                    tax_amount: tax.value,
                                                    discount_percent: discount.percentage,
                                                    discount_amount: discount.value,
                                                    items: items
                                                })
                                            }}
                                        >
                                            Confirm
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>}
            </div>
        </AppLayout>
    )
}