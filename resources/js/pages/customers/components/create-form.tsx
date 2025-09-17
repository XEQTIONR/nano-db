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
import { router } from "@inertiajs/react";
import {
  DrawerClose,
  DrawerContent,
  DrawerDescription,
  DrawerFooter,
  DrawerHeader,
  DrawerTitle,
} from "@/components/ui/drawer"
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useState } from 'react';
import { cn } from '@/lib/utils';
import InputError from '@/components/input-error';

interface NewCustomerFields {
    name: string
    address: string
    phone: string
    notes: string
}

const emptyFields: NewCustomerFields = {
        name: "",
        address: "",
        phone: "",
        notes: ""
}
export default function CreateForm() {
    const [data, setData] = useState<NewCustomerFields>({...emptyFields})

    const [errors, setErrors] = useState<NewCustomerFields>({...emptyFields})

    const validate = () => {
        const err = {...emptyFields}
        let count = 0

        if (data.name.length === 0) {
            err.name = "The customer's name is required."
            count++
        } 
        
        if (data.address.length === 0) {
            err.address = "The customer's address is required."
            count++
        } 
        
        if (data.phone.length === 0) {
            err.phone = "The customer's phone number is required."
            count++
        } 

        setErrors({...err})
        
        return count
    }

    return (
        <DrawerContent>
            <form 
                className="mx-auto w-full max-w-md"
                onSubmit={(e) => {
                    e.preventDefault()
                    const k = validate()
                    if (k === 0) {
                        router.post(route('customers.store'), { ...data })
                    }
                }}
            >
                <DrawerHeader>
                    <DrawerTitle className="text-center">Add New Customer</DrawerTitle>
                    <DrawerDescription className="text-center">Create a new customer</DrawerDescription>
                </DrawerHeader>
                <div className="px-4 pb-8 flex flex-col gap-6">
                    <div className="flex flex-col gap-3">
                        <Label>Name</Label>
                        <Input
                            className={cn( errors.name.length && "border-red-400")} 
                            value={data.name}
                            onChange={({target}) => {
                                setData({ ... data, name: target.value})
                                if(target.value.length) {
                                    setErrors({...errors, name: ""})
                                }
                            }} 
                            placeholder="Customer's name" 
                        />
                        {errors.name.length > 0 && <InputError message={errors.name} />}
                    </div>
                    <div className="flex flex-col gap-3">
                        <Label>Address</Label>
                        <Input
                            className={cn( errors.address.length && "border-red-400")} 
                            value={data.address}
                            onChange={({target}) => {
                                setData({ ... data, address: target.value})
                                if(target.value.length) {
                                    setErrors({...errors, address: ""})
                                }
                            }} 
                            placeholder="Customer's address" 
                        />
                        {errors.address.length > 0 && <InputError message={errors.address} />}
                    </div>
                    <div className="flex flex-col gap-3">
                        <Label>Phone #</Label>
                        <Input
                            className={cn( errors.phone.length && "border-red-400")} 
                            value={data.phone}
                            onChange={({target}) => {
                                setData({ ... data, phone: target.value})
                                if(target.value.length) {
                                    setErrors({...errors, phone: ""})
                                }
                            }} 
                            placeholder="Customer's phone number" 
                        />
                        {errors.phone.length > 0 && <InputError message={errors.phone} />}
                    </div>
                    <div className="flex flex-col gap-3">
                        <Label>Notes</Label>
                        <Input
                            className={cn( errors.notes.length && "border-red-400")} 
                            value={data.notes}
                            onChange={({target}) => {
                                setData({ ... data, notes: target.value})
                                if(target.value.length) {
                                    setErrors({...errors, notes: ""})
                                }
                            }} 
                            placeholder="Notes" 
                        />
                        {errors.notes.length > 0 && <InputError message={errors.notes} />}
                    </div>
                </div>
                <DrawerFooter>
                    <AlertDialog>
                        <AlertDialogTrigger asChild>
                            <Button type="button">Submit</Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                            <AlertDialogTitle>Create new customer?</AlertDialogTitle>
                            <AlertDialogDescription>
                                Are you sure you want to create this new customer?
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>
                                Cancel
                            </AlertDialogCancel>
                            <AlertDialogAction onClick={() => {
                                const k = validate()
                                if (k === 0) {
                                    router.post(route('customers.store'), { ...data })
                                }
                            }}>
                                Confirm
                            </AlertDialogAction>
                        </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                    
                    <DrawerClose asChild>
                        <Button onClick={() =>{
                            setErrors({...emptyFields})
                            setData({...emptyFields})
                        }} variant="outline">Cancel</Button>
                    </DrawerClose>
                </DrawerFooter>
            </form>
        </DrawerContent>
    )
}