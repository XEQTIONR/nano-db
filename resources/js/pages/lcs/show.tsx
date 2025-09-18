import AppLayout from "@/layouts/app-layout"
import { LetterOfCredit, BreadcrumbItem } from "@/types"
import { Head, router } from '@inertiajs/react';
import { Label } from "@/components/ui/label";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip"
import { ArrowLeft } from "lucide-react";

export default function Show({ letterOfCredit } : { 
    letterOfCredit: {data: LetterOfCredit}
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Letter of Credit",
            href: route('lcs.index'),
        },
        {
            title: "# " + letterOfCredit.data.lc_num,
            href: "",
        },
    ];
    return (
        <AppLayout 
            breadcrumbs={breadcrumbs}
            controls={
                <div className="flex items-end gap-2 justify-end">
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    onClick={() => router.visit(route('lcs.index'))} 
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <ArrowLeft />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Back to letters of credit</p>
                            </TooltipContent>
                        </Tooltip>
                </div>
            }
        >
            <Head title={breadcrumbs[0].title} />
            <div className="w-full">
                <h1 className="text-2xl md:text-4xl font-bold pl-4 mt-2">Letter of Credit <span className="text-muted text-2xl">#{letterOfCredit.data.lc_num}</span></h1>
                <div className="w-full gap-5 flex flex-wrap lg:flex-nowrap items-start p-4">
                    <Card className="w-full lg:w-1/2 dark:bg-neutral-900 dark:border-none rounded-3xl">
                        <CardHeader>
                            <CardTitle>Information</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Letter of Credit Number</Label>
                                    {letterOfCredit.data.lc_num}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Invoice Number</Label>
                                    {letterOfCredit.data.invoice_no}
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Date Issued</Label>
                                    {new Date(letterOfCredit.data.date_issued).toLocaleDateString()}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Date Expired</Label>
                                    {new Date(letterOfCredit.data.date_expiry).toLocaleDateString()}
                                </div>
                            </div>
                            <div className="flex flex-col gap-6 mb-6">
                                <div className="grid gap-2">
                                    <Label className="text-xs" htmlFor="lc_num">Applicant</Label>
                                    {letterOfCredit.data.applicant.split('<br/>').map((item) => <p>{item}</p>)}
                                </div>
                            </div>
                            <div className="flex flex-col gap-6 mb-6">
                                <div className="grid gap-2">
                                    <Label className="text-xs" htmlFor="lc_num">Beneficiary</Label>
                                    {letterOfCredit.data.beneficiary.split('<br/>').map((item) => <p>{item}</p>)}
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Departing Port</Label>
                                    {letterOfCredit.data.port_depart}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Port of Arrival</Label>
                                    {letterOfCredit.data.port_arrive}
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Currency</Label>
                                    {letterOfCredit.data.currency_code}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Rate</Label>
                                    {letterOfCredit.data.exchange_rate}
                                </div>
                                
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2"></div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Value</Label>
                                    {letterOfCredit.data.foreign_amount.toFixed(2)}
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Foreign Expense</Label>
                                    {letterOfCredit.data.foreign_expense.toFixed(2)}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Domestic Expense</Label>
                                    {letterOfCredit.data.domestic_expense.toFixed(2)}
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card className="flex flex-col w-full lg:w-1/2  bg-neutral-50 dark:bg-neutral-900 border-none rounded-3xl">
                        <CardHeader>
                            <CardTitle>Proforma Invoice</CardTitle>
                            <CardDescription># {letterOfCredit.data.invoice_no}</CardDescription>
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
                            <TableBody className="font-mono text-xs">
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
            </div>
            
        </AppLayout> 
    )
}