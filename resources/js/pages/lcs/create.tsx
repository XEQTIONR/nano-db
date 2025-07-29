import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { ChevronRight } from 'lucide-react';

import { Button } from "@/components/ui/button"
import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { InputCalendar } from '@/components/ui/input-calendar';
import { Textarea } from "@/components/ui/textarea"



export default function Create() {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Create a New Letter of Credit',
            href: route('lcs.index'),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="LCs" />
            <div className="flex flex-col h-full items-start rounded-xl p-4">
                <div className="w-full flex gap-10 items-center">
                    <div className="flex items-center gap-4">
                        <Badge className="h-7 min-w-7 px-1.5 py-1 rounded-full bg-indigo-500 text-xs font-bold font-mono text-white">1</Badge>
                        <span className="text-base font-bold ">Record Information</span>
                    </div>
                    <div className="flex items-center gap-4">
                        <Badge className="h-7 min-w-7 px-1.5 py-1 rounded-full bg-neutral-800 text-xs font-bold font-mono text-neutral-400">2</Badge>
                        <span className="text-base font-bold text-neutral-700">Add Proforma Invoice</span>
                    </div>
                    <div className="flex items-center gap-4">
                        <Badge className="h-7 min-w-7 px-1.5 py-1 rounded-full bg-neutral-800 text-xs font-bold font-mono text-neutral-400">3</Badge>
                        <span className="text-base font-bold text-neutral-700">Confirm</span>
                    </div>
                    {/* <Badge variant="outline" className="h-7 min-w-7 p-1.5 rounded-full font-semibold border-indigo-500">2</Badge> */}
                    {/* <Badge variant="outline" className="h-6 min-w-6 p-1.5 rounded-full">3</Badge> */}
                    {/* <Badge variant="outline" className="h-6 min-w-6 p-1.5 rounded-full">4</Badge> */}
                    {/* <Badge variant="outline" className="h-6 min-w-6 p-1.5 rounded-full">5</Badge> */}
                </div>
                <div className="w-full pt-5">
                    <Card className="w-full max-w-3xl">
                        <CardHeader>
                            <CardTitle>Letter of Credit Information</CardTitle>
                            <CardDescription>
                                Enter details about your new letter of credit
                            </CardDescription>
                            <CardAction>
                                <Button variant="secondary">Next Step
                                    <ChevronRight />
                                </Button>
                            </CardAction>
                        </CardHeader>
                        <CardContent>
                            <form>
                                <div className="flex flex-col gap-6">
                                    <div className="grid gap-2">
                                        <Label htmlFor="lc_num">Letter of Credit Number</Label>
                                        <Input
                                            id="lc_num"
                                            placeholder="F20 | Document Credit Number"
                                            required
                                        />
                                    </div>
                                    <div className="flex justify-between w-full gap-5">
                                        <div className="flex flex-col gap-2">
                                            <Label htmlFor="date_issued">Issue Date</Label>
                                            <InputCalendar id="date_issued" placeholder="F31C | Select date issued" />
                                        </div>
                                    
                                        <div className="grid gap-2">
                                            <Label htmlFor="date_expiry">Expiry Date</Label>
                                            <InputCalendar id="date_expiry"  placeholder="F31D | Select expiry date" />
                                        </div>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="applicant">Applicant</Label>
                                        <Textarea placeholder='F50 | Applicant name and address' id="applicant" />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="beneficiary">Beneficiary</Label>
                                        <Textarea placeholder='F59 | Beneficiary name and address' id="beneficiary" />
                                    </div>
                                    <Separator />
                                    <div className="flex justify-end w-full gap-5">
                                        <div className="grid gap-2 w-1/2">
                                            <Label htmlFor="port_depart">Departing port</Label>
                                            <Input
                                                id="port_depart"
                                                placeholder="F44E"
                                                required
                                            />
                                        </div>
                                        <div className="grid gap-2 w-1/2">
                                            <Label htmlFor="port_arrive">Arriving port</Label>
                                            <Input
                                                id="port_arrive"
                                                placeholder="F44F"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div className="flex justify-start w-full gap-5">
                                        <div className="flex gap-5 w-3/6">
                                            <div className="grid gap-2">
                                                <Label htmlFor="currency_code">Currency Code</Label>
                                                <Input
                                                    id="currency_code"
                                                    placeholder="F32B"
                                                    required
                                                />
                                            </div>
                                            <div className="grid gap-2">
                                                <Label className="not-only:" htmlFor="exchange_rate">Rate</Label>
                                                <Input
                                                    className="text-right"
                                                    placeholder="0.00"
                                                    id="exchange_rate"
                                                    required
                                                />
                                            </div>
                                        </div>
                                        <div className="grid gap-2 w-3/6">
                                            <Label htmlFor="exchange_rate">Value (foreign currency)</Label>
                                            <Input
                                                className="text-right"
                                                placeholder="0.00"
                                                id="exchange_rate"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div className="w-full flex justify-end gap-5">
                                        <div className="grid gap-2 w-3/6">
                                        </div>
                                        <div className="grid gap-2 w-3/6">
                                            <Label htmlFor="exchange_rate">Amount in local currency</Label>
                                            <Input
                                                className="text-right"
                                                disabled={true}
                                                id="exchange_rate"
                                                value={5000.0}
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div className="flex justify-between w-full gap-5">
                                        <div className="grid gap-2 w-1/2">
                                            <Label htmlFor="foreign_expense">Foreign Expenses Paid</Label>
                                            <Input
                                                id="foreign_expense"
                                                className="text-right"
                                                placeholder="0.00"
                                                required
                                            />
                                        </div>
                                        <div className="grid gap-2 w-1/2">
                                            <Label htmlFor="domestic_expense">Domestic Expenses Paid</Label>
                                            <Input
                                                id="domestic_expense"
                                                className="text-right"
                                                placeholder="0.00"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="notes">Notes</Label>
                                        <Textarea id="notes" />
                                    </div>
                                </div>
                            </form>
                        </CardContent>
                        {/* <CardFooter className="flex-col gap-2">
                            <Button type="submit" className="w-full">
                            Login
                            </Button>
                            <Button variant="outline" className="w-full">
                            Login with Google
                            </Button>
                        </CardFooter> */}
                    </Card>
                </div>
                
            </div>
        </AppLayout>
    );
}