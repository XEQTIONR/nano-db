import { ArrowUpDown, ArrowDownNarrowWide, ArrowDownWideNarrow } from "lucide-react"
import { cn } from "@/lib/utils"
import { router } from '@inertiajs/react'
import { SortingState, ColumnSort } from "@tanstack/react-table"
import { Button } from "../button"

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
    let icon = <ArrowUpDown strokeWidth={2.2} />

    if (config) {
        const st :{sorting: SortingState} =  config.table.getState()
        sortParam = st.sorting.find(({ id }) => id == colKey)
        
        if (sortParam) {
            icon = sortParam.desc ? <ArrowDownWideNarrow strokeWidth={2.2} /> : <ArrowDownNarrowWide strokeWidth={2.2} />
        }
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

            url.searchParams.set('sortDir', dir)
            url.searchParams.set('sortBy', colKey)
            console.log('URL: ', url.toString());
            router.get(url)
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
            { icon }
        </Button>
    </div>)
}