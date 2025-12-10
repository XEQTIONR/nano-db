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
                    open && 'mr-1',
                    
                )}>
                    <div className={cn(
                        "flex aspect-square py-1 items-center justify-center rounded-md  text-sidebar-primary-foreground",
                         "size-10",
                         !open && "dark:bg-neutral-800 -ml-1"
                        
                    )}>
                        <AppLogoIcon className={
                            cn(open ? "scale-110" : "", 
                                "size-8 transition duration-300 ease-in-out"
                            )
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
