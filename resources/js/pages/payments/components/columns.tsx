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
import { Link } from "@inertiajs/react"

export const columns = [
  {
    accessorKey: "transaction_id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="transaction_id" label="Transaction ID" config={v} />,
    cell: ({ row }) => <span className="text-black dark:text-white font-medium">{row.getValue('transaction_id')}</span>
  },
  {
    accessorKey: "order_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_num" label="Order" config={v} />,
    cell: ({ row }) => <Link className="italic hover:underline" href={route("orders.show", {order: row.getValue('order_num')})}>{ row.getValue('order_num') }</Link>
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
    key: "transaction_id",
    label: "Transaction ID",
    inputType: 'select',
    dataType: 'string',
    group: "Payment",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.payments.index', {
          filters: "transaction_id.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })
        console.log('response:', response)
        return response.data.items.map(({transaction_id} : {transaction_id: string}) => {
          return {value: transaction_id, label: transaction_id}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "order_num",
    label: "Order #",
    inputType: 'select',
    dataType: 'int',
    group: "Payment",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.orders.index', {
          filters: "Order_num.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })
        return response.data.items.map(({order_num} : {order_num: number}) => {
          return {value: order_num, label: order_num}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "type",
    label: "Type",
    inputType: 'select',
    dataType: 'string',
    group: "Payment",
    getOptions: (apiToken: string) => async () => [
      { value: 'unknown', label: 'unknown'},
      { value: 'deposit', label: 'deposit'},
      { value: 'cash', label: 'cash'},
      { value: 'check', label: 'check'},
    ],
    ops: ['in']
  },
  {
    key: "amount",
    label: "Amount",
    inputType: 'number',
    dataType: 'float',
    group: "Payment",
    ops: ['gt', 'lt', 'gte', 'lte', 'eq', 'ne']
  },
  
]