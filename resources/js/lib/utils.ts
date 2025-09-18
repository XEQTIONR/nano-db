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

export function relativeUrl(url: string) {
    return url.slice((window.location.protocol + "//" + window.location.hostname + ((window.location.port.length > 0) ? (":" + window.location.port) : "")).length)
}