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
import { type FilterConfig, type Option } from "@/types"
import axios from 'axios'
import { Link } from '@inertiajs/react'

export const columns = [
  {
    accessorKey: "lc_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="lc_num" label="LC #" config={v} />,
    cell: ({ row }) => <Link className="font-medium hover:underline text-black dark:text-white" href={route('lcs.show', {lc: row.getValue("lc_num")})}>{row.getValue("lc_num")}</Link>
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
            <div className="flex justify-end pr-3">
              <span className="sr-only">Open menu</span>
              <MoreHorizontal size={16} className="ml-3 dark:hover:stroke-white" />
            </div>
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
    key: "lc_num",
    label: "LC #",
    inputType: 'select',
    dataType: 'string',
    group: "Letter of Credit",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.lcs.index', {
          filters: "lc_num.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })
        console.log('response:', response)
        return response.data.items.map(({lc_num} : {lc_num: string}) => {
          return {value: lc_num, label: lc_num}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "date_issued",
    label: "Date issued",
    inputType: 'date',
    dataType: 'string',
    group: "Letter of Credit",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "date_expiry",
    label: "Expires on",
    inputType: 'date',
    dataType: 'string',
    group: "Letter of Credit",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "exchange_rate",
    label: "Exchange rate",
    inputType: 'number',
    dataType: 'float',
    group: "Letter of Credit",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "foreign_amount",
    label: "Foreign Amount",
    inputType: 'number',
    dataType: 'float',
    group: "Letter of Credit",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "local_amount",
    label: "Local Amount",
    inputType: 'number',
    dataType: 'float',
    group: "Letter of Credit",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  // {
  //   key: "total_expenses",
  //   label: "Total Expense",
  //   inputType: 'number',
  //   dataType: 'float',
  //   group: "Letter of Credit",
  //   ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  // },
  {
    key: "created_at",
    label: "Created At",
    inputType: 'date',
    dataType: 'string',
    group: "Letter of Credit",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
]