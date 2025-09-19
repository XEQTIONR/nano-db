import { cn } from "@/lib/utils"
export default function DashboardCard({title, stat, subtitle, className} 
    : {title: string, stat: string, subtitle: string, className: string}) {
    return(
        <div className={cn(
            "p-5 h-44 aspect-video overflow-hidden border-none rounded-xl bg-[#121212]",
            className
        )}>                            
            <div className="flex justify-between flex-col h-full">
                <div>
                    <h3 className="font-bold text-2xl lg:text-xl xl:text-lg">{title}</h3>
                </div>
                <span className="text-3xl sm:text-5xl md:text-xl lg:text-4xl  font-bold">{stat}</span>
                <span className="text-lg xl:text-sm">{subtitle}</span>
            </div>
        </div>
    )
}