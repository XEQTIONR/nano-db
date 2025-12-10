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

const uc = (str: string) => str.charAt(0).toUpperCase() + str.slice(1)

export const columns = [
  {
    accessorKey: "id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="id"  justify="center" label="ID" config={v} />,
    cell: ({ row }) => <div className="text-center font-medium text-black dark:text-white">{ row.getValue("id") }</div>
  },
  {
    accessorKey: "date",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="date" justify="start" label="Date" config={v} />,
    cell: ({ row }) => <div className="">{ row.getValue("date") }</div>
  },
  {
    accessorKey: "expensable_type",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="expensable_type" label="Type" config={v} />,
    cell: ({ row }) => <div className="">{ 
      uc(row.getValue("expensable_type").split('App\\Models\\').join("")) 
    }</div>
  },
  {
    accessorKey: "expensable_id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="expensable_id" justify="center" label="Expensable ID" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue("expensable_id") }</div>
  },
  {
    accessorKey: "note",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="note" justify="center" label="Note" config={v} />,
    cell: ({ row }) => {
      if (row.getValue("redacted")) {
        return "********"
      }
      return <div className="overflow-ellipsis">{ row.getValue("note") }</div>
    }
  },
  {
    accessorKey: "amount_local",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="amount_local" justify="end" label="Amount Local" config={v} />,
    cell: ({ row }) => {
        if (row.getValue("redacted")) {
          return <div className="text-end">********</div>
        }
        const amount = parseFloat(row.getValue("amount_local"))
        return <div className="text-right">{ currencyFormat('BDT', amount) }</div>
    }
  },
  {
    accessorKey: "redacted",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="redacted" justify="center" label="Redacted" config={v} />,
    cell: ({ row }) => <div className="text-center">{row.getValue("redacted") ? "True" : "False"}</div>
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" justify="end" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>
  },
  // {
  //   id: "actions",
  //   enableHiding: false,
  //   cell: ({ row }) => {
  //     return (
  //       <DropdownMenu>
  //         <DropdownMenuTrigger asChild>
  //             <div className="flex justify-end pr-3">
  //               <span className="sr-only">Open menu</span>
  //               <MoreHorizontal size={16} className="ml-3 dark:hover:stroke-white" />
  //             </div>
  //         </DropdownMenuTrigger>
  //         <DropdownMenuContent align="end">
  //           <DropdownMenuLabel>Actions</DropdownMenuLabel>
  //           <DropdownMenuSeparator />
  //           <DropdownMenuItem>View</DropdownMenuItem>
  //           <DropdownMenuItem>Add Proforma Invoice</DropdownMenuItem>
  //         </DropdownMenuContent>
  //       </DropdownMenu>
  //     )
  //   },
  // }
]

export const filterConfigs: FilterConfig[] = []