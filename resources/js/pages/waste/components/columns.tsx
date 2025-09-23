import { DataTableCustomColumnHeader } from "@/components/ui/data-table/column-header"
// This type is used to define the shape of our data.
// You can use a Zod schema here if you want.
import { type FilterConfig, type Option } from "@/types"
import { Link } from "@inertiajs/react"

export const columns = [
  {
    accessorKey: "id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="id" label="#" config={v} />,
    cell: ({ row }) => <div className="font-medium text-center text-black dark:text-white">{ row.getValue('id') }</div>
  },
  {
    accessorKey: "bol",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="id" label="BOL #" config={v} />,
    cell: ({ row }) => <div className="text-center">
      <Link className="italic hover:underline" href={route('consignments.show', { consignment: row.getValue('bol')})}>
      { row.getValue('bol') }
      </Link>
    </div>
  },
  {
    accessorKey: "container_num",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="id" label="Container #" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('container_num') }</div>
  },
  {
    accessorKey: "tyre_id",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="tyre_id" label="Tyre ID" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('tyre_id') }</div>
  },
  // {
  //   accessorKey: "brand",
  //   header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="brand" label="Brand" config={v} />
  // },
  // {
  //   accessorKey: "size",
  //   header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="size" label="Size" config={v} />
  // },
  // {
  //   accessorKey: "pattern",
  //   header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="pattern" label="Pattern" config={v} />
  // },
  // {
  //   accessorKey: "lisi",
  //   header: (v: {table: object}) => <DataTableCustomColumnHeader justify="start" colKey="lisi" label="Li/Si" config={v} />
  // },
  {
    accessorKey: "qty",
    header: (v: {table: object}) => <DataTableCustomColumnHeader justify="center" colKey="qty" label="Qty" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('qty') }</div>
  },
  {
    accessorKey: "created_at",
    header: (v: {table: object}) => <DataTableCustomColumnHeader colKey="created_at" justify="center" label="Created On" config={v} />,
    cell: ({ row }) => <div className="text-center">{ row.getValue('created_at') }</div>
  },
]

export const filterConfigs: FilterConfig[] = [
  {
    key: "qty",
    label: "Quantity",
    inputType: 'number',
    dataType: 'int',
    group: "Waste",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
  {
    key: "created_at",
    label: "Created At",
    inputType: 'date',
    dataType: 'string',
    group: "Waste",
    ops: ['eq', 'lt', 'lte', 'gt', 'gte', 'ne']
  },
]