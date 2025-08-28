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
import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"

import { type FilterConfig, type Option } from "@/types"
import axios from 'axios'
// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.

export const columns = [
  {
    accessorKey: "id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="id" label="ID" config={v} />,
    cell: ({ row }) => {
      return <div className="text-center">{row.getValue("id")}</div>
    }
  },
  {
    accessorKey: "bank_name",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="bank_name" label="Bank Name" config={v} />,
    cell: ({ row }) => {

        return <div className="text-center">{row.getValue("bank_name")}</div>
    }
  },
  {
    accessorKey: "account_number",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="account_number" label="Account #" config={v} />,
    cell: ({ row }) => {

        return <div className="text-center">{row.getValue("account_number")}</div>
    }
  },
  {
    accessorKey: "account_name",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="account_name" label="Account Name" config={v} />,
    cell: ({ row }) => {
        return <div className="text-center">{row.getValue("account_name")}</div>
    }
  },
  {
    accessorKey: "bank_address",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="bank_address" label="Branch Address" config={v} />,
    cell: ({ row }) => {
        return <div>{row.getValue("bank_address")}</div>
    }
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