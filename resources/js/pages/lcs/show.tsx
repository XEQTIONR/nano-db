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
import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Hash } from "lucide-react";

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
            <div className="w-full p-4 flex flex-wrap">
                <div className="w-full lg:w-1/2 p-2">
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
                <Card className="flex flex-col w-full lg:w-1/2">
                    <CardHeader>
                        <CardTitle>Proforma Invoice</CardTitle>
                        <CardDescription className="flex items-center gap-1"><Hash size={18} /> {letterOfCredit.data.invoice_no}</CardDescription>
                    </CardHeader>
                    <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead className="">#</TableHead>
                                <TableHead>Item</TableHead>
                                <TableHead className="text-center">Qty</TableHead>
                                <TableHead className="text-right">Price</TableHead>
                                <TableHead className="text-right">Sub total</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                        { 
                            letterOfCredit.data?.items?.map(({id, brand, size, pattern, lisi, qty, unit_price}, index) => (
                                <TableRow>
                                    <TableCell className="font-bold">{index + 1}</TableCell>
                                    <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                    <TableCell className="text-center">
                                        {qty} 
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {unit_price}
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {(qty * unit_price).toFixed(2)}
                                    </TableCell>
                                </TableRow>
                            )) 
                        }
                        </TableBody>
                    </Table>
                    </CardContent>
                </Card>
            </div>
        </AppLayout> 
    )
}