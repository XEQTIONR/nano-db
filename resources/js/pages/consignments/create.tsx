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
import AppLayout from '@/layouts/app-layout';
import axios from 'axios'
import { Consignment, ContainerItem, type BreadcrumbItem } from '@/types';
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel"
import { Head } from '@inertiajs/react';

import { Badge } from '@/components/ui/badge';
import { ChevronLeft, ChevronsUpDown, ChevronRight, Plus, X, Check, TriangleAlert } from 'lucide-react';
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
import { Combobox } from '@/components/ui/combobox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label'

import { useState } from 'react';
import ProductsTable from '@/components/product-table';
import { InputCalendar } from '@/components/ui/input-calendar';
import InputError from '@/components/input-error';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"
import {
  Collapsible,
  CollapsibleContent,
  CollapsibleTrigger,
} from "@/components/ui/collapsible"
import { Separator } from '@/components/ui/separator';


interface ContainerItemErrors {
    qty?: string;
    unit_price?: string;
    total_tax?: string;
    total_weight?: string;
}

type OptionalContainerItemErrors = ContainerItemErrors | undefined

interface ContainerErrors {
    container_num: string
    isEmptyError?: string
    errors: OptionalContainerItemErrors[]
}

import { router } from '@inertiajs/react';

export default function Create({apiToken} : {apiToken: string}) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Consignments',
            href: route('consignments.index'),
        },
        {
            title: 'Create New',
            href: route('consignments.create'),
        },
    ];

    const [show, setShow] = useState(true);
    const [dir, setDir] = useState(true);
    const [current, setCurrent] = useState(0)
    const steps = [
        'Add consignment details',
        'Add Containers',
        'Confirm'
    ]

    const [isOpen, setIsOpen] = useState(false)
    const [noContainersError, setNoContainersError] = useState<string | undefined>(undefined)
    
    const fn = (prev = false) => {
        console.log('fn')
        setDir(prev)
        setShow(false)
        //setCurrent((prev ? (current - 1) : (current + 1)) % steps.length)
        
        if (prev) {
            if ((current - 1) < 0)
                setCurrent(steps.length - 1)
            else
                setCurrent((current - 1))
        } else { //
            switch(current) {
                case 0:
                    if (validateConsignment() == 0) {
                        setCurrent(1)
                    }
                break

                case 1:
                    if (validateContainers() == 0) {
                        setCurrent(2)
                    }
                break
            }
        }
        
        setTimeout(() => setShow(true), 1)
    }

    const [consignment, setConsignment] = useState<Consignment>({
        lc: "",
        bol: "",
        value: 0,
        exchange_rate: 0,
        tax: 0,
    })

    const [containers, setContainers] = useState<string[]>([])

    const [containerNum, setContainerNum] = useState<string>("")

    const [items, setItems] = useState<ContainerItem[]>([])
    
    const [consignmentErrors, setConsignmentErrors] = useState<{
        lc?: string
        bol?: string
        value?: string
        exchange_rate?: string
        tax?: string
        land_date?: string
    }>({})

    const [containerErrors, setContainerErrors] = useState<ContainerErrors[]>([])

    const validateConsignment = (): number => {
        let count = 0
        
        let errors = {...consignmentErrors}
        if (!(consignment.lc.length > 0)) {
            errors = {...errors, lc: "An existing letter of credit is required"}
            count++
        }

        if (!(consignment.bol.length > 0)) {
            errors ={...errors, bol: "The bill of lading number is required"}
            count++
        }

        if (!(consignment.value > 0)) {
            errors ={...errors, value: "The consignment value must be greater than 0"}
            count++
        }

        if (!(consignment.exchange_rate > 0)) {
            errors ={...errors, exchange_rate: "The exchange rate must be greater than 0"}
            count++
        }

        if (!(consignment.tax >= 0)) {
            errors ={...errors, tax: "The tax value must be greater than or equal to 0"}
            count++
        }

        if (!(consignment.land_date instanceof Date)) {
            errors ={...errors, land_date: "The land date is required"}
            count++
        }
        setConsignmentErrors(errors)
        return count
    }

    const validateContainers = () => {
        const errs: ContainerErrors[] = []
        let count = 0

        if (containers.length  == 0) {
            setNoContainersError("Add and fill atleast one container")
            setContainerErrors([])
            return 1
        } else {
            setNoContainersError(undefined)
        }
        containers.forEach(container => {
            const cErr: ContainerErrors = { 
                container_num: container, 
                errors: items
                    .filter(({container_num}) => container_num == container)
                    .map((item) => {
                        const e : OptionalContainerItemErrors = {}
                        if (typeof item.qty != 'number' || item.qty < 1) {
                            e.qty = "Quantity must be a number greater than 0"
                            count++
                        }

                        if (typeof item.unit_price != 'number' || item.unit_price <= 0) {
                            e.unit_price = "Price must be greater than 0"
                            count++
                        }

                        if (typeof item.total_tax != 'number' || item.total_tax < 0) {
                            e.total_tax = "Price must be greater than or equal to 0"
                            count++
                        }

                        if (typeof item.total_weight != 'number' || item.total_weight < 0) {
                            e.unit_price = "Price must be greater than or equal to 0"
                            count++
                        }

                        if (Object.keys(e).length == 0) {
                            return undefined
                        }

                        return e
                    })
            
            }

            if (items.filter(({container_num}) => container_num == container).length == 0) {
                cErr.isEmptyError = "Container # " + container + " is empty"
                count++
            }

            errs.push(cErr) 
        })

        setContainerErrors(errs)
        return count
    }

    const deleteContainer = (containerNum : string) => {
        setContainers(containers.filter((container) => container !== containerNum ))
        setItems(items.filter(({container_num}) => container_num !== containerNum))
        setContainerErrors(containerErrors.filter(({container_num}) => container_num !== containerNum))
    }

    const save = () => {
        router.post(route('consignments.store'), {
            consignment, containers, items
        })
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Consignments" />
            <div className="flex flex-col h-full md:items-start rounded-xl p-4">
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
                        <Button className="cursor-pointer" onClick={() => {
                            fn(false)
                        }} variant="secondary">
                            <ChevronRight />
                        </Button>
                    </div>
                    
                </div>
                <div className="w-full pt-5 flex flex-wrap xl:flex-nowrap gap-4 items-stretch xl:items-start">
                {
                    current == 0 && (
                        <Card 
                            className={cn(
                                "w-full lg:w-1/2 transition-all relative",
                                show ? "opacity-100" : "opacity-0",
                                !dir && (show ? "-right-0" : "-right-16"), 
                                dir && (show ? "-left-0" : "-left-16"), )}
                        >
                            <CardHeader>
                                <CardTitle>Consignment information</CardTitle>
                                <CardDescription>
                                    Enter details about your new consignment
                                </CardDescription>
                                <CardAction>
                                    <Button onClick={() => fn()} variant="secondary">
                                        Next Step
                                        <ChevronRight />
                                    </Button>
                                </CardAction>
                            </CardHeader>
                            <CardContent>
                                <form className="grid gap-6">
                                    <div className="flex flex-col gap-6">
                                        <div className="grid gap-2">
                                            <Label 
                                                // className={cn(errors?.lc_num && "text-red-400")} 
                                                htmlFor="lc_num"
                                            >
                                                Select Letter of credit
                                            </Label>
                                            <Combobox
                                                className={cn(
                                                    consignmentErrors?.lc && "border-red-400 dark:border-red-400",
                                                )}
                                                value={consignment?.lc}
                                                getOptions={async(search: string) => {
                                                    const endpoint = route('api.lcs.index', {
                                                        filters: "lc_num.like." + search
                                                    })
                                                    const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })
                                                    console.log('response:', response)
                                                    return response.data.items.map(({lc_num} : {lc_num: string}) => {
                                                        return {value: lc_num, label: lc_num}
                                                    })
                                                }}
                                                onSelect={(val) => {
                                                    const e = {...consignmentErrors}
                                                    delete e.lc
                                                    setConsignmentErrors(e)
                                                    setConsignment({
                                                        ...consignment,
                                                        lc: val.toString()
                                                    })
                                                }} 
                                            />
                                            {consignmentErrors?.lc && <InputError message={consignmentErrors.lc} />}
                                        </div>
                                    </div>
                                    <div className="flex gap-6">
                                        <div className="flex w-full flex-col gap-2">
                                            <Label htmlFor="exchange_rate">Bill of Lading Number</Label>
                                            <Input
                                                className={cn(
                                                    consignmentErrors?.bol && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                                )}
                                                onChange={({target}) => {
                                                    const e = {...consignmentErrors}
                                                    delete e.bol
                                                    setConsignmentErrors(e)
                                                    setConsignment({ ...consignment, bol: target.value})
                                                }}
                                                value={consignment.bol}
                                                placeholder="Bill of Lading #"
                                                id="bol"
                                                required
                                            />
                                            {consignmentErrors?.bol && <InputError message={consignmentErrors.bol} />}
                                        </div>
                                    </div>
                                    <div className="flex justify-start flex-wrap">
                                        <div className="flex flex-col gap-2 w-full md:w-1/2 md:pr-3 pb-6">
                                            <Label htmlFor="exchange_rate">Rate</Label>
                                            <Input
                                                className={cn(
                                                    consignmentErrors?.exchange_rate && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                                    "text-right"
                                                )}
                                                onChange={({target}) => {
                                                    const e = {...consignmentErrors}
                                                    delete e.exchange_rate
                                                    setConsignmentErrors(e)
                                                    setConsignment({ ...consignment, exchange_rate: parseFloat(target.value) ?? 0})
                                                }}
                                                type="number"
                                                value={consignment.exchange_rate}
                                                placeholder="0.00"
                                                id="exchange_rate"
                                                required
                                            />
                                            {consignmentErrors?.exchange_rate && <InputError message={consignmentErrors.exchange_rate} />}
                                        </div>
                                        <div className="flex flex-col gap-2 w-full md:w-1/2 md:pl-3 pb-6">
                                            <Label htmlFor="exchange_rate">Value</Label>
                                            <Input
                                                className={cn(
                                                    consignmentErrors?.value && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                                    "text-right"
                                                )}
                                                onChange={({target}) => {
                                                    const e = {...consignmentErrors}
                                                    delete e.value
                                                    setConsignmentErrors(e)
                                                    setConsignment({ ...consignment, value: parseFloat(target.value) ?? 0})
                                                }}
                                                type="number"
                                                value={consignment.value}
                                                placeholder="0.00"
                                                id="value"
                                                required
                                            />
                                            {consignmentErrors?.value && <InputError message={consignmentErrors.value} />}
                                        </div>
                                        <div className="flex flex-col gap-2 w-full md:w-1/2 md:pr-3 pb-6">
                                            <Label htmlFor="exchange_rate">Total Tax Paid</Label>
                                            <Input
                                                className={cn(
                                                    // errors?.exchange_rate && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                                    "text-right"
                                                )}
                                                onChange={({target}) => {
                                                    const e = {...consignmentErrors}
                                                    delete e.tax
                                                    setConsignmentErrors(e)
                                                    setConsignment({ ...consignment, tax: parseFloat(target.value) ?? 0})
                                                }}
                                                type="number"
                                                value={consignment.tax}
                                                placeholder="0.00"
                                                id="tax"
                                                required
                                            />
                                            {consignmentErrors?.tax && <InputError message={consignmentErrors.tax} />}
                                        </div>
                                        <div className="flex flex-col gap-2 w-full md:w-1/2 md:pl-3 pb-6">
                                            <Label htmlFor="land_date">Issue Date</Label>
                                            <InputCalendar
                                                className={cn(consignmentErrors?.land_date && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                                                // date={formData.date_issued}
                                                onChange={(date) => {
                                                    const e = {...consignmentErrors}
                                                    delete e.land_date
                                                    setConsignmentErrors(e)
                                                    setConsignment({...consignment, land_date: date})
                                                }}
                                                date={consignment?.land_date}
                                                id="land_date"
                                                placeholder="Date landed"
                                            />
                                            {consignmentErrors?.land_date && <InputError message={consignmentErrors.land_date} />}
                                        </div>
                                    </div>
                                </form>
                            </CardContent>
                        </Card>
                    )
                }
                {
                    current == 1 
                    && (<div className="w-full xl:w-2/3 flex flex-col gap-4">
                        <Card 
                                className={cn(
                                    "w-full transition-all relative",
                                    show ? "opacity-100" : "opacity-0",
                                    !dir && (show ? "-right-0" : "-right-16"), 
                                    dir && (show ? "-left-0" : "-left-16"), 
                                )}
                        >
                            <CardHeader>
                                <CardTitle>Container Information</CardTitle>
                                <CardDescription>
                                    Add containers to consignment
                                </CardDescription>
                                <CardAction>
                                    <Button onClick={() => fn()} variant="secondary">
                                        Next Step
                                        <ChevronRight />
                                    </Button>
                                </CardAction>
                            </CardHeader>
                            <CardContent>
                                <div className="grid gap-4">
                                    <Label>Container number</Label>
                                    <form onSubmit={(e) => {
                                        e.preventDefault()
                                        if (containerNum.length) {
                                            setContainers([containerNum, ...containers])
                                            setContainerNum("")
                                            setNoContainersError(undefined)
                                        }
                                    }} className="w-full flex gap-4">
                                        <Input
                                            className={cn(noContainersError && "border-red-400")} 
                                            value={containerNum} 
                                            onChange={({target}) => setContainerNum(target.value)} 
                                            placeholder="Type Container # and click + button to add a container" 
                                        />
                                        <Button type="submit" size="icon" variant="outline"><Plus /></Button>
                                    </form>
                                    <Separator className="mt-4" />
                                    <Collapsible
                                        open={isOpen}
                                        onOpenChange={setIsOpen}
                                        className="flex flex-col gap-2"
                                        >
                                        <div className="flex items-center justify-between gap-4 px-1">
                                            <h4 className="text-sm font-semibold">
                                            {containers.length} containers added
                                            </h4>
                                            <CollapsibleTrigger asChild>
                                            <Button variant="ghost" size="icon" className="size-8">
                                                <ChevronsUpDown />
                                            </Button>
                                            </CollapsibleTrigger>
                                        </div>
                                        {
                                            containers.length > 0 && (<>
                                                <div 
                                                    onClick={() => setIsOpen(false)} 
                                                    className={cn(
                                                        "rounded-md border px-4 py-2 font-mono text-sm flex items-center justify-between",
                                                    )}
                                                >
                                                    <span># {containers[0]}</span>
                                                    <div className="flex gap-4 items-center">
                                                        <Check className="mt-[0.5px]" size={16} />
                                                        
                                                        { containerErrors.find(({container_num}) => container_num == containers[0])?.isEmptyError
                                                            && <TriangleAlert className="stroke-red-400 ml-2" size={16} />
                                                        }
                                                        <Button className="cursor-pointer" onClick={(e) => {
                                                            e.stopPropagation()
                                                            deleteContainer(containers[0])
                                                        }} size="icon" variant="ghost">
                                                            <X />
                                                        </Button>
                                                        
                                                    </div>
                                                </div>
                                                {
                                                    containers.length > 1 && (
                                                        <CollapsibleContent className="flex flex-col gap-2">
                                                            {
                                                                containers.slice(1).map((item) => (
                                                                    <div
                                                                        onClick={() => {
                                                                            const selected = item
                                                                            const not = containers.filter((val) => item !== val)

                                                                            setContainers([selected, ...not])
                                                                            setIsOpen(false)
                                                                        }} 
                                                                        className="rounded-md border px-4 py-2 font-mono text-sm  flex items-center justify-between"
                                                                    >
                                                                        <span># {item}</span>
                                                                        <div className="flex gap-4 items-center">
                                                                            { containerErrors.find(({container_num}) => container_num == item)?.isEmptyError
                                                                                && <TriangleAlert className="stroke-red-400" size={16} />}
                                                                            <Button className="cursor-pointer" onClick={(e) => {
                                                                                e.stopPropagation()
                                                                                deleteContainer(item)
                                                                            }} size="icon" variant="ghost">
                                                                                <X />
                                                                            </Button>
                                                                        </div>
                                                                    </div>
                                                                ))
                                                            }
                                                        </CollapsibleContent>
                                                    )
                                                }
                                                
                                            </>)
                                        }
                                        
                                    </Collapsible>
                                    { noContainersError && <InputError message={noContainersError} />}
                                    { 
                                        containerErrors.find(({ isEmptyError }) => isEmptyError !== undefined) &&
                                        <InputError message={containerErrors.find(({ isEmptyError }) => isEmptyError !== undefined)?.isEmptyError} />
                                    }
                                </div>
                            </CardContent>
                        </Card>

                        {
                            (containers.length > 0) && (
                                <Card>
                                    <CardHeader>
                                        <CardTitle>
                                            Container # {containers[0]}
                                        </CardTitle>
                                        <CardDescription>
                                            Add products to container
                                        </CardDescription>
                                    </CardHeader>
                                    <CardContent className="overflow-x-scroll">
                                        <Table>
                                            <TableHeader>
                                                <TableRow>
                                                    <TableHead>#</TableHead>
                                                    <TableHead>Item</TableHead>
                                                    <TableHead className="min-w-28">Qty</TableHead>
                                                    <TableHead className="min-w-28">Price</TableHead>
                                                    <TableHead className="min-w-28">Total Tax</TableHead>
                                                    <TableHead className="min-w-28">Total Weight</TableHead>
                                                    <TableHead></TableHead>
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody>
                                                {
                                                    items
                                                        .filter(({container_num}) => container_num == containers[0])
                                                        .map((item, index) => (
                                                        <TableRow className="hover:bg-transparent">
                                                            <TableCell>{index+1}</TableCell>
                                                            <TableCell>({item.id}) {item.brand} {item.size} {item.pattern} {item.lisi}</TableCell>
                                                            <TableCell>
                                                                <Input
                                                                    type="number"
                                                                    className={cn(
                                                                        containerErrors.find((val) => val.container_num == containers[0])?.errors[index]?.qty &&
                                                                        "border-red-400" 
                                                                    )}
                                                                    value={item.qty} 
                                                                    onChange={({target}) => {
                                                                        const i = items.findIndex(elem => elem === item)
                                                                        const vals = [...items]
                                                                        vals[i].qty =  isNaN(parseInt(target.value)) ? 0 : parseInt(target.value)
                                                                        setItems(vals)
                                                                    }}
                                                                />

                                                            </TableCell>
                                                            <TableCell>
                                                                <Input
                                                                    type="number"
                                                                    className={cn(
                                                                        containerErrors.find((val) => val.container_num == containers[0])?.errors[index]?.unit_price &&
                                                                        "border-red-400" 
                                                                    )} 
                                                                    value={item.unit_price}
                                                                    onChange={({target}) => {
                                                                        const i = items.findIndex(elem => elem === item)
                                                                        const vals = [...items]
                                                                        vals[i].unit_price =  isNaN(parseFloat(target.value)) ? 0 : parseFloat(target.value)
                                                                        setItems(vals)
                                                                    }} 
                                                                />
                                                            </TableCell>
                                                            <TableCell>
                                                                <Input
                                                                    type="number"
                                                                    className={cn(
                                                                        containerErrors.find((val) => val.container_num == containers[0])?.errors[index]?.total_tax &&
                                                                        "border-red-400" 
                                                                    )} 
                                                                    value={item.total_tax} 
                                                                    onChange={({target}) => {
                                                                        const i = items.findIndex(elem => elem === item)
                                                                        const vals = [...items]
                                                                        vals[i].total_tax =  isNaN(parseFloat(target.value)) ? 0 : parseFloat(target.value)
                                                                        setItems(vals)
                                                                    }}
                                                                />
                                                            </TableCell>
                                                            <TableCell>
                                                                <Input
                                                                    type="number"
                                                                    className={cn(
                                                                        containerErrors.find((val) => val.container_num == containers[0])?.errors[index]?.total_weight &&
                                                                        "border-red-400" 
                                                                    )} 
                                                                    value={item.total_weight} 
                                                                    onChange={({target}) => {
                                                                        const i = items.findIndex(elem => elem === item)
                                                                        const vals = [...items]
                                                                        vals[i].total_weight =  isNaN(parseFloat(target.value)) ? 0 : parseFloat(target.value)
                                                                        setItems(vals)
                                                                    }}
                                                                />
                                                            </TableCell>
                                                            <TableCell>
                                                                <Button
                                                                    className="cursor-pointer"
                                                                    size="icon"
                                                                    variant="ghost"
                                                                    onClick={() => setItems(items.filter((i) => i !== item))}
                                                                >
                                                                    <X />
                                                                </Button>
                                                            </TableCell>
                                                        </TableRow>
                                                    ))
                                                }
                                            </TableBody>
                                        </Table>
                                    </CardContent>
                                </Card>
                            )
                        }
                    </div>)
                }
                {
                    current == 2 
                    && (<div className="w-full flex flex-col gap-4">
                        <Card className={cn(
                            "w-full transition-all relative flex shrink grow-0",
                            show ? "opacity-100" : "opacity-0",
                            !dir && (show ? "-right-0" : "-right-16"), 
                            dir && (show ? "-left-0" : "-left-16"), 
                        )}>
                            <CardHeader>
                                <CardTitle>Confirm</CardTitle>
                                <CardDescription>Confirm new consignment information</CardDescription>
                                <CardAction>
                                    <AlertDialog>
                                        <AlertDialogTrigger asChild>
                                            <Button>
                                                Create Consignment
                                            </Button>
                                        </AlertDialogTrigger>
                                        <AlertDialogContent>
                                            <AlertDialogHeader>
                                                <AlertDialogTitle>Create new consignment?</AlertDialogTitle>
                                                <AlertDialogDescription>
                                                    Are you sure you want to create this consignment?
                                                </AlertDialogDescription>
                                            </AlertDialogHeader>
                                            <AlertDialogFooter>
                                                <AlertDialogCancel>
                                                    Cancel
                                                </AlertDialogCancel>
                                                <AlertDialogAction onClick={save}>
                                                    Confirm
                                                </AlertDialogAction>
                                            </AlertDialogFooter>
                                        </AlertDialogContent>
                                    </AlertDialog>
                                </CardAction>
                            </CardHeader>
                            <CardContent className="flex">
                                <div className="w-full flex flex-col gap-6">
                                    <div className="flex w-full">
                                        <div className="flex flex-col gap-2 w-1/2 pr-6">
                                            <Label>Letter of credit #</Label>
                                            <span className="font-mono font-light text-sm">{consignment.lc}</span>
                                        </div>
                                        <div className="flex flex-col gap-2 w-1/2">
                                            <Label>Bill of lading #</Label>
                                            <span className="font-mono font-light text-sm">{consignment.bol}</span>
                                        </div>
                                    </div>
                                    <div className="flex">
                                        <div className="flex flex-col gap-2 w-1/2 pr-6">
                                            <Label>Exchange rate</Label>
                                            <span className="font-mono font-light text-sm">{consignment.exchange_rate.toFixed(2)}</span>
                                        </div>
                                        <div className="flex flex-col gap-2 w-1/2 pr-6">
                                            <Label>Total value</Label>
                                            <span className="font-mono font-light text-sm">{consignment.value.toFixed(2)}</span>
                                        </div>
                                    </div>
                                    <div className="flex">
                                        <div className="flex flex-col w-1/2 gap-2">
                                            <Label>Total tax paid</Label>
                                            <span className="font-mono font-light text-sm">{consignment.tax}</span>
                                        </div>
                                        <div className="flex flex-col gap-2 w-1/2 pr-6">
                                            <Label>Total Value (taka)</Label>
                                            <span className="font-mono font-light text-sm">{(consignment.exchange_rate * consignment.value).toFixed(2)}</span>
                                        </div>
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <Label>Landed on</Label>
                                        <span className="font-mono font-light text-sm">{consignment.land_date?.toDateString()}</span>
                                    </div>
                                </div>
                            </CardContent>   
                        </Card>
                        <Card className="w-full">
                            <CardHeader>
                                <CardTitle>Containers</CardTitle>
                                <CardDescription>Confirm container details</CardDescription>
                            </CardHeader>
                            <CardContent className=" flex justify-center">
                                    <Carousel className="w-[90%] min-h-80 flex justify-center items-center">
                                        <CarouselContent className="flex items-stretch">
                                            { containers.map((container)=> (
                                            <CarouselItem className=""  key={container}>
                                                <Card className="w-full">
                                                    <CardHeader>
                                                        <CardTitle>Container <span className="font-mono">#{container}</span></CardTitle>
                                                    </CardHeader>
                                                    <CardContent className="flex items-center justify-center p-6">
                                                        <Table>
                                                            <TableHeader>
                                                                <TableRow>
                                                                    <TableHead className="font-mono font-bold">#</TableHead>
                                                                    <TableHead className="font-mono font-bold max-w-[30%]">Item</TableHead>
                                                                    <TableHead className="font-mono font-bold">Qty</TableHead>
                                                                    <TableHead className="font-mono font-bold">Price</TableHead>
                                                                    <TableHead className="font-mono font-bold hidden lg:table-cell">Subtotal</TableHead>
                                                                    <TableHead className="font-mono font-bold hidden lg:table-cell">Tax</TableHead>
                                                                    <TableHead className="font-mono font-bold hidden lg:table-cell">Weight</TableHead>
                                                                </TableRow>
                                                            </TableHeader>
                                                            <TableBody>

                                                                {
                                                                    items.filter(({container_num}) => container_num == container)
                                                                        .map(({
                                                                            id, brand, size, pattern, lisi, qty, unit_price, total_tax, total_weight
                                                                        }, idx) =>
                                                                    (<TableRow>
                                                                        <TableCell className="text-center font-mono">{idx + 1}</TableCell>
                                                                        <TableCell className="font-mono max-w-[30%]"> ({id}) {brand} <span className="hidden md:inline">{size} {pattern} {lisi}</span></TableCell>
                                                                        <TableCell className="text-center font-mono">{qty}</TableCell>
                                                                        <TableCell className="text-right font-mono">{unit_price.toFixed(2)}</TableCell>
                                                                        <TableCell className="text-right font-mono hidden lg:table-cell">{(qty *unit_price).toFixed(2)}</TableCell>
                                                                        <TableCell className="text-right font-mono hidden lg:table-cell">{total_tax.toFixed(2)}</TableCell>
                                                                        <TableCell className="text-right font-mono hidden lg:table-cell">{total_weight.toFixed(2)}</TableCell>
                                                                    </TableRow>))
                                                                }
                                                                <TableRow>
                                                                    <TableCell></TableCell>
                                                                    <TableCell className="font-mono font-bold max-w-[30%]">Total</TableCell>
                                                                    <TableCell className="text-center font-mono font-bold">{
                                                                            items
                                                                                .filter(({container_num}) => container_num == container)
                                                                                .reduce((prev, cur) => prev + cur.qty, 0)
                                                                    }</TableCell>
                                                                    <TableCell></TableCell>
                                                                    <TableCell className="text-right font-mono font-bold hidden lg:table-cell">{
                                                                            items
                                                                                .filter(({container_num}) => container_num == container)
                                                                                .reduce((prev, cur) => prev + (cur.qty * cur.unit_price), 0)
                                                                                .toFixed(2)
                                                                    }</TableCell>
                                                                    <TableCell className="text-right font-mono font-bold hidden lg:table-cell">{
                                                                            items
                                                                                .filter(({container_num}) => container_num == container)
                                                                                .reduce((prev, cur) => prev + cur.total_tax, 0)
                                                                                .toFixed(2)
                                                                    }</TableCell>
                                                                    <TableCell className="text-right font-mono font-bold hidden lg:table-cell">{
                                                                            items
                                                                                .filter(({container_num}) => container_num == container)
                                                                                .reduce((prev, cur) => prev + cur.total_weight, 0)
                                                                                .toFixed(2)
                                                                    }</TableCell>
                                                                </TableRow>
                                                            </TableBody>
                                                        </Table>
                                                    </CardContent>
                                                </Card>
                                            </CarouselItem>
                                            ))}
                                        </CarouselContent>
                                        <CarouselPrevious />
                                        <CarouselNext />
                                    </Carousel>
                                </CardContent>
                        </Card>
                    </div>)
                }
                {
                    current == 1 && (
                        <Card className="w-full xl:w-1/3">
                            <CardHeader>
                                <CardTitle>Product Catalog</CardTitle>
                                <CardDescription>
                                    All products
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="overflow-x-scroll">
                                <ProductsTable
                                    all={true} 
                                    apiToken={apiToken}
                                    addItem={(item) => {
                                        if(containers.length > 0)
                                        setItems([
                                            ...items,
                                            {
                                                ...item, 
                                                qty: 0, 
                                                unit_price: 0, 
                                                total_tax: 0, 
                                                total_weight: 0,
                                                container_num: containers[0],
                                                bol: consignment.bol
                                            }
                                        ])
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