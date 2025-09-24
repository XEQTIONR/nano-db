import { Badge } from '@/components/ui/badge';
import { cn } from "@/lib/utils";

export default function Stepper({current, steps} : { current: number, steps: string[]}) {
    return (
        <div className="flex flex-col lg:flex-row gap-4 flex-grow justify-between md:justify-start">
        {
            steps.map((step, index) => (
                <div className="flex flex-row items-center gap-4">
                    <Badge className={cn(
                        "h-7 min-w-7 px-1.5 py-1 rounded-full text-xs font-bold font-mono ",
                        index == current ? "bg-indigo-500 text-white" : "bg-neutral-300 dark:bg-neutral-800 dark:text-neutral-400"
                    )}>
                        {index + 1}
                    </Badge>
                    <span className={cn(
                        "text-sm md:text-base font-bold text-center md:text-left",
                        index == current ? "dark:text-white text-neutral-950" : "text-neutral-400 dark:text-neutral-700"
                    )}>
                        {step}
                    </span>
                </div> 
            ))
        }
        </div>
    )
}