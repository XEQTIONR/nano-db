import { ArrowUpDown, ArrowDownNarrowWide, ArrowDownWideNarrow } from "lucide-react"
import { cn } from "@/lib/utils"
import { router } from '@inertiajs/react'
import { SortingState, ColumnSort } from "@tanstack/react-table"
import { Button } from "@/components/ui/button"

export function DataTableCustomColumnHeader({
    label, 
    colKey, 
    justify = "start",
    config
} : {
    label: string,
    colKey: string,
    justify?: "start" | "center" | "end",
    config?: {
        table: object
    },
}) {
    let sortParam: ColumnSort | undefined = undefined

    if (config) {
        const st :{sorting: SortingState} =  config.table.getState()
        sortParam = st.sorting.find(({ id }) => id == colKey)
    }

    const toggleSorting = () => {
        if (config) {
            const st :{sorting: SortingState} =  config.table.getState()
            sortParam = st.sorting.find(({ id }) => id == colKey)
            let dir = 'asc'

            if (sortParam) {
                if (sortParam.desc === false) { //toggle
                    dir = 'desc'
                }
            }
            const url = new URL(window.location.href)

            const data = {}
            for (const [key, value] of url.searchParams) {
                data[key] = value
                if (key == 'sortDir') {
                    dir = (value == 'asc') ? 'desc' : 'asc'
                }
            }
            data.sortDir = dir
            data.sortBy = config.column.id
            const v = [{id: colKey, desc: dir == 'desc'}]

            config.table.setSorting(v)

            router.get(url.pathname, data, { preserveState: true })
        }
    }

    return (<div className={cn(
        "flex items-center gap-2",
        "justify-"+justify
    )}>
        {label}
        <Button variant="ghost" size="icon" 
            className={cn(
                config && sortParam
                ? "text-neutral-400 hover:text-neutral-800 dark:hover:text-neutral-300 dark:text-neutral-500 cursor-pointer"
                : "text-neutral-300 hover:text-neutral-800 dark:text-neutral-700 dark:hover:text-neutral-200 cursor-pointer",
            )}
            onClick={toggleSorting}
        >
            {
                config.table.getState().sorting.find(({ id }) => id == colKey)
                    ? (config.table.getState().sorting.find(({ id }) => id == colKey).desc 
                        ? <ArrowDownWideNarrow strokeWidth={2.2} /> 
                        : <ArrowDownNarrowWide strokeWidth={2.2} /> 
                    )
                    : <ArrowUpDown strokeWidth={2.2} /> 
            }
        </Button>
    </div>)
}