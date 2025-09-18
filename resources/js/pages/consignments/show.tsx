import AppLayout from "@/layouts/app-layout"
import { LetterOfCredit, BreadcrumbItem, Consignment } from "@/types"
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

export default function Show({ consignment } : { 
    consignment: {data: Consignment}
}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: "Consignments",
            href: route('consignments.index'),
        },
        {
            title: "# " + consignment.data.bol,
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
                                    onClick={() => router.visit(route('consignments.index'))} 
                                    className="hover:cursor-pointer text-xs" 
                                    size="icon" 
                                    variant="ghost"
                                >
                                    <ArrowLeft />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>
                                <p>Back to consignments</p>
                            </TooltipContent>
                        </Tooltip>
                </div>
            }
        >
            <Head title={breadcrumbs[0].title} />
            <div className="w-full">
                <h1 className="text-2xl md:text-4xl font-bold pl-4 mt-2">Consignment <span className="text-muted text-2xl">#{consignment.data.bol}</span></h1>
                <div className="w-full gap-5 flex flex-wrap lg:flex-nowrap items-start p-4">
                    <Card className="w-full lg:w-1/2 dark:bg-neutral-900 dark:border-none rounded-3xl">
                        <CardHeader>
                            <CardTitle>Information</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Bill of lading Number</Label>
                                    {consignment.data.bol}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Letter of Credit Number</Label>
                                    {consignment.data.lc_num}
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Landed On</Label>
                                    {new Date(consignment.data.land_date).toLocaleDateString()}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Rate</Label>
                                    <span className="tracking-wide">
                                        {consignment.data.exchange_rate.toFixed(2)}
                                    </span>
                                </div>
                            </div>
                            {/* <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Departing Port</Label>
                                    {letterOfCredit.data.port_depart}
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Port of Arrival</Label>
                                    {letterOfCredit.data.port_arrive}
                                </div>
                            </div> */}
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                {/* <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Currency</Label>
                                    {letterOfCredit.data.currency_code}
                                </div> */}
                                
                                
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Value</Label>
                                    <span className="tracking-wide">
                                        { currencyFormat(consignment.data.currency_code ?? "USD",  consignment.data.value)}
                                    </span>
                                </div>
                                <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Value (local)</Label>
                                    <span className="tracking-wide">
                                        {currencyFormat('BDT',consignment.data.value_local ?? 0)}
                                    </span>
                                </div>
                            </div>
                            <div className="flex gap-6 mb-6 flex-wrap md:flex-nowrap">
                                
                                {/* <div className="grid gap-2 w-full md:w-1/2">
                                    <Label className="text-xs" htmlFor="lc_num">Domestic Expense</Label>
                                    <span className="tracking-wide">
                                        {currencyFormat('BDT',letterOfCredit.data.domestic_expense)}
                                    </span>
                                </div> */}
                            </div>
                        </CardContent>
                    </Card>
                    <div className="flex flex-col gap-4 w-full lg:w-1/2">
                        <Card className="flex flex-col  bg-neutral-50 dark:bg-neutral-900 border-none rounded-3xl">
                            <CardHeader>
                                <CardTitle>Containers</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="w-full flex flex-col gap-4">
                                {
                                    consignment.data.containers?.map((container) => (
                                        <div className="border px-1 overflow-x-scroll rounded-lg dark:bg-neutral-950">
                                        <Table>
                                            <TableHeader>
                                                <TableRow className="hover:bg-transparent">
                                                    {/* <TableHead className=""></TableHead> */}
                                                    <TableHead colSpan={5}>Container # {container.container_num}</TableHead>
                                                </TableRow>
                                                <TableRow className="hover:bg-transparent">
                                                    <TableHead className="">#</TableHead>
                                                    <TableHead>Item</TableHead>
                                                    <TableHead className="text-center">Qty</TableHead>
                                                    <TableHead className="text-right">Price</TableHead>
                                                    <TableHead className="text-right">Sub total</TableHead>
                                                    <TableHead className="text-right">Total tax</TableHead>
                                                    <TableHead className="text-right">Total weight</TableHead>
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody className="font-mono text-xs">
                                            { 
                                                container.contents?.map(({id, brand, size, pattern, lisi, qty, unit_price, total, total_tax, total_weight}, index) => (
                                                    <TableRow className="hover:bg-transparent">
                                                        <TableCell className="font-bold">{index + 1}</TableCell>
                                                        <TableCell>({id}) {brand} {size} {pattern} {lisi}</TableCell>
                                                        <TableCell className="text-center">
                                                            {qty} 
                                                        </TableCell>
                                                        <TableCell className="text-right">
                                                            {currencyFormat("BDT", unit_price)}
                                                        </TableCell>
                                                        <TableCell className="text-right">
                                                            {currencyFormat("BDT", total)}
                                                        </TableCell>
                                                        <TableCell className="text-right">
                                                            {currencyFormat("BDT", total_tax)}
                                                        </TableCell>
                                                        <TableCell className="text-right">
                                                            {total_weight.toFixed(2)}
                                                        </TableCell>
                                                    </TableRow>
                                                )) 
                                            }
                                            </TableBody>
                                            <TableFooter className="dark:bg-neutral-950">
                                                <TableRow className="font-mono" >
                                                    <TableCell className="rounded-bl-lg"></TableCell>
                                                    <TableCell>Total</TableCell>
                                                    <TableCell className="text-center">
                                                        {container.contents?.reduce((prev, { qty }) => prev + qty, 0)} 
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        {currencyFormat("BDT", container.contents?.reduce((prev, { total }) => prev + total, 0) ?? 0)}
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        {currencyFormat("BDT", container.contents?.reduce((prev, { total_tax }) => prev + total_tax, 0) ?? 0)}
                                                    </TableCell>
                                                    <TableCell className="text-right rounded-br-lg">
                                                        {container.contents?.reduce((prev, { total_weight }) => prev + total_weight, 0).toFixed(2)}
                                                    </TableCell>
                                                </TableRow>
                                            </TableFooter>
                                        </Table>
                                        </div>
                                    ))
                                }
                                </div>
                            {/* <Table>
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
                            </Table> */}
                            </CardContent>
                        </Card>
                        {/* <Card className="flex flex-col  bg-neutral-50 dark:bg-neutral-900 border-none rounded-3xl">
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
                        </Card> */}
                    </div>
                </div>
            </div>
            
        </AppLayout> 
    )
}