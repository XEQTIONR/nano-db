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
    accessorKey: "grand_total",
    header: "Grand Total",
    cell: ({ row }) => {

        const code = 'BDT'
        const amount = parseFloat(row.getValue("grand_total"))

        return <div className="text-right">{(new Intl.NumberFormat("en-IN", 
            { style: "currency", currency: code, currencyDisplay: "narrowSymbol" })
            .format(amount))}</div>
    }
  },
  {
    accessorKey: "created_at",
    header: () => <div className="text-center">Created On</div>,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>
  },
]