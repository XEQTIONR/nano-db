import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

import { ChevronLeft, ChevronRight, ChevronFirst, ChevronLast, LoaderCircleIcon, RefreshCcw, Search } from "lucide-react";

import { Button } from "./ui/button";

import { useEffect, useState } from "react"
import axios from 'axios';
import { Tyre } from "@/types";
import { useDebouncedCallback } from 'use-debounce';

export default function ProductsTable({ apiToken, addItem = undefined, showStock = false, all = false } : { 
    apiToken: string,
    addItem?: (item: Tyre) => void
    showStock?: boolean 
    all?: boolean
}) {


    const [items, setItems] = useState<(Tyre & {in_stock: number}) []>([])
    const [buttons, setButtons] = useState(null)
    const [error, setError] = useState(null)

    const labels = ['first', 'prev', 'next', 'last']
    const tyreRoute = route('api.tyres.index', { perPage: 15 })
    const stockRoute = route('api.stocks.index', { perPage: 15 })
    const paginate = (link?: string) => {
        const r = all ? tyreRoute : stockRoute
        axios.get(link ?? r, { headers: { 
            Authorization: 'Bearer ' + apiToken, 
            Accept: 'application/json'
        } })
            .then((res) => {
                console.log('res:', res)

                if (res.data.items)
                    setItems(res.data.items.map((d) => {
                        return {
                            ...d,
                            id: d.tyre_id ?? d.id
                        }
                    }))
                if (res.data.data)
                    setItems(res.data.data.map((d) => {
                        return {
                            ...d,
                            id: d.tyre_id ?? d.id
                        }
                    }))
                
                setButtons(res.data.links)
            })
            .catch((err) => {
                setError(err.message)
            })
    }

    const debounced = useDebouncedCallback(
        (q: string) => {

            if (q == "") {
                paginate()
            } else {
                const rt = route(all ? 'api.tyres.index' : 'api.stocks.index', {
                    filters: "*.like." + q
                })

                paginate(rt)
            }
            
        },
        1000
    )
    useEffect(() => {
        paginate()
    }, [])

    return (
        <>
        <Table>
            {/* <TableCaption>{ apiToken }</TableCaption> */}
            <TableHeader>
                <TableRow className="hover:bg-transparent">
                    <TableHead className="px-0 pb-6" colSpan={5 + (showStock ? 1 : 0)}>
                        <div className="flex items-center gap-3 border-2 p-2 rounded-lg">
                            <Search size={16} />
                            <input
                                onChange={({target}) => debounced(target.value)} 
                                placeholder="Search" 
                                className="w-full border-none outline-none" 
                                type="text" 
                            />

                        </div>
                    </TableHead>
                </TableRow>
                <TableRow>
                    <TableHead>ID</TableHead>
                    <TableHead>Brand</TableHead>
                    <TableHead>Size</TableHead>
                    <TableHead>Pattern</TableHead>
                    <TableHead>Li/Si</TableHead>
                    { showStock && <TableHead className="text-center"># available</TableHead>}
                </TableRow>
            </TableHeader>
            <TableBody>
            {
                !error && 
                
                (
                    <>
                        { items.length === 0 &&
                            <TableRow>
                                <TableCell className="text-center" colSpan={showStock ? 6 : 5}>
                                        No products found
                                        {/* <LoaderCircleIcon className="block mx-auto my-4 animate-spin" size={30} /> */}
                                </TableCell>
                            </TableRow>
                        }
                        { items.map((item) => (
                            <TableRow className="cursor-pointer" onClick={() => {
                                if (addItem) {
                                    addItem(item)
                                }
                            }}>
                                <TableCell className="font-bold">{item.id}</TableCell>
                                <TableCell>{item.brand}</TableCell>
                                <TableCell>{item.size}</TableCell>
                                <TableCell>{item.pattern}</TableCell>
                                <TableCell>{item.lisi}</TableCell>
                                { showStock && <TableCell className="text-center">{item.in_stock}</TableCell> }
                            </TableRow>
                        )) 
                        }
                    </>
                )
            }
            </TableBody>

        </Table>
        {error && <div className="w-full my-4 flex flex-col gap-4 items-center">
            {error}
            <Button onClick={paginate} variant="secondary">
                <RefreshCcw />
                Reload
            </Button>
        </div>}
        <div className="w-full flex gap-1 mt-3">
        {   
            labels.map(label => {
                
                
            switch(label) {
                case 'first':
                    return <Button 
                        onClick={() => paginate(buttons[label])}
                        className="cursor-pointer" variant="secondary"><ChevronFirst /></Button>
                case 'prev':
                    return <Button 
                        onClick={() => paginate(buttons[label])}
                        className="cursor-pointer"  variant="secondary"><ChevronLeft /></Button>
                case 'next':
                    return <Button 
                        onClick={() => {
                            console.log('paginate', buttons[label])
                            paginate(buttons[label])
                        }}
                        className="cursor-pointer"  variant="secondary"><ChevronRight /></Button>
                case 'last':
                    return <Button 
                        onClick={() => paginate(buttons[label])}
                        className="cursor-pointer"  variant="secondary"><ChevronLast /></Button>
            }
            return <Button>{ label }</Button>
        
        })
        }
        </div>
        </>
    )
}