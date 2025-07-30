import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function currencyFormat(code: string, amount: number) {
    return Intl.NumberFormat("en-IN", { 
        style: "currency",
        currency: code,
        currencyDisplay: "narrowSymbol"
    }).format(amount)
}