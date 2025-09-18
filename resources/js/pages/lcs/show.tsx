import AppLayout from "@/layouts/app-layout"
import { LetterOfCredit, BreadcrumbItem } from "@/types"
import { Head, router } from '@inertiajs/react';
import { Label } from "@/components/ui/label";
import {
  Table,
  TableBody,
  TableCell,
  TableFooter,
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
import { currencyFormat } from "@/lib/utils";

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
                <h1 className="text-2xl md:text-4xl font-bold pl-4 mt-2">Letter of credit <span className="text-muted text-2xl">#{letterOfCredit.data.lc_num}</span></h1>
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
                                    <span className="tracking-wide">
                                        {letterOfCredit.data.exchange_rate.toFixed(2)}
                                    </span>
                                </div>
                                
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2"></div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Value</Label>
                                    <span className="tracking-wide">
                                        {currencyFormat(letterOfCredit.data.currency_code,letterOfCredit.data.foreign_amount)}
                                    </span>
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Foreign Expense</Label>
                                    <span className="tracking-wide">
                                        {currencyFormat(letterOfCredit.data.currency_code,letterOfCredit.data.foreign_expense)}
                                    </span>
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Domestic Expense</Label>
                                    <span className="tracking-wide">
                                        {currencyFormat('BDT',letterOfCredit.data.domestic_expense)}
                                    </span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <div className="flex flex-col gap-4 w-full lg:w-1/2">
                        <Card className="flex flex-col  bg-neutral-50 dark:bg-neutral-900 border-none rounded-3xl">
                            <CardHeader>
                                <CardTitle>Proforma Invoice</CardTitle>
                                <CardDescription># {letterOfCredit.data.invoice_no}</CardDescription>
                            </CardHeader>
                            <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow className="hover:bg-transparent">
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
                                        <TableRow className="hover:bg-transparent">
                                            <TableCell className="font-bold">{index + 1}</TableCell>
                                            <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                            <TableCell className="text-center">
                                                {qty} 
                                            </TableCell>
                                            <TableCell className="text-right">
                                                {currencyFormat(letterOfCredit.data.currency_code, unit_price)}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                {currencyFormat(letterOfCredit.data.currency_code,(qty * unit_price))}
                                            </TableCell>
                                        </TableRow>
                                    )) 
                                }
                                </TableBody>
                                <TableFooter>
                                    <TableRow className="font-mono" >
                                        <TableCell></TableCell>
                                        <TableCell>Total</TableCell>
                                        <TableCell className="text-center">
                                            {letterOfCredit.data?.items?.reduce((prev, { qty }) => prev + qty, 0)} 
                                        </TableCell>
                                        <TableCell className="text-right">
                                        </TableCell>
                                        <TableCell className="text-right">
                                            {currencyFormat(
                                                letterOfCredit.data.currency_code,
                                                letterOfCredit.data?.items?.reduce((prev, { qty, unit_price }) => prev + (qty * unit_price), 0) ?? 0
                                            )} 
                                        </TableCell>
                                    </TableRow>
                                </TableFooter>
                            </Table>
                            </CardContent>
                        </Card>
                        <Card className="flex flex-col  bg-neutral-50 dark:bg-neutral-900 border-none rounded-3xl">
                            <CardHeader>
                                <CardTitle>Consignments</CardTitle>
                                <CardDescription>Consignemnts imported under this letter of credit.</CardDescription>
                            </CardHeader>
                            <CardContent>
                            <Table>
                                <TableHeader>
                                    <TableRow className="hover:bg-transparent">
                                        <TableHead className="">#</TableHead>
                                        <TableHead>Bill of Lading #</TableHead>
                                        <TableHead className="text-center">Landed On</TableHead>
                                        <TableHead className="text-right">Tax</TableHead>
                                        <TableHead className="text-right">Value</TableHead>
                                        <TableHead className="text-right">Value (Taka)</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody className="font-mono text-xs">
                                { 
                                    letterOfCredit.data?.consignments?.map(({bol, land_date, tax, value, value_local}, index) => (
                                        <TableRow className="hover:bg-transparent">
                                            <TableCell className="font-bold">{index + 1}</TableCell>
                                            <TableCell>{bol}</TableCell>
                                            <TableCell className="text-center">
                                                {land_date} 
                                            </TableCell>
                                            <TableCell className="text-right">
                                                {currencyFormat('BDT',tax)}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                {currencyFormat(letterOfCredit.data.currency_code, value ?? 0)}
                                            </TableCell>
                                            <TableCell className="text-right">
                                                {currencyFormat('BDT', value_local ?? 0)}
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
            </div>
            
        </AppLayout> 
    )
}