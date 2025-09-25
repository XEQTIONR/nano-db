import AppLayout from '@/layouts/app-layout';
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
import { Head, router } from '@inertiajs/react';
import { Button } from "@/components/ui/button"
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

import { Input } from '@/components/ui/input';
import { Tyre } from '@/types'
import { type BreadcrumbItem } from '@/types';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Consignments',
        href: route('consignments.index')
    },
    {
        title: 'Waste',
        href: route('waste.index'),
    },
    {
        title: 'Add New',
        href: route('waste.create'),
    },
];

const tyreDescription = ({ brand, size, pattern, lisi}: Tyre) => `${brand} ${size} ${pattern} ${lisi}`

type WasteItem = {
    containerNum : string
    bol: string
    tyreId: number
    qtyReturned: number

}

export default function Create({ groupedConsignments, tyres } : { tyres: { data: Tyre[]}}) {
    
    const [waste, setWaste] = useState<WasteItem[]>([])

    const setQty = (containerNum: string, bol: string, tyreId: number, qty: string, max: number) => {
        
        const tempQty = isNaN(parseInt(qty)) ? 0 : parseInt(qty)

        const index = waste.findIndex((item) => 
            item.containerNum == containerNum
            && item.bol == bol
            && item.tyreId == tyreId
        )

        if (index >= 0) {
            if (tempQty == 0) {
                setWaste(waste.filter((_, idx) => idx !== index))
            } else if (tempQty <= max) {
                setWaste(waste.map((itm, idx) => {
                    if (idx == index) {
                        itm.qtyReturned = tempQty
                    }
                    return itm
                }))
            }
        } else if (tempQty > 0 && tempQty <= max) {
            setWaste([...waste, {
                containerNum,
                bol,
                tyreId,
                qtyReturned: tempQty
            }])
        }
    }

    const submit = () => {
        router.post(route('waste.store'), {
            waste: waste
        })
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Waste" />
            <h1 className="text-2xl md:text-4xl font-bold pl-4 mt-6">Add waste</h1>
            <div className="w-full flex justify-center">
                <div className="w-full lg:w-3/4 xl:w-2/3 flex flex-col gap-4 p-4">
                    {
                        Object.keys(groupedConsignments).map((bol) => (
                            <Card className="overflow-x-scroll">
                                <CardHeader>
                                    <CardTitle>Bill of lading # {bol}</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div className="w-full flex flex-col gap-2">
                                        {
                                            Object.keys(groupedConsignments[bol])
                                                .map((containerNum) => (
                                                    <>
                                                        <h1>Container # {containerNum}</h1>
                                                        <Table>
                                                            <TableHeader>
                                                                <TableRow>
                                                                    <TableHead className="w-1/3 md:w-1/2">Tyre</TableHead>
                                                                    <TableHead className="text-center">Current Stock</TableHead>
                                                                    <TableHead className="text-center">Add Waste</TableHead>
                                                                    <TableHead className="text-center">Updated Stock</TableHead>
                                                                </TableRow>
                                                            </TableHeader>
                                                            <TableBody>
                                                            {
                                                                groupedConsignments[bol][containerNum].map((item) => (
                                                                    <TableRow className="hover:bg-transparent">
                                                                        <TableCell className="w-1/2">
                                                                            <span className="font-bold">({item.tyre_id})</span> { tyreDescription(tyres.data.find(({id}) => id === item.tyre_id)) }
                                                                        </TableCell>
                                                                        <TableCell className="text-center">
                                                                            {item.in_stock}
                                                                        </TableCell>
                                                                        <TableCell>
                                                                            <Input
                                                                                value={waste.find((wasteItem) => wasteItem.bol == bol && wasteItem.containerNum == containerNum && wasteItem.tyreId == item.tyre_id)?.qtyReturned ?? ""} 
                                                                                min={0} 
                                                                                max={item.in_stock} 
                                                                                onChange={({target}) => setQty(containerNum, bol, item.tyre_id, target.value, item.in_stock)} 
                                                                                className="text-center" 
                                                                                type="number" 
                                                                            />
                                                                        </TableCell>
                                                                        <TableCell className="text-center">
                                                                            {item.in_stock - (waste.find((wasteItem) => wasteItem.bol == bol && wasteItem.containerNum == containerNum && wasteItem.tyreId == item.tyre_id)?.qtyReturned ?? 0)}
                                                                        </TableCell>
                                                                    </TableRow>
                                                                ))
                                                            }
                                                            </TableBody>
                                                        </Table>
                                                    </>
                                                ))
                                        }
                                    </div>
                                </CardContent>
                            </Card>
                        ))
                    }
                    <div className="w-full flex justify-end">
                        <AlertDialog>
                            <AlertDialogTrigger asChild>
                                <Button disabled={waste.length == 0}>Submit</Button>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                                <AlertDialogHeader>
                                    <AlertDialogTitle>Confirm add waste?</AlertDialogTitle>
                                    <AlertDialogDescription>
                                        Are you sure that you want to add tyres to waste? These <span className="font-bold text-black dark:text-white">{waste.reduce((prev, curr) => prev + curr.qtyReturned, 0)}</span> tyres will be removed from the current stock.
                                    </AlertDialogDescription>
                                </AlertDialogHeader>
                                <AlertDialogFooter>
                                    <AlertDialogCancel>
                                        Cancel
                                    </AlertDialogCancel>
                                    <AlertDialogAction onClick={submit}>
                                        Confirm
                                    </AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>
                    </div>
                </div>
            </div>
        </AppLayout>
    )
}