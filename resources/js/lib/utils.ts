import { ReportType } from '@/types';
import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function currencyFormat(code: string, amount: number) {
    let c = code
    if (code.toUpperCase() === "RMB") {
            c = "CNY"
    }
    return Intl.NumberFormat("en-IN", { 
        style: "currency",
        currency: c,
        currencyDisplay: "narrowSymbol"
    }).format(amount)
}

export function formatChartTooltipLabel (type: ReportType, date: Date) {
    switch(type) {
        case "daily":
            return (new Date(date)).getDate() + " " + (new Date(date)).toLocaleString('default', { month: 'short' }) + " " + (new Date(date)).getFullYear()
        case "monthly":
            return (new Date(date)).toLocaleString('default', { month: 'short' }) + " " + (new Date(date)).getFullYear()
        case "yearly":
            return (new Date(date)).getFullYear()
    }
    
}