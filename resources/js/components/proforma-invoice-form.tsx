import {
  CardAction,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Label } from "@/components/ui/label"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
  TableCaption
} from "@/components/ui/table"

import { X, ChevronRight } from "lucide-react";

import { Tyre, type ProformaInvoiceItem } from "@/types";
import { Button } from "./ui/button";
import { Input } from "./ui/input";
import { useState, useEffect } from "react";
import { cn } from "@/lib/utils";

export default function ProformaInvoiceForm({invoiceNumber, items, updateItems = undefined, onSubmit = undefined} : {
    invoiceNumber?: string,
    items : ProformaInvoiceItem[],
    updateItems?: (items: Tyre[]) => void
    onSubmit?: (invoiceNum: string, items: ProformaInvoiceItem[]) => void
}) {

    const [currentItems, setCurrentItems] = useState<ProformaInvoiceItem[]>(items)
    const [showErrors, setShowErrors] = useState(false)
    const [invoiceNum, setInvoiceNum] = useState<string | undefined>(invoiceNumber)
    const [displayItems, setDisplayItems] = useState<{
        qty: string;
        unit_price: string;
        id: number;
        brand: string;
        size: string;
        pattern: string;
        lisi: string;
    }[]>([])

    const updateQty = (idx: number, value: string) => {
        const itms = displayItems
        itms[idx].qty = value
        setDisplayItems([...itms])
    }

    const updatePrice = (idx: number, value: string) => {
        const itms = displayItems
        itms[idx].unit_price = value
        setDisplayItems([...itms])
    }

    useEffect(() => {
        setCurrentItems([...items])
    }, [items])

    useEffect(() => {
        const itms = currentItems.map((item, i) => {
                if (item.id === displayItems[i]?.id) {
                    return {
                        ...item,
                        qty: displayItems[i].qty,
                        unit_price: displayItems[i].unit_price
                    }
                }

                return {
                    ...item,
                    qty: item.qty.toString(),
                    unit_price: item.unit_price.toString()
                }
            })
        
        setDisplayItems([...itms])
    }, [currentItems])

    return (
        <>
        <CardHeader>
            <CardTitle>Proforma Invoice</CardTitle>
            <CardDescription>
                Add a proforma invoice to your letter of credit
            </CardDescription>
            <CardAction>
                <Button
                    onClick={() => {

                        const itms = displayItems.map(item => {
                            return {
                                ...item,
                                qty: parseInt(item.qty),
                                unit_price: parseFloat(item.unit_price)
                            }
                        })

                        if (itms.every(({qty, unit_price}) => qty > 0 && unit_price > 0) && invoiceNum) {
                            if (onSubmit) {
                                onSubmit(invoiceNum, itms)
                            }
                        } else {
                            setShowErrors(true)
                        }
                    }} 
                    variant="secondary"
                >
                    Next Step
                    <ChevronRight />
                </Button>
            </CardAction>
        </CardHeader>
        <CardContent>
            <form>
                <div className="flex flex-col gap-6 mb-6">
                    <div className="grid gap-2">
                        <Label htmlFor="lc_num">Invoice Number</Label>
                        <Input
                            onChange={({target}) => setInvoiceNum( target.value == "" ? undefined : target.value )}
                            id="invoice_no"
                            placeholder="Invoice #"
                            required
                            className={cn(showErrors && !invoiceNum && "border-red-400")}
                        />
                    </div>
                </div>
                <Table>
                    {displayItems.length === 0 && <TableCaption>Add some items from the catalog</TableCaption> }
                    <TableHeader>
                        <TableRow>
                            <TableHead className="">#</TableHead>
                            <TableHead>Item</TableHead>
                            <TableHead>Qty</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>Sub total</TableHead>
                            <TableHead></TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                    { 
                        displayItems.map(({id, brand, size, pattern, lisi, qty, unit_price}, index) => (
                            <TableRow>
                                <TableCell className="font-bold">{index + 1}</TableCell>
                                <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                <TableCell className="text-center">
                                    <Input 
                                        onChange={({target}) => updateQty(index, target.value)} 
                                        className={cn("text-right", showErrors && (!(parseInt(qty) > 0)) && "border-red-400")} 
                                        value={qty} 
                                    /> 
                                </TableCell>
                                <TableCell className="text-right">
                                    <Input 
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
                                        onClick={() => {
                                            if (updateItems) {
                                                setDisplayItems(displayItems.filter((_,i) => i !== index))
                                                updateItems(currentItems.filter((_,i) => i !== index))
                                            }
                                        }}
                                        type="button" size="icon" variant="secondary"
                                    >
                                        <X />
                                    </Button>
                                </TableCell>
                            </TableRow>
                        )) 
                    }
                    </TableBody>

                </Table>
            </form>
        </CardContent>
        </>
    )
}