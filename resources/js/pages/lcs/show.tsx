import AppLayout from "@/layouts/app-layout"
import { LetterOfCredit, BreadcrumbItem } from "@/types"
import { Head } from '@inertiajs/react';
import { Label } from "@/components/ui/label";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
  TableCaption
} from "@/components/ui/table"

export default function Show({ letterOfCredit } : { 
    letterOfCredit: {data: LetterOfCredit}
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Letter of Credit # ", //+ letterOfCredit.lc_num,
            href: "",
        },
    ];
    return (
        letterOfCredit?.data && <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={breadcrumbs[0].title} />
            <div className="w-full p-4">
                <div className="w-1/2">
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Letter of Credit Number</Label>
                            {letterOfCredit.data.lc_num}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Date Issued</Label>
                            {letterOfCredit.data.date_issued}
                        </div>
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Date Expired</Label>
                            {letterOfCredit.data.date_expiry}
                        </div>
                    </div>
                    <div className="flex flex-col gap-6 mb-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Applicant</Label>
                            {letterOfCredit.data.applicant}
                        </div>
                    </div>
                    <div className="flex flex-col gap-6 mb-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Beneficiary</Label>
                            {letterOfCredit.data.beneficiary}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Departing Port</Label>
                            {letterOfCredit.data.port_depart}
                        </div>
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Port of Arrival</Label>
                            {letterOfCredit.data.port_arrive}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/3">
                            <Label htmlFor="lc_num">Currency</Label>
                            {letterOfCredit.data.currency_code}
                        </div>
                        <div className="grid gap-2 w-1/3">
                            <Label htmlFor="lc_num">Rate</Label>
                            {letterOfCredit.data.exchange_rate}
                        </div>
                        <div className="grid gap-2 w-1/3">
                            <Label htmlFor="lc_num">Value</Label>
                            {letterOfCredit.data.foreign_amount}
                        </div>
                    </div>
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Foreign Expense</Label>
                            {letterOfCredit.data.foreign_expense}
                        </div>
                        <div className="grid gap-2 w-1/2">
                            <Label htmlFor="lc_num">Domestic Expense</Label>
                            {letterOfCredit.data.domestic_expense}
                        </div>
                    </div>
                </div>
                {/* <div className="flex flex-col w-1/2">
                    <div className="flex gap-6 mb-6">
                        <div className="grid gap-2">
                            <Label htmlFor="lc_num">Proforma Invoice Number</Label>
                            {letterOfCredit.invoice_no}
                        </div>
                    </div>
                    <Table className="">
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
                            items.map(({id, brand, size, pattern, lisi, qty, price}, index) => (
                                <TableRow>
                                    <TableCell className="font-bold">{index + 1}</TableCell>
                                    <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                    <TableCell className="text-center">
                                        {qty} 
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {price}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {(qty * price).toFixed(2)}
                                    </TableCell>
                                </TableRow>
                            )) 
                        }
                        </TableBody>
                    </Table>
                </div> */}
            </div>
        </AppLayout> 
    )
}