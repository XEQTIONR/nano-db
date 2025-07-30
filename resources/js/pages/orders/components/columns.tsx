
const currencyFormat = (code: string, amount: number) => (new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: code, currencyDisplay: "narrowSymbol" })
            .format(amount))

export const columns = [
  {
    accessorKey: "order_num",
    header: "Order #",
  },
  {
    accessorKey: "order_on",
    header: "Date ordered",
  },
  {
    accessorKey: "customer_id",
    header: "Customer ID",
  },
  {
    accessorKey: "customer_name",
    header: "Customer",
  },
  {
    accessorKey: "commission",
    header: "Commision Paid",
  },
  {
    accessorKey: "count",
    header: "# of items",
  },
  {
    accessorKey: "count_payments",
    header: "# of payments",
  },
  {
    accessorKey: "grand_total",
    header: "Grand Total",
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("grand_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "payments_total",
    header: "Payments Total",
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("payments_total"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "balance",
    header: "Balance",
    cell: ({ row }) => {
        const amount = parseFloat(row.getValue("balance"))
        return <div className="text-right">{ currencyFormat('BDT', amount)}</div>
    }
  },
  {
    accessorKey: "created_at",
    header: () => <div className="text-center">Created On</div>,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>
  },
]