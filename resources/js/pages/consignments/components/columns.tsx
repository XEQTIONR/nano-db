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
    accessorKey: "bol",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="bol" label="Bill of lading #" config={v} />,
    cell: ({ row }) => {
      return <div className="text-center">{row.getValue("bol")}</div>
    }
  },
  {
    accessorKey: "value",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="end" colKey="value" label="Foreign Amount" config={v} />,
    cell: ({ row }) => {

        let code: string = row.original.currency_code

        if (code.toUpperCase() === "RMB") {
            code = "CNY"
        }
        const amount = parseFloat(row.getValue("value"))

        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: code, currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "exchange_rate",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="exchange_rate" label="Rate" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("exchange_rate"))
        return <div className="text-center">{amount.toFixed(2)}</div>
    }
  },
  {
    accessorKey: "tax",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="end" colKey="tax" label="Tax" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("tax"))

        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: 'BDT', currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "land_date",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="land_date" label="Land Date" config={v} />,
    cell: ({row}) => {
      return <div className="text-center">{row.getValue("land_date")}</div>
    }
  },
  {
    accessorKey: "lc_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="lc_num" label="LC #" config={v} />,
    cell: ({row}) => {
      return <div className="text-center">{row.getValue("lc_num")}</div>
    }
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="created_at" label="Created On" config={v} />,
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