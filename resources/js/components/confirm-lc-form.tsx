import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/components/ui/alert-dialog"
import {
    Card,
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

import { ChevronRight, NotebookPen, StickyNote } from "lucide-react";

import { LetterOfCredit, type InvoiceItem } from "@/types";
import { Button } from "./ui/button";
import { currencyFormat } from "@/lib/utils";

export default function ConfirmLcForm({lcData, items, onSubmit = undefined} : {
    lcData: LetterOfCredit,
    items: InvoiceItem[],
    onSubmit?: (data: LetterOfCredit, items: InvoiceItem[]) => void
}) {

    return (
        <>
        <CardHeader>
            <CardTitle>Confirm</CardTitle>
            <CardDescription>
                Confirm details about your letter of credit and proforma invoice
            </CardDescription>
            <CardAction>
                <AlertDialog>
                    <AlertDialogTrigger asChild>
                        <Button
                            type="button"
                            variant="secondary"
                        >
                            Finish
                            <ChevronRight />
                        </Button>
                    </AlertDialogTrigger>
                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>Create new letter of credit?</AlertDialogTitle>
                            <AlertDialogDescription>
                                Are you sure you want to create this letter of credit?
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>
                                Cancel
                            </AlertDialogCancel>
                            <AlertDialogAction onClick={() => {
                                if (onSubmit) {
                                    onSubmit(lcData, items)
                                }
                            }}>
                                Confirm
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>
            </CardAction>
        </CardHeader>
        <CardContent>
            <form className="flex flex-wrap h-10/12">
                <div className="w-full lg:w-1/2">
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Letter of Credit Number</Label>
                            {lcData.lc_num}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Date Issued</Label>
                            {lcData.date_issued?.toDateString()}
                        </div>
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Date Expired</Label>
                            {lcData.date_expiry?.toDateString()}
                        </div>
                    </div>
                    <div className="flex flex-col gap-6 mb-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Applicant</Label>
                            {lcData.applicant}
                        </div>
                    </div>
                    <div className="flex flex-col gap-6 mb-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Beneficiary</Label>
                            {lcData.beneficiary}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Departing Port</Label>
                            {lcData.port_depart}
                        </div>
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Port of Arrival</Label>
                            {lcData.port_arrive}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/3">
                            <Label htmlFor="lc_num">Currency</Label>
                            {lcData.currency_code}
                        </div>
                        <div className="grid gap-2 w-1/3">
                            <Label htmlFor="lc_num">Rate</Label>
                            {lcData.exchange_rate}
                        </div>
                        <div className="grid gap-2 w-1/3">
                            <Label htmlFor="lc_num">Value</Label>
                            {currencyFormat(lcData.currency_code, lcData.foreign_amount)}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Foreign Expense</Label>
                            {currencyFormat(lcData.currency_code, lcData.foreign_expense)}
                        </div>
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Domestic Expense</Label>
                            {currencyFormat("BDT", lcData.domestic_expense)}
                        </div>
                    </div>
                </div>
                <div className="flex flex-col w-full gap-6 lg:w-1/2">
                    <div className="flex gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Proforma Invoice Number</Label>
                            {lcData.invoice_no}
                        </div>
                    </div>
                    <Table>
                        <TableCaption>Confirm the proforma invoice details</TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead className="">#</TableHead>
                                <TableHead>Item</TableHead>
                                <TableHead>Qty</TableHead>
                                <TableHead>Price</TableHead>
                                <TableHead>Sub total</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                        { 
                            items.map(({id, brand, size, pattern, lisi, qty, unit_price}, index) => (
                                <TableRow>
                                    <TableCell className="font-bold">{index + 1}</TableCell>
                                    <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                    <TableCell className="text-center">
                                        {qty} 
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {currencyFormat(lcData.currency_code, unit_price)}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {currencyFormat(lcData.currency_code, qty * unit_price)}
                                    </TableCell>
                                </TableRow>
                            )) 
                        }
                        </TableBody>
                    </Table>
                    { (lcData.notes && lcData.notes.length > 0) && <Card className="flex gap-6">
                        <CardHeader className="grid gap-2">
                            <CardTitle className="flex items-center gap-2"><StickyNote size={18} /> Note</CardTitle>
                            
                        </CardHeader>
                        <CardContent>
                            {lcData.notes}
                        </CardContent>
                    </Card> }
                </div>
                
            </form>
        </CardContent>
        </>
    )
}