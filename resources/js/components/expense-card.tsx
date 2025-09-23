import { 
    Table, 
    TableHeader, 
    TableHead, 
    TableBody, 
    TableRow, 
    TableCell, 
    TableFooter 
} from "@/components/ui/table"
import { 
    Card, 
    CardHeader, 
    CardTitle, 
    CardDescription, 
    CardContent
 } from "@/components/ui/card"
import { Expense } from "@/types"
import { cn, currencyFormat } from "@/lib/utils"

export default function ExpenseCard ({expenses, className = ""} : { expenses : Expense[], className?: string }) {
    return (
        <div className={cn("border px-1 overflow-x-scroll rounded-lg dark:bg-neutral-900", className)}>
            <Card className="flex flex-col  bg-neutral-50 dark:bg-neutral-900 border-none rounded-3xl">
                <CardHeader>
                    <CardTitle>Expenses</CardTitle>
                    <CardDescription>Expenses related to this consignment.</CardDescription>
                </CardHeader>
                <CardContent>
                <Table>
                    <TableHeader>
                        <TableRow className="hover:bg-transparent">
                            <TableHead className="">ID</TableHead>
                            <TableHead className="text-center">Date</TableHead>
                            <TableHead>Note</TableHead>
                            <TableHead className="text-right">Amount (TK)</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody className="text-sm">
                    { 
                        expenses.map(({id, date, note, amount_local}) => (
                            <TableRow className="hover:bg-transparent">
                                <TableCell className="font-bold">{id}</TableCell>
                                <TableCell className="text-center">
                                    {/* <Link className="font-semibold hover:underline" href={route('consignments.show', { consignment: bol})}> */}
                                        {date}
                                    {/* </Link> */}
                                </TableCell>
                                <TableCell className="text-ellipsis">
                                    {note} 
                                </TableCell>
                                <TableCell className="text-right">
                                    {currencyFormat('BDT', amount_local)}
                                </TableCell>
                            </TableRow>
                        )) 
                    }

                        
                    </TableBody>
                    <TableFooter className="bg-transparent">
                        <TableRow className="font-bold">
                            <TableCell colSpan={3}>
                                Total
                            </TableCell>
                            <TableCell className="text-right">
                                {
                                    currencyFormat(
                                        'BDT',
                                        expenses.reduce((prev, {amount_local}) => prev + amount_local, 0) 
                                    )
                                }
                            </TableCell>
                        </TableRow>
                    </TableFooter>
                </Table>
                </CardContent>
            </Card>
        </div>
    )
}