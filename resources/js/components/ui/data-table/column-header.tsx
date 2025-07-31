import { ArrowUpDown, ArrowDownNarrowWide, ArrowDownWideNarrow } from "lucide-react"
import { cn } from "@/lib/utils"
import { router } from '@inertiajs/react'
import { SortingState, ColumnSort } from "@tanstack/react-table"

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
    let icon = <ArrowUpDown strokeWidth={2.5} size={17} />
    if (config) {
        const st :{sorting: SortingState} =  config.table.getState()
        const sortParam: ColumnSort | undefined = st.sorting.find(({ id }) => id == colKey)
        
        if (sortParam) {
            icon = sortParam.desc
                ? <ArrowDownWideNarrow strokeWidth={2.5} size={17} />
                : <ArrowDownNarrowWide strokeWidth={2.5} size={17} />
        }
    }

    const toggleSorting = () => {
        if (config) {
            const st :{sorting: SortingState} =  config.table.getState()
            const sortParam: ColumnSort | undefined = st.sorting.find(({ id }) => id == colKey)

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
        <span className="text-neutral-200 dark:hover:text-neutral-300 dark:text-neutral-700 cursor-pointer"
            onClick={toggleSorting}
        >
            { icon }
        </span>
    </div>)
}