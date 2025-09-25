import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"
import { Button } from "@/components/ui/button"
import { 
  DropdownMenu, 
  DropdownMenuTrigger, 
  DropdownMenuLabel, 
  DropdownMenuItem, 
  DropdownMenuContent,
  DropdownMenuSeparator 
} from "@/components/ui/dropdown-menu"
import { MoreHorizontal } from "lucide-react"
import axios from 'axios'
import { FilterConfig, Option } from "@/types"
import { Link } from "@inertiajs/react"
// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.

export const columns = [
  {
    accessorKey: "container_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="container_num" label="Container #" config={v} />,
    cell: ({ row }) => <span className="font-medium text-black dark:text-white">{row.getValue('container_num')}</span>
  },
  {
    accessorKey: "land_date",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="land_date" label="Landed On" config={v} />
  },
  {
    accessorKey: "bol",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="bol" justify="center" label="Bill of lading #" config={v} />,
    cell: ({ row }) => <div className="text-center">
      <Link className="italic hover:underline" href={route('consignments.show', { consignment: row.getValue('bol')})}>
      { row.getValue('bol') }
      </Link>
    </div>
  },
  {
    accessorKey: "lc_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="lc_num" justify="center" label="LC #" config={v} />,
    cell: ({ row }) => <div className="text-center">
      <Link className="italic hover:underline" href={route('lcs.show', { lc: row.getValue('lc_num')})}>
      { row.getValue('lc_num') }
      </Link>
    </div>
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" justify="end" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-end">{ row.getValue('created_at') }</div>
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

export const filterConfigs: FilterConfig[] = [
  {
    key: "container_num",
    label: "Container #",
    inputType: 'select',
    dataType: 'string',
    group: "Container",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.containers.index', {
          filters: "Container_num.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })
        console.log('response:', response)
        return response.data.items.map(({container_num} : {container_num: string}) => {
          return {value: container_num, label: container_num}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "bol",
    label: "Bill of lading #",
    inputType: 'select',
    dataType: 'string',
    group: "Container",
    getOptions: (apiToken: string) => {
      return async (search: string) : Promise<Option[]>  => {
        const endpoint = route('api.consignments.index', {
          filters: "bol.like." + search
        })
        
        const response = await axios.get(endpoint, { headers: { Authorization: 'Bearer ' + apiToken } })
        console.log('response:', response)
        return response.data.items.map(({bol} : {bol: string}) => {
          return {value: bol, label: bol}
        })
      }
    },
    ops: ['in']
  },
  {
    key: "lc_num",
    label: "LC #",
    inputType: 'select',
    dataType: 'string',
    group: "Container",
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
    key: "land_date",
    label: "Landed On",
    inputType: 'date',
    dataType: 'string',
    group: "Container",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "created_at",
    label: "Created At",
    inputType: 'date',
    dataType: 'string',
    group: "Container",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
]