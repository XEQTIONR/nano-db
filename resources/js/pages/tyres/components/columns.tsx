import { Button } from "@/components/ui/button"
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
import { router } from "@inertiajs/react"
// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.

export const columns = [
  {
    accessorKey: "id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="id" label="ID" config={v} />,
    cell: ({ row }) => <span className="text-black dark:text-white font-medium">{row.getValue('id')}</span>
  },
  {
    accessorKey: "brand",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="brand" label="Brand" config={v} />
  },
  {
    accessorKey: "size",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="size" label="Size" config={v} />
  },
  {
    accessorKey: "pattern",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="pattern" label="Pattern" config={v} />
  },
  {
    accessorKey: "lisi",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="lisi" label="Li/Si" config={v} />
  },
  {
    accessorKey: "in_stock",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="in_stock" justify="center" label="# in stock" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('in_stock') }</div>
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" justify="end" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-end">{ row.getValue('created_at') }</div>
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
            <DropdownMenuItem>
              <Button
                  onClick={() => router.visit(route('tyres.edit', {
                    tyre: row.getValue("id")
                  }))} 
                  className="hover:cursor-pointer" 
                  size="icon" 
                  variant="ghost"
              >
                  Edit
              </Button>
            </DropdownMenuItem>
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
    inputType: 'number',
    dataType: 'int',
    group: "Tyre",
    ops: ['eq', 'ne', 'gt', 'gte', 'lt', 'lte']
  },
  {
    key: "brand",
    label: "Brand",
    inputType: 'text',
    dataType: 'string',
    group: "Tyre",
    ops: ['like']
  },
  {
    key: "size",
    label: "Size",
    inputType: 'text',
    dataType: 'string',
    group: "Tyre",
    ops: ['like']
  },
  {
    key: "pattern",
    label: "Pattern",
    inputType: 'text',
    dataType: 'string',
    group: "Tyre",
    ops: ['like']
  },
  {
    key: "lisi",
    label: "Li/Si",
    inputType: 'text',
    dataType: 'string',
    group: "Tyre",
    ops: ['like']
  },
]