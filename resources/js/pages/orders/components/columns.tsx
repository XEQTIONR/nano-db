
import { currencyFormat } from "@/lib/utils"
import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"

export const columns = [
  {
    accessorKey: "order_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_num" label="Order #" config={v} />
  },
  {
    accessorKey: "order_on",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="order_on" label="Order on" config={v} />
  },
  {
    accessorKey: "customer_id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="customer_id" label="Customer ID" config={v} />
  },
  {
    accessorKey: "customer_name",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="customer_name" label="Customer" config={v} />
  },
  {
    accessorKey: "commission",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="commission" label="Commission paid" config={v} />
  },
  {
    accessorKey: "count",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="count" label="# of items" config={v} />
  },
  {
    accessorKey: "count_payments",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="count_payments" label="# of payments" config={v} />
  },
  {
    accessorKey: "grand_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="grand_total" label="Grand Total" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("grand_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "payments_total",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="payments_total" label="Total Paid" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("payments_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "balance",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="balance" label="Balance" config={v} />,
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("balance"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>
  },
]