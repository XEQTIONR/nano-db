import AppLayout from '@/layouts/app-layout';
import { InvoiceItem, Tyre, type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';

import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { useEffect, useState } from 'react';
import { Combobox } from '@/components/ui/combobox';
import axios from 'axios';
import { Textarea } from '@/components/ui/textarea';

export default function Create({apiToken, types} : {apiToken: string, types: object}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Expenses',
            href: '/expenses',
        },
        {
            title: 'Create New',
            href: '/expenses/create',
        },
    ];

    const [amount, setAmount] = useState('0.00')
    const [floatAmount, setFloatAmount] = useState(0)
    const [type, setType] = useState<undefined| string>(undefined)
    const [value, setValue] = useState<string | undefined>(undefined)

    const isRelation = (val: string) => val.startsWith('App\\Models\\') 

    const getApiRoute = (val: string, input: string) => {
        console.log('val: ', val)
        const name = val.split("App\\Models\\")[1]
        console.log('name: ', name)
        switch(name) {
            case "BankAccount":
                return route('api.bank-accounts.index', { filters: `id.like.${input}` });
            case "Container":
                return route('api.containers.index', { filters: `Container_num.like.${input}` });
            case "Consignment":
                return route('api.consignments.index', { filters: `BOL.like.${input}` });
            case "LetterOfCredit":
                return route('api.lcs.index', { filters: `lc_num.like.${input}` });
            case "Order":
            default:
                return route('api.orders.index', { filters: `Order_num.like.${input}` });

        }
    }

    const getKey = (val: string) => {
        const name = val.split("App\\Models\\")[1]
        console.log('name: ', name)
        switch(name) {
            case "BankAccount":
                return 'id'
            case "Container":
                return 'container_num';
            case "Consignment":
                return 'bol';
            case "LetterOfCredit":
                return 'lc_num'
            case "Order":
            default:
                return 'order_num';
        }
    }
    useEffect(() => {
        switch(type) {
            case "daily":
                console.log('dd');
                break
            case "monthly":
                console.log('dd monthly');
                break
            case "yearly":
                console.log('dd yearly');
                break
            default:
                console.log('default', type)
        }
    }, [type])

    // const submit = (lcData: LetterOfCredit, items: InvoiceItem[]) => {
    //     router.post(route('lcs.store'), {
    //         lc: {
    //             ...lcData,
    //             date_issued: lcData.date_issued?.toISOString().split("T")[0],
    //             date_expiry: lcData.date_expiry?.toISOString().split("T")[0],
    //         },
    //         items,
    //     })
    // }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="New expense" />
            <div className="flex justify-center h-full items-start p-4">
                <Card className="w-full lg:w-1/2 xl:w-1/3">
                    <CardHeader>
                        <CardTitle>New Expense</CardTitle>
                        <CardDescription>Add details</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-6">
                            <div className="grid gap-4">
                                <Label>Expense type</Label>
                                <Select value={type} onValueChange={(value) => {
                                    setType(value)
                                    if (isRelation(value)) {
                                        console.log('isRelation')
                                    }
                                }}>
                                    <SelectTrigger className="w-full">
                                        <SelectValue placeholder="Select expense type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {
                                            Object.keys(types).map((key) => (
                                                <SelectItem value={key}>{types[key]}</SelectItem>
                                            ))
                                        }
                                    </SelectContent>
                                    
                                </Select>
                            </div>
                            {type && isRelation(type) && <div className="grid gap-4">
                                {/* <Label>{types[type] ?? "Select"}</Label> */}
                                <Combobox
                                    key={type}
                                    placeholder={"Select " + types[type] + " ..."} 
                                    value={value}
                                    getOptions={async (search: string) => {
                                        if (type && isRelation(type)) {
                                            console.log('b4 axios type', type)
                                            const  res = await axios.get(getApiRoute(type, search), { headers: { Authorization: 'Bearer ' + apiToken } })
                                            
                                            console.log('res:', res.data.items)

                                            const key = getKey(type)

                                            return res.data.items.map((item) => {
                                                return {
                                                    value: item[key],
                                                    label: item.toString
                                                }
                                            })
                                        }

                                        return []
                                    }}
                                    onSelect={(val: string) => {
                                        setValue(val)
                                    }}
                                />
                            </div>}
                            <div className="grid gap-4">
                                <Label>Amount</Label>
                                <Input value={amount} onChange={(e) => {
                                    setAmount(e.target.value)
                                    setFloatAmount(parseFloat(e.target.value))
                                }} type="number" className="md:text-4xl text-center font-bold h-16" />
                            </div>
                            <div className="grid gap-4">
                                <Label>Note</Label>
                                <Textarea className="min-h-36" />
                            </div>

                            <Button>Submit</Button>
                        </div>
                    </CardContent>
                </Card>
                
            </div>
        </AppLayout>
    );
}