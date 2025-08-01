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
// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.

export const columns = [
  {
    accessorKey: "lc_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="lc_num" label="LC #" config={v} />
  },
  {
    accessorKey: "date_issued",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="date_issued" label="Date Issued" config={v} />
  },
  {
    accessorKey: "date_expiry",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="date_expiry" label="Expires On" config={v} />
  },
  {
    accessorKey: "currency_code",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="currency_code" label="Currency" config={v} />
  },
  {
    accessorKey: "exchange_rate",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="exchange_rate" label="Rate" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("exchange_rate"))
        return amount.toFixed(2)
    }
  },
  {
    accessorKey: "foreign_amount",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="end" colKey="foreign_amount" label="Foreign Amount" config={v} />,
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
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="end" colKey="local_amount" label="Local Amount" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("local_amount"))
        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: "BDT", currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "total_expense",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="end" colKey="total_expense" label="Expenses" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("total_expense"))
        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: "BDT", currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" justify="center" label="Created On" config={v} />,
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