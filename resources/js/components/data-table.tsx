import {
  flexRender,
  getCoreRowModel,
  useReactTable,
  RowSelectionState,
  TableOptions,
} from "@tanstack/react-table"

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table"

import { DataTableProps } from "@/components/ui/data-table/types"
import { useState } from "react"

import { SortingState } from "@tanstack/react-table"

export function DataTable<TData, TValue>({
  columns,
  data,
  meta,
  sortBy,
  sortDir,
  primaryKey,
  selectedValue,
}: DataTableProps<TData, TValue>) {

  const [sorting, setSorting] = useState<SortingState>(() => {
    if (sortBy) {
      return [
        {
          id: sortBy,
          desc: sortDir == 'desc'
        }
      ]
    }

    return []
  })

  const options : TableOptions<TData> = {
    data,
    columns,
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    manualSorting: true,
    rowCount: meta.total,
    //getRowId: 
    state: {
      sorting
    }
  }

  if (primaryKey) {
    options.getRowId = (row) => row[primaryKey]
    if (selectedValue) {
      const obj: RowSelectionState = {}
      obj[selectedValue] = true
      options.state.rowSelection = obj
    }
  }

  const table = useReactTable(options)

  return (
    <div className="overflow-y-scroll max-h-[83vh] rounded-md border">
      <Table>
        <TableHeader>
          {table.getHeaderGroups().map((headerGroup) => (
            <TableRow key={headerGroup.id}>
              {headerGroup.headers.map((header) => {
                return (
                  <TableHead className="font-bold dark:text-white bg-white dark:bg-neutral-950 sticky top-0 z-50" key={header.id}>
                    {
                      header.isPlaceholder
                      ? null
                      : flexRender(
                          header.column.columnDef.header,
                          header.getContext()
                        )}
                  </TableHead>
                )
              })}
            </TableRow>
          ))}
        </TableHeader>
        <TableBody>
          {table.getRowModel().rows?.length ? (
            table.getRowModel().rows.map((row) => (
              <TableRow
              className="text-neutral-900 dark:text-neutral-300"
                key={row.id}
                data-state={row.getIsSelected() && "selected"}
              >
                {row.getVisibleCells().map((cell) => (
                  <TableCell key={cell.id}>
                    {flexRender(cell.column.columnDef.cell, cell.getContext())}
                  </TableCell>
                ))}
              </TableRow>
            ))
          ) : (
            <TableRow>
              <TableCell colSpan={columns.length} className="h-24 text-center">
                No results.
              </TableCell>
            </TableRow>
          )}
        </TableBody>
      </Table>
    </div>
  )
}