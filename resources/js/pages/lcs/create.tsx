import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import { Badge } from '@/components/ui/badge';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from "@/components/ui/button"
import {
  Card,
  CardAction,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { useState } from 'react';
import LetterOfCreditForm from './components/lc-form';
import { LetterOfCredit } from '@/types';
import StockTable from '@/components/stock-table';

export default function Create({apiToken} : {apiToken: string}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Create a New Letter of Credit',
            href: route('lcs.index'),
        },
    ];

    const [show, setShow] = useState(true);
    const [dir, setDir] = useState(true);
    const [current, setCurrent] = useState(0)
    const steps = [
        'Record Information',
        'Add Proforma Invoice',
        'Confirm'
    ]
    const fn = (prev = false) => {
        setDir(prev)
        setShow(false)
        setCurrent((prev ? (current - 1) : (current + 1)) % steps.length)
        
        if (prev) {
            if ((current - 1) < 0)
                setCurrent(steps.length - 1)
            else
                setCurrent((current - 1))
        } else {
            setCurrent((current+1) % steps.length)
        }
        
        setTimeout(() => setShow(true), 1)
    }

    const [lcData, setLcData] = useState<LetterOfCredit>({
        lc_num: "",
        date_issued: undefined,
        date_expiry: undefined,
        applicant: "",
        beneficiary: "",
        port_depart: "",
        port_arrive: "",
        currency_code: "",
        rate: 0,
        value: 0,
        foreign_expense: 0,
        domestic_expense: 0,
        notes: "",
    })

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create LC" />
            <div className="flex flex-col h-full items-start rounded-xl p-4">
                <div className="w-full flex gap-10 items-center">
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
                        <Button className="cursor-pointer" onClick={() => fn(true)} variant="secondary">
                        <ChevronLeft />
                        </Button>
                        <Button className="cursor-pointer" onClick={() => fn(false)} variant="secondary">
                            <ChevronRight />
                        </Button>
                    </div>
                    
                </div>
                <div className="w-full pt-5 flex gap-4 items-start">
                    {
                        current == 0 && (
                            <Card 
                                className={cn(
                                    "w-full lg:w-1/2 transition-all relative",
                                    show ? "opacity-100" : "opacity-0",
                                    !dir && (show ? "-right-0" : "-right-16"), 
                                    dir && (show ? "-left-0" : "-left-16"), )}
                            >
                                <LetterOfCreditForm initialValue={lcData} onSubmit={(data) => {
                                    setLcData(data)
                                    fn()
                                }} />
                            </Card>
                        )
                    }
                    {
                        current == 1 
                        && (<Card 
                                className={cn(
                                    "w-1/2 max-w-3xl transition-all relative",
                                    show ? "opacity-100" : "opacity-0",
                                    !dir && (show ? "-right-0" : "-right-16"), 
                                    dir && (show ? "-left-0" : "-left-16"), 
                                )}
                    
                        >
                            <CardHeader>
                                <CardTitle>Proforma Invoice</CardTitle>
                                <CardDescription>
                                    Enter details about your new letter of credit
                                </CardDescription>
                                <CardAction>
                                    <Button className="hidden" variant="secondary">Next Step
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
                                    </div>
                                </form>
                            </CardContent>
                        </Card>)
                    }
                    {
                        current == 2 
                        && (<Card className={cn(
                                    "w-1/2 max-w-3xl transition-all relative",
                                    show ? "opacity-100" : "opacity-0",
                                    !dir && (show ? "-right-0" : "-right-16"), 
                                    dir && (show ? "-left-0" : "-left-16"), 
                                )}
                    
                        >
                            <CardHeader>
                                <CardTitle>Confirm</CardTitle>
                                <CardDescription>
                                    Enter details about your new letter of credit
                                </CardDescription>
                                <CardAction>
                                    <Button className="hidden" variant="secondary">Next Step
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
                                    </div>
                                </form>
                            </CardContent>
                        </Card>)
                    }

                    <Card className="w-1/2">
                        <CardHeader>
                            <CardTitle>Current Stock</CardTitle>
                            <CardDescription>
                                Products currently available
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <StockTable apiToken={apiToken} />
                        </CardContent>
                    </Card> 
                </div>
                
            </div>
        </AppLayout>
    );
}