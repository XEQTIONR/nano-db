import { cn } from "@/lib/utils"
import { useEffect, useRef } from 'react'
export default function DashboardCard({title, stat, subtitle, className, decimalPlaces = 0, currencyCode = undefined, currencySymbolColorClass = undefined} 
    : {title: string, stat: number, subtitle: string, className: string, decimalPlaces?: number, currencyCode?: string, currencySymbolColorClass?: string}) {


    // create a ref and declare an instance for each countUp animation
    const countupRef = useRef(null);
    let countUpAnim;

    function getCurrencySymbol(code: string) {
        const formatter = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: code,
            currencyDisplay: 'narrowSymbol',
            minimumFractionDigits: 0, // Ensure no decimal parts are included in the formatting
            maximumFractionDigits: 0
        });

        const parts = formatter.formatToParts(0); // Format a zero value to isolate the symbol

        const currencyPart = parts.find(part => part.type === 'currency');
        return currencyPart ? currencyPart.value : '';
    }
    
    // useEffect with empty dependency array runs once when component is mounted
    useEffect(() => {
        initCountUp();
    }, []);

    // dynamically import and initialize countUp, sets value of `countUpAnim`
    // you don't have to import this way, but this works best for next.js
    async function initCountUp() {
        const countUpModule = await import('countup.js');
        countUpAnim = new countUpModule.CountUp(countupRef.current, stat, { 
            useIndianSeparators: true, 
            decimalPlaces: decimalPlaces,
            duration: 1 
        });
        if (!countUpAnim.error) {
        countUpAnim.start();
        } else {
        console.error(countUpAnim.error);
        }
    }
    return(
        <div className={cn(
            "p-5 h-44 aspect-video overflow-hidden rounded-xl border dark:border-none shadow-md dark:shadow-none  dark:bg-[#121212]",
            className
        )}>                            
            <div className="flex justify-between flex-col h-full">
                <div>
                    <h3 className="font-bold text-2xl xl:text-lg">{title}</h3>
                </div>
                <div className="text-3xl sm:text-4xl md:text-3xl lg:text-4xl font-bold whitespace-nowrap">
                    <span className={cn(
                        currencySymbolColorClass ?? "text-emerald-500"
                    )}>{currencyCode && getCurrencySymbol(currencyCode)}</span> <span ref={countupRef}>{stat}</span>
                </div>
                <span className="text-lg xl:text-sm">{subtitle}</span>
            </div>
        </div>
    )
}