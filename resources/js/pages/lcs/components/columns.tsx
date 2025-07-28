"use client"

import { LetterOfCredit } from "@/types"
import { ColumnDef } from "@tanstack/react-table"
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

export const columns: ColumnDef<LetterOfCredit>[] = [
  {
    accessorKey: "lc_num",
    header: "LC #",
  },
  {
    accessorKey: "date_issued",
    header: "Date Issued",
  },
  {
    accessorKey: "date_expiry",
    header: "Expires On",
  },
  {
    accessorKey: "currency_code",
    header: "Currency",
  },
  {
    accessorKey: "exchange_rate",
    header: "Rate",
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("exchange_rate"))
        return amount.toFixed(2)
    }
  },
  {
    accessorKey: "foreign_amount",
    header: () => <div className="text-right">Foreign Amount</div>,
    cell: ({ row }) => {
        let code: string = row.getValue("currency_code")

        if (code.toUpperCase() === "RMB") {
          code = "CNY"
        }
        const amount = parseFloat(row.getValue("foreign_amount"))
        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: code, currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "local_amount",
    header: () => <div className="text-right">Local Amount</div>,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("local_amount"))
        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: "BDT", currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "total_expense",
    header: () => <div className="text-right">Expenses</div>,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("local_amount"))
        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: "BDT", currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "created_at",
    header: () => <div className="text-center">Created On</div>,
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