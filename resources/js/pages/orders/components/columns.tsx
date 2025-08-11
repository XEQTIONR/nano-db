import { currencyFormat } from "@/lib/utils"
import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"
import { FilterConfig } from "@/types"
import axios from 'axios'
import { type Option } from "@/types"

export const columns = [
  {
    accessorKey: "order_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_num" label="Order #" config={v} />,
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "order_on",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_on" label="Order on" config={v} />,
    filterOptions: {
      dataType: 'date',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "customer_id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="customer_id" label="Customer ID" config={v} />,
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne', 'like']
    }
  },
  {
    accessorKey: "customer_name",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="customer_name" label="Customer" config={v} />,
    filterOptions: {
      dataType: 'string',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "count",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="count" label="# of items" config={v} />,
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "count_payments",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="count_payments" label="# of payments" config={v} />,
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "grand_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="grand_total" label="Grand Total" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("grand_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "payments_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="payments_total" label="Total Paid" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("payments_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "commission",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="commission" label="Commission paid" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("commission"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "balance",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="balance" label="Balance" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("balance"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    },
    filterOptions: {
      dataType: 'numeric',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>,
    filterOptions: {
      dataType: 'date',
      ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
    }
  },
]


export const filterOptions: FilterConfig[] = [
  {
    key: "order_num",
    label: "Order #",
    inputType: 'select',
    dataType: 'string',
    group: "Order",
    // endpoint: "/customers"
  },
  {
    key: "order_on",
    label: "Order Date",
    inputType: 'date',
    dataType: 'string',
    group: "Order"
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
          filters: "id.like." + search // refine this
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })

        return response.data.items.map(({id} : {id: number}) => {
          return {value: id, label: id.toString()}
        })
      }
    }
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
          filters: "name.like." + search // refine this
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })

        return response.data.items.map(({id, name} : {id: number, name: string}) => {
          return {value: id, label: name}
        })
      }
    }
  },
  {
    key: "count",
    label: "Number of items",
    inputType: 'number',
    dataType: 'int',
    group: "Order"
  },
  {
    key: "count_payments",
    label: "Number of payments",
    inputType: 'number',
    dataType: 'int',
    group: "Payments"
  },
  {
    key: "grand_total",
    label: "Grand Total",
    inputType: 'number',
    dataType: 'float',
    group: "Order"
  },
  {
    key: "payments_total",
    label: "Total Paid",
    inputType: 'number',
    dataType: 'float',
    group: "Payments"
  },
  {
    key: "commission",
    label: "Commission Paid",
    inputType: 'number',
    dataType: 'float',
    group: "Payments"
  },
  {
    key: "balance",
    label: "Balance",
    inputType: 'number',
    dataType: 'float',
    group: "Payments"
  },
  {
    key: "created_at",
    label: "Created At",
    inputType: 'date',
    dataType: 'float',
    group: "Order"
  },
]