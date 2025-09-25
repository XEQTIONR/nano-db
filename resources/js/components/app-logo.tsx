import { usePage } from '@inertiajs/react';
import AppLogoIcon from './app-logo-icon';
import { useSidebar } from './ui/sidebar';
import { cn } from '@/lib/utils';

export default function AppLogo() {

    const { name } = usePage().props
    const { open } = useSidebar()
    return (
        <div className={cn("flex gap-4 items-center",
        )}>
            <div className="flex aspect-square size-9 bg-sidebar-primary items-center justify-center rounded-md  text-sidebar-primary-foreground">
                <AppLogoIcon className={
                    cn("text-white dark:text-black",
                    open ? "size-6" : "size-6")
                } />
            </div>
            <div className="grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tighter text-base font-bold">{name}</span>
            </div>
        </div>
    );
}
