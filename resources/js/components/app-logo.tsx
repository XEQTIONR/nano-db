import { usePage } from '@inertiajs/react';
import AppLogoIcon from './app-logo-icon';
import { useSidebar } from './ui/sidebar';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react'
export default function AppLogo() {

    const { name } = usePage().props
    const { open } = useSidebar()
    return (
        <div className={cn("flex gap-4 items-center",
        )}>
            <Link className="w-full" href="/">
            <div className={cn(
                'rounded-md w-full flex items-center gap-3 hover:bg-accent',
                open ? 'pt-1' : 'pt-0.5'
            )}>
                <div className={cn(
                    "flex aspect-square size-9 items-center justify-center rounded-md  text-sidebar-primary-foreground",
                
                )}>
                    <AppLogoIcon className={
                        cn("text-white dark:text-black",
                        open ? "size-9" : "size-7")
                    }/>
                </div>
                <div className="grid flex-1 text-left text-sm">
                    <span className="mb-0.5 truncate leading-tighter tracking-tight text-neutral-800 dark:text-neutral-200 font-bold text-base">{name}</span>
                </div>
            </div>
            </Link>
            
        </div>
    );
}
