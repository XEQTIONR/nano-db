import AppLayout from '@/layouts/app-layout';
import { ProformaInvoiceItem, Tyre, type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';

import { Badge } from '@/components/ui/badge';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';
import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"

import { useState } from 'react';
import LetterOfCreditForm from './components/lc-form';
import { LetterOfCredit } from '@/types';
import ProductsTable from '@/components/product-table';
import ProformaInvoiceForm from '@/components/proforma-invoice-form';
import ConfirmLcForm from '@/components/confirm-lc-form';


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
    const [items, setItems] = useState<Tyre[]>([])
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

    const [invoiceItems, setInvoiceItems] = useState<ProformaInvoiceItem[] | null>(null)
    const [lcData, setLcData] = useState<LetterOfCredit>({
        lc_num: "",
        date_issued: undefined,
        date_expiry: undefined,
        applicant: "",
        beneficiary: "",
        port_depart: "",
        port_arrive: "",
        currency_code: "",
        exchange_rate: 0,
        foreign_amount: 0,
        foreign_expense: 0,
        domestic_expense: 0,
        notes: "",
        invoice_no: ""
    })

    const [lcDirty, setLcDirty] = useState(false)
    const [invoiceDirty, setInvoiceDirty] = useState(false)

    const submit = (lcData: LetterOfCredit, items: ProformaInvoiceItem[]) => {
        router.post(route('lcs.store'), {
            lc: {
                ...lcData,
                date_issued: lcData.date_issued?.toISOString().split("T")[0],
                date_expiry: lcData.date_expiry?.toISOString().split("T")[0],
            },
            items,
        })
    }

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
                        <Button disabled={current == 0} className="cursor-pointer" onClick={() => fn(true)} variant="secondary">
                        <ChevronLeft />
                        </Button>
                        <Button disabled={
                            current == (steps.length - 1)
                            || (current == 0 && (lcData.lc_num.length == 0 || lcDirty))
                            || (current == 1 && (lcData.invoice_no?.length == 0 || invoiceDirty))
                        } className="cursor-pointer" onClick={() => {
                            fn(false)
                        }} variant="secondary">
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
                            <LetterOfCreditForm 
                                initialValue={lcData} 
                                onSubmit={(data) => {
                                    setLcDirty(false)
                                    setLcData(data)
                                    fn()
                                }}
                                onDirty={() => setLcDirty(true)} 
                                />
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
                        <ProformaInvoiceForm
                            invoiceNumber={lcData.invoice_no} 
                            items={invoiceItems ?? items.map(item => {
                                return {
                                    ...item,
                                    qty: 0,
                                    unit_price: 0,
                                }
                            })}
                            updateItems={(itms) => setItems(itms)} 
                            onSubmit={(invoiceNum: string, items: ProformaInvoiceItem[]) => {
                                setInvoiceDirty(false)
                                setInvoiceItems(items)
                                setLcData({
                                    ...lcData,
                                    invoice_no: invoiceNum
                                })
                                fn()
                            }}

                            onDirty={() => setInvoiceDirty(true)}
                        />
                    </Card>)
                }
                {
                    current == 2 
                    && (<Card className={cn(
                                "w-full transition-all relative",
                                show ? "opacity-100" : "opacity-0",
                                !dir && (show ? "-right-0" : "-right-16"), 
                                dir && (show ? "-left-0" : "-left-16"), 
                            )}
                
                    >
                        <ConfirmLcForm 
                            lcData={lcData} 
                            items={invoiceItems ?? []} 
                            onSubmit={submit}
                        />
                    </Card>)
                }
                {
                    current == 1 && (
                        <Card className="w-1/2">
                            <CardHeader>
                                <CardTitle>Product Catalog</CardTitle>
                                <CardDescription>
                                    All products
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <ProductsTable 
                                    apiToken={apiToken}
                                    addItem={(item) => {
                                        setItems([
                                            ...items,
                                            item
                                        ])

                                        if (invoiceItems) {
                                            setInvoiceItems([
                                                ...invoiceItems,
                                                {
                                                    ...item,
                                                    qty: 0,
                                                    unit_price: 0
                                                }
                                            ])
                                        }
                                        
                                    }} 
                                />
                            </CardContent>
                        </Card>
                    )
                }     
                </div>
                
            </div>
        </AppLayout>
    );
}