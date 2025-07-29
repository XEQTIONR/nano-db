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

export const columns = [
  {
    accessorKey: "container_num",
    header: "Container #",
  },
  {
    accessorKey: "bol",
    header: "Bill of Lading #",
  },
  {
    accessorKey: "lc_num",
    header: "LC #",
  },
  {
    accessorKey: "land_date",
    header: "Landed On",
  },
  {
    accessorKey: "created_at",
    header: () => <div className="text-center">Created On</div>,
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