import { LucideIcon } from 'lucide-react'
import type { Config } from 'ziggy-js'

export interface Auth {
    user: User
}

export interface BreadcrumbItem {
    title: string
    href: string
}

export interface NavGroup {
    title: string
    items: NavItem[]
}

export interface NavCollapseGroup {
    title: string
    icon?: LucideIcon | null
    links: NavItem[]
    isActive?: boolean
}

export interface NavItem {
    title: string
    href: string
    icon?: LucideIcon | null
    isActive?: boolean
}

export interface SharedData {
    name: string
    quote: { message: string; author: string }
    auth: Auth
    ziggy: Config & { location: string }
    sidebarOpen: boolean
    [key: string]: unknown
}

export interface PaginationLinkData {
    active: boolean
    url: string
    label: string
}

export interface PaginationMeta {
    total: number
    current_page: number
    from: number
    to: number
    links: PaginationLinkData[]
    per_page: number
}

export type StrOrNum = string | number
interface Option {
    value: StrOrNum
    label: string
}

export type Filter = [string, string, string]
export type FilterOperator = "lt" | "lte" | "eq" | "gt" | "gte" | "ne" | "like" | "in"

export type FilterTransformed = [
    string, 
    FilterOperator | undefined,
    StrOrNum | StrOrNum[] | undefined
]


export interface FilterConfig {
    key: string
    label: string
    dataType: 'float' | 'int' | 'string'
    inputType: 'select' | 'date' | 'number' | 'text'
    group: string
    getOptions?: (apiToken: string) => ((search: string) => Promise<Option[]>)
    ops?: FilterOperator[]
}

export interface BankAccount {
    id: number
    account_name: string
    account_number: string
    bank_address: string
    bank_name: string
    created_at: string
}

export interface User {
    id: number
    name: string
    email: string
    avatar?: string
    email_verified_at: string | null
    created_at: string
    updated_at: string
    [key: string]: unknown // This allows for additional properties...
}

export interface Customer {
    id: number
    name: string
    address: string
    phone: string
    created_at: string
    notes?: string
    orders?: Order[]
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
    items?: InvoiceItem[]
    consignments?: Consignment[]
    expenses?: Expense[]
}

export interface Container {
    container_num: string
    bol: string
    land_date: Date | string
    lc_num: string
    toString: string
    contents?: ContainerItem[]
    
}

export interface Consignment {
    lc_num: string
    bol: string
    value: number
    exchange_rate: number
    tax: number
    land_date?: Date
    value_local?: number
    currency_code?: string
    containers?: Container[]
    expenses?: Expense[]
}

export interface Tyre {
    id: number
    brand: string
    size: string
    pattern: string
    lisi: string
}

export type InvoiceItem = Tyre & {
    qty: number
    unit_price: number
}

export type ContainerItem = InvoiceItem & {
    container_num: string
    bol: string
    total_tax: number
    total_weight: number
    total: number
}

export type OrderContentItem = InvoiceItem & {
    order_num: number
    tyre_id: number
    container_num: string
    bol: string
    item_total: number
    tyre: Tyre
    
}

export type OrderReturnItem = OrderContentItem

export interface LetterOfCreditFormErrors {
    lc_num?: string
    date_issued?: string
    date_expiry?: string
    applicant?: string
    beneficiary?: string
    port_depart?: string
    port_arrive?: string
    currency_code?: string
    exchange_rate?: string
    foreign_amount?: string
    foreign_expense?: string
    domestic_expense?: string
    notes?: string
}

export interface Order {
    order_num: number
    order_on: string | Date
    count?: number
    commission: number
    sub_total: number
    discount_percent: number
    discount_amount: number
    tax_percentage: number
    tax_amount: number
    contents?: OrderContentItem[]
    items?: OrderContentItem[]
    returns?: OrderReturnItem[],
    returns_consolidated?: OrderReturnItem[],
    payments?: Payment[]
    customer?: Customer
    customer_name?: string
    customer_id?: number
    sub_total?: number
    grand_total?: number
    payments_total?: number
    balance?: number
    expenses?: Expense[]
}

export interface Payment {
    transaction_id: number
    order_num: number
    account: number | null
    amount: number
    payment_amount: number
    refund_amount: number
    type: "cash" | "deposit" | "check" | "unknown"
    order?: Order
    created_at?: string

}

export interface Expense {
    id: number
    expensable_type: string
    expensable_id: string
    date: string
    currency_code: string
    amount: number | null
    amount_local: number | null
    created_at: string
    note: string | null
    redacted: boolean
}
