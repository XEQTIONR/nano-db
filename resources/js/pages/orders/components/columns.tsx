import { currencyFormat } from "@/lib/utils"
import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"
import { FilterConfig } from "@/types"
import axios from 'axios'
import { type Option } from "@/types"
import { Link } from "@inertiajs/react"

export const columns = [
  {
    accessorKey: "order_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_num" label="Order #" config={v} />,
    cell: ({ row }) => <Link className="font-medium hover:underline text-black dark:text-white" href={route('orders.show', { order: row.getValue("order_num") })}>
        {row.getValue("order_num")}
      </Link>,
  },
  {
    accessorKey: "order_on",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_on" label="Order on" config={v} />,
  },
  {
    accessorKey: "customer_id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="customer_id" label="Customer ID" config={v} />,
    cell: ({ row }) => <div className="text-center">{row.getValue("customer_id")}</div>
  },
  {
    accessorKey: "customer_name",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="customer_name" label="Customer" config={v} />,
  },
  {
    accessorKey: "count",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="count" label="# of items" config={v} />,
    cell: ({ row }) => <div className="text-center">{row.getValue("count")}</div>
  },
  {
    accessorKey: "count_payments",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="count_payments" label="# of payments" config={v} />,
    cell: ({ row }) => <div className="text-center">{row.getValue("count_payments")}</div>
  },
  {
    accessorKey: "grand_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="grand_total" label="Grand Total" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("grand_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
  },
  {
    accessorKey: "payments_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="payments_total" label="Total Paid" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("payments_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
  },
  {
    accessorKey: "commission",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="commission" label="Commission paid" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("commission"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
  },
  {
    accessorKey: "balance",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="balance" label="Balance" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("balance"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>,
  },
]


export const filterConfigs: FilterConfig[] = [
  {
    key: "order_num",
    label: "Order #",
    inputType: 'select',
    dataType: 'string',
    group: "Order",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.orders.index', {
          filters: "Order_num.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })

        return response.data.items.map(({order_num} : {order_num: number}) => {
          return {value: order_num, label: order_num.toString()}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "order_on",
    label: "Order Date",
    inputType: 'date',
    dataType: 'string',
    group: "Order",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "customer_id",
    label: "Customer ID",
    inputType: 'select',
    dataType: 'int',
    group: "Customer",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.customers.index', {
          filters: "id.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })

        return response.data.items.map(({id} : {id: number}) => {
          return {value: id, label: id.toString()}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "customer_name",
    label: "Customer",
    inputType: 'select',
    dataType: 'string',
    group: "Customer",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.customers.index', {
          filters: "name.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })

        return response.data.items.map(({name} : {name: string}) => {
          return {value: name, label: name}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "count",
    label: "Number of items",
    inputType: 'number',
    dataType: 'int',
    group: "Order",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "count_payments",
    label: "Number of payments",
    inputType: 'number',
    dataType: 'int',
    group: "Payments",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "grand_total",
    label: "Grand Total",
    inputType: 'number',
    dataType: 'float',
    group: "Order",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "payments_total",
    label: "Total Paid",
    inputType: 'number',
    dataType: 'float',
    group: "Payments",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "commission",
    label: "Commission Paid",
    inputType: 'number',
    dataType: 'float',
    group: "Payments",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "balance",
    label: "Balance",
    inputType: 'number',
    dataType: 'float',
    group: "Payments",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "created_at",
    label: "Created At",
    inputType: 'date',
    dataType: 'string',
    group: "Order",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
]