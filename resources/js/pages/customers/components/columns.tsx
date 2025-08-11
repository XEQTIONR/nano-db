import { Button } from "@/components/ui/button"
import { currencyFormat } from "@/lib/utils"
import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"
import { 
  DropdownMenu, 
  DropdownMenuTrigger, 
  DropdownMenuLabel, 
  DropdownMenuItem, 
  DropdownMenuContent,
  DropdownMenuSeparator 
} from "@/components/ui/dropdown-menu"
import { MoreHorizontal } from "lucide-react"

import { FilterConfig } from "@/types"

export const columns = [
  {
    accessorKey: "id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="id" label="ID" config={v} />
  },
  {
    accessorKey: "name",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="name" label="Name" config={v} />
  },
  {
    accessorKey: "phone",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="phone" label="Phone #" config={v} />
  },
  {
    accessorKey: "num_orders",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="num_orders" justify="center" label="# of orders" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue("num_orders") }</div>
  },
  {
    accessorKey: "grand_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="grand_total" justify="end" label="Lifetime Value" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("grand_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "payment_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="payment_total" justify="end" label="Total Paid" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("payment_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "total_commission",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="total_commission" label="Total Commision" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("total_commission"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "balance",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="balance" justify="end" label="Balance" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("balance"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
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

export const filterConfigs: FilterConfig[] = [
  {
    key: "id",
    label: "ID",
    inputType: 'select',
    dataType: 'int',
    group: "Customer",
  },
  {
    key: "name",
    label: "Name",
    inputType: 'select',
    dataType: 'string',
    group: "Customer"
  },
  {
    key: "phone",
    label: "Phone #",
    inputType: 'text',
    dataType: 'string',
    group: "Customer"
  },
  {
    key: "num_orders",
    label: "# of orders",
    inputType: 'number',
    dataType: 'int',
    group: "Orders"
  },
  {
    key: "grand_total",
    label: "Grand Total",
    inputType: 'number',
    dataType: 'float',
    group: "Orders"
  },
  {
    key: "payments_total",
    label: "Total Paid",
    inputType: 'number',
    dataType: 'float',
    group: "Orders"
  },
  {
    key: "commission",
    label: "Commission Paid",
    inputType: 'number',
    dataType: 'float',
    group: "Orders"
  },
  {
    key: "balance",
    label: "Balance",
    inputType: 'number',
    dataType: 'float',
    group: "Orders"
  },
  {
    key: "created_at",
    label: "Created At",
    inputType: 'date',
    dataType: 'float',
    group: "Customer"
  },
]