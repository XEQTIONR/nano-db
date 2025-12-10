import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

import { useEffect, useState } from "react"
import axios from 'axios';

export default function StockTable({ apiToken } : { apiToken: string }) {


    const [items, setItems] = useState([])

    useEffect(() => {
        axios.get(route('api.stocks.index'), { headers: { Authorization: 'Bearer ' + apiToken } })
            .then((res) => {
                setItems(res.data.items)
            })
            .catch((err) => console.log('err:', err))
    }, [apiToken])

    return (
        <Table>
            {/* <TableCaption></TableCaption> */}
            <TableHeader>
                <TableRow>
                    <TableHead className="w-[100px]">ID</TableHead>
                    <TableHead>Brand</TableHead>
                    <TableHead>Size</TableHead>
                    <TableHead>Pattern</TableHead>
                    <TableHead>Li/Si</TableHead>
                    <TableHead>In Stock</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
            { items.map(({tyre_id, brand, size, pattern, lisi, in_stock}) => (
                <TableRow>
                    <TableCell className="font-bold">{tyre_id}</TableCell>
                    <TableCell>{brand}</TableCell>
                    <TableCell>{size}</TableCell>
                    <TableCell>{pattern}</TableCell>
                    <TableCell>{lisi}</TableCell>
                    <TableCell>{in_stock}</TableCell>
                </TableRow>
            )) }
            </TableBody>
        </Table>
    )
}