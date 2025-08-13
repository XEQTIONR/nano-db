import { 
  DropdownMenu, 
  DropdownMenuTrigger, 
  DropdownMenuLabel, 
  DropdownMenuItem, 
  DropdownMenuContent,
  DropdownMenuSeparator 
} from "@/components/ui/dropdown-menu"
import { MoreHorizontal } from "lucide-react"
import { Button } from "@/components/ui/button"
// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.
import { currencyFormat } from "@/lib/utils"
import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"
import { FilterConfig } from "@/types"
import axios from 'axios'
import { type Option } from "@/types"

export const columns = [
  {
    accessorKey: "transaction_id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="transaction_id" label="Transaction ID" config={v} />
  },
  {
    accessorKey: "order_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_num" label="Order" config={v} />
  },
  {
    accessorKey: "type",
    header: (v) => <DataTableCustomColumnHeader colKey="type" label="Type" config={v} />
  },
  {
    accessorKey: "accountDesc",
    header: (v) => <DataTableCustomColumnHeader colKey="account" justify="center" label="Account"  config={v}/>,
    cell: ({ row }) => <div className="text-center">{ row.getValue('accountDesc') ?? 'Unknown' }</div>
  },
  {
    accessorKey: "amount",
    header: (v) => {
      return <DataTableCustomColumnHeader justify="end" colKey="amount" label="Amount"  config={v}/>
    },
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("amount"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  
  {
    accessorKey: "created_at",
    header: (v) => <DataTableCustomColumnHeader colKey="created_at" justify="center" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>
  },
  {
    id: "actions",
    enableHiding: false,
    cell: ({ row }) => {
      return (
        <DropdownMenu>
          <DropdownMenuTrigger asChild>
            <Button variant="ghost" className="h-8 w-8 p-0">
              <span className="sr-only">Open menu</span>
              <MoreHorizontal />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem>View</DropdownMenuItem>
            <DropdownMenuItem>Add Proforma Invoice</DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      )
    },
  }
]