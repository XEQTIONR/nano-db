import {
  ColumnDef,
} from "@tanstack/react-table"

export interface DataTableProps<TData, TValue> {
  columns: ColumnDef<TData, TValue>[]
  data: TData[]
  meta: {total: number}
  sortBy?: string
  sortDir?: "asc" | "desc"
}