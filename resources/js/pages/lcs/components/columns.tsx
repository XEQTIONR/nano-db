"use client"

import { LetterOfCredit } from "@/types"
import { ColumnDef } from "@tanstack/react-table"

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
        const amount = parseFloat(row.getValue("foreign_amount"))
        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: row.getValue("currency_code"), currencyDisplay: "narrowSymbol" })
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
    header: () => <div className="text-center">Create On</div>,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>
  },
]