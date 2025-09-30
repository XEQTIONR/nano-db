import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/components/ui/alert-dialog"
import { Button } from '@/components/ui/button'
import { cn, currencyFormat } from "@/lib/utils";
import { router } from "@inertiajs/react";
import { Minus, Plus, Check, X } from "lucide-react"
import {
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerFooter,
  DrawerHeader,
  DrawerTitle,
} from "@/components/ui/drawer"
import { Input } from '@/components/ui/input';
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"

import { BankAccount, Order } from '@/types'

import { useState } from 'react';

type PaymentType = 'unknown'|'cash'|'deposit'|'check'

export default function CreateForm({ accounts, order } : { 
    accounts: { 
        data: BankAccount[]
    },
    order: { 
        data: Order
    }, 
}) {

    const [prevValue, setPreviousValue] = useState<number>(0)
    const [newPaymentAmount, setNewPaymentAmount] = useState <string|number>(0)
    const [editPaymentAmount, setEditPaymentAmount] = useState(false)
    const [paymentType, setPaymentType] = useState<PaymentType|undefined>('cash')
    const [paymentAccount, setPaymentAccount] = useState<string|null>(null)

    const data = order.data

    const modifyPaymentAmount = (down: boolean, add: boolean = true) => {
        if (down && !window.timer) {
            window.timer = setInterval(() => {
                if(down && window.timer) {
                    setNewPaymentAmount((amt) => (add ? (amt + 10) : (amt - 10)))
                }
            }, 100)
        } else if (window.timer) {
            clearInterval(window.timer)
            delete window.timer
        }
    }

    return (
        <DrawerContent>
            <div className="mx-auto w-full max-w-md">
                <DrawerHeader>
                    <DrawerTitle className="text-center">Make a payment</DrawerTitle>
                    <DrawerDescription className="text-center">Order # {order.data.order_num} </DrawerDescription>
                </DrawerHeader>
                <div className="px-4 pb-0 pt-8">
                    <div className="flex flex-col justify-center gap-2">
                        <div className="flex justify-between space-x-2 pb-2">
                            <div>Grand Total</div>
                            <div className="text-right tracking-wide">
                            {currencyFormat('BDT', order.data.grand_total ?? 0)}
                            </div>
                        </div>
                        <div className="flex justify-between space-x-2 pb-2">
                            <div>Payments</div>
                            <div className="text-right tracking-wide">
                                {currencyFormat('BDT', order.data.payments_total ?? 0)}
                            </div>
                        </div>
                        { typeof newPaymentAmount == "number" && newPaymentAmount > 0 && (<div className="flex justify-between space-x-2 pb-2">
                            <div>New Payment</div>
                            <div className="text-right tracking-wide">
                                {currencyFormat('BDT', newPaymentAmount)}
                            </div>
                        </div>)}
                        <div className="flex justify-between space-x-2 border-t-2 pt-3 mb-6">
                            <div>Balance</div>
                            <div className="text-right tracking-wide">{
                                typeof newPaymentAmount == "number" && typeof order.data.balance == "number"
                                ? (currencyFormat('BDT',order.data.balance - newPaymentAmount))
                                : currencyFormat('BDT', order.data.balance ?? 0)
                            }
                            </div>
                        </div>
                        <div>Pay</div>
                        <div className="flex items-center justify-between h-20">
                            <Button
                                variant="outline"
                                size="icon"
                                className={cn("h-8 w-8 shrink-0 rounded-full select-none mb-6")}
                                disabled={(typeof newPaymentAmount == "string")}
                                onClick={() =>{ 
                                    if (editPaymentAmount) {
                                        setNewPaymentAmount(prevValue)
                                        setEditPaymentAmount(false)
                                    } else {
                                        if (typeof newPaymentAmount == "number") {
                                            setNewPaymentAmount((amt) => {
                                                if (typeof amt == "number") {
                                                    if (typeof data.balance == "number" && (amt - 10) < 0) {
                                                        return 0
                                                    }
                                                    return amt - 10
                                                }
                                                return newPaymentAmount
                                            })
                                        }
                                    }
                                }}
                                onTouchStart={() => !editPaymentAmount && modifyPaymentAmount(true, false)}
                                onMouseDown={() => !editPaymentAmount && modifyPaymentAmount(true, false)}
                                onMouseUp={() => !editPaymentAmount && modifyPaymentAmount(false)}
                                onTouchEnd={() => !editPaymentAmount && modifyPaymentAmount(false)}
                            >
                                {editPaymentAmount ? <X /> : <Minus />}
                                <span className="sr-only">Decrease</span>
                            </Button>
                            <div className="flex flex-col gap-2 items-center">
                                {
                                    editPaymentAmount ? (
                                        
                                        <div className="w-full flex justify-center">
                                            <Input
                                                min={0}
                                                max={data.balance}
                                                type="number"
                                                onChange={({target}) => {
                                                    if (isNaN(parseFloat(target.value))) {
                                                        setNewPaymentAmount(0)
                                                    }
                                                    setNewPaymentAmount(
                                                        data.balance 
                                                        ? Math.min(parseFloat(target.value), data.balance)
                                                        : parseFloat(target.value)
                                                    )
                                                }} 
                                                value={newPaymentAmount} 
                                                className="md:text-4xl w-full h-16 text-center font-bold tracking-tighter" 
                                            />
                                        </div>
                                    ) : (
                                        <div onClick={() => {
                                            setPreviousValue(newPaymentAmount)
                                            setEditPaymentAmount(true)
                                        }} className="text-4xl font-bold tracking-tight">
                                            {
                                                typeof newPaymentAmount == "number"
                                                ? currencyFormat('BDT', newPaymentAmount)
                                                : currencyFormat('BDT', parseFloat(newPaymentAmount))
                                            }
                                        </div>
                                    )
                                }
                                
                                <div className="text-muted-foreground text-[0.70rem] uppercase">
                                    Taka
                                </div>
                            </div>
                            <Button
                                variant="outline"
                                size="icon"
                                className={cn("h-8 w-8 shrink-0 rounded-full select-none mb-6")}
                                disabled={(typeof newPaymentAmount == "string")}
                                onClick={() =>{
                                    if (editPaymentAmount) {
                                        setEditPaymentAmount(false)
                                    } else {
                                        if (typeof newPaymentAmount == "number") {
                                            setNewPaymentAmount((amt) => {
                                                if (typeof amt == "number") {
                                                    if (typeof data.balance == "number" && (amt + 10) > data.balance) {
                                                        return data.balance
                                                    }
                                                    return amt + 10
                                                }
                                                return newPaymentAmount
                                            })
                                        }
                                    }
                                    
                                }}
                                onTouchStart={() => !editPaymentAmount && modifyPaymentAmount(true)}
                                onMouseDown={() => !editPaymentAmount && modifyPaymentAmount(true)}
                                onTouchEnd={() => !editPaymentAmount && modifyPaymentAmount(false)}
                                onMouseUp={() => !editPaymentAmount && modifyPaymentAmount(false)}
                            >
                                {
                                    editPaymentAmount ? <Check /> : <Plus />
                                }
                                
                                <span className="sr-only">Increase</span>
                            </Button>
                        </div>
                        <div className="my-4">
                            <Select value={paymentType} onValueChange={(value) => {
                                setPaymentType(value)
                            }}>
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder="Select payment type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                    <SelectLabel>Payment types</SelectLabel>
                                        <SelectItem value="unknown">Unknown</SelectItem>
                                        <SelectItem value="cash">Cash</SelectItem>
                                        <SelectItem value="deposit">Bank Deposit</SelectItem>
                                        <SelectItem value="check">Check</SelectItem>
                                        { order.data.commission === 0 
                                            && <SelectItem value="commission">Commission</SelectItem>
                                        }
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                        {paymentType == 'deposit' && (<div className="mb-4">
                            <Select onValueChange={(value) => {
                                setPaymentAccount(value)
                            }}>
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder="Select a bank account" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup>
                                    <SelectLabel>Payment types</SelectLabel>
                                    {
                                        accounts.data.map((account: BankAccount) => (
                                            <SelectItem value={account.id.toString()}>{account.bank_name} {account.account_number}</SelectItem>
                                        ))
                                    }
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>)}
                    </div>
                </div>
                <DrawerFooter>
                    <AlertDialog>
                        <AlertDialogTrigger disabled={
                                    editPaymentAmount 
                                    || (typeof newPaymentAmount == 'number'
                                        && (newPaymentAmount <= 0 
                                            ||  ( !!order.data.balance && newPaymentAmount > order.data.balance)
                                        ))
                                    || isNaN(newPaymentAmount)
                                    || (paymentType == 'deposit' && paymentAccount == null)
                                }>
                            <Button
                                className="w-full"
                                type="button"
                                disabled={
                                    editPaymentAmount 
                                    || (typeof newPaymentAmount == 'number'
                                        && (newPaymentAmount <= 0 
                                            ||  ( !!order.data.balance && newPaymentAmount > order.data.balance)
                                        ))
                                    || isNaN(newPaymentAmount)
                                    || (paymentType == 'deposit' && paymentAccount == null)
                                } 
                                
                            >
                                Make Payment
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Create new payment?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Are you sure you want to pay <b className="dark:text-neutral-50">{
                                        typeof newPaymentAmount == 'string' 
                                            ? currencyFormat('BDT', parseFloat(newPaymentAmount))
                                            : currencyFormat('BDT', newPaymentAmount)
                                    }</b> towards Order <b className="dark:text-neutral-50">#{order.data.order_num}</b> new balance will be <b className="dark:text-neutral-50">{
                                        typeof newPaymentAmount == "number" && typeof order.data.balance == "number"
                                            ? (currencyFormat('BDT',order.data.balance - newPaymentAmount))
                                            : currencyFormat('BDT', order.data.balance ?? 0)
                                    }</b>?
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>
                                    Cancel
                                </AlertDialogCancel>
                                <AlertDialogAction onClick={() => {

                                    const data: {
                                        order_num: number
                                        payment_amount: number
                                        payment_type: PaymentType
                                        account: number | null
                                    } = {
                                        order_num: order.data.order_num,
                                        payment_amount: typeof newPaymentAmount === 'string' ? parseFloat(newPaymentAmount) : newPaymentAmount,
                                        payment_type: paymentType ?? 'unknown',
                                        account: null
                                    }
                                    
                                    if (paymentType == 'deposit') {
                                        data.account = paymentAccount ? parseInt(paymentAccount) : null
                                    }

                                    setNewPaymentAmount(0)
                                    setPaymentType(undefined)
                                    setPaymentAccount(null)
                                    
                                    router.post(route('payments.store'), data)
                                }}>
                                    Confirm
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                    
                    <DrawerClose asChild>
                        <Button onClick={() => setNewPaymentAmount(0)} variant="outline">Cancel</Button>
                    </DrawerClose>
                </DrawerFooter>
            </div>
        </DrawerContent>
    )
}