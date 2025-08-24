import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavCollapseGroup {
    title: string;
    icon?: LucideIcon | null;
    links: NavItem[];
    isActive?: boolean
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface PaginationLinkData {
    active: boolean;
    url: string;
    label: string
}

export interface PaginationMeta {
    total: number;
    current_page: number;
    from: number;
    to: number;
    links: PaginationLinkData[]
    per_page: number;
}

export type StrOrNum = string | number
interface Option {
    value: StrOrNum,
    label: string,
}

export type Filter = [string, string, string]
export type FilterOperator = "lt" | "lte" | "eq" | "gt" | "gte" | "ne" | "like" | "in"

export type FilterTransformed = [
    string, 
    FilterOperator | undefined,
    StrOrNum | StrOrNum[] | undefined
]


export interface FilterConfig {
    key: string,
    label: string,
    dataType: 'float' | 'int' | 'string',
    inputType: 'select' | 'date' | 'number' | 'text',
    group: string,
    getOptions?: (apiToken: string) => ((search: string) => Promise<Option[]>),
    ops?: FilterOperator[]
}
export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface LetterOfCredit {
    lc_num: string
    date_issued?: Date
    date_expiry?: Date
    applicant: string
    beneficiary: string
    port_depart: string
    port_arrive: string
    currency_code: string
    exchange_rate: number
    foreign_amount: number
    foreign_expense: number
    domestic_expense: number
    notes: string
    invoice_no?: string
    items?: ProformaInvoiceItem[]
}

export interface Tyre {
    id: number;
    brand: string;
    size: string;
    pattern: string;
    lisi: string;
}

export type ProformaInvoiceItem = Tyre & {
    qty: number,
    unit_price: number,
}

export interface LetterOfCreditFormErrors {
    lc_num?: string,
    date_issued?: string,
    date_expiry?: string,
    applicant?: string,
    beneficiary?: string,
    port_depart?: string,
    port_arrive?: string,
    currency_code?: string,
    exchange_rate?: string,
    foreign_amount?: string,
    foreign_expense?: string,
    domestic_expense?: string,
    notes?: string,
}
