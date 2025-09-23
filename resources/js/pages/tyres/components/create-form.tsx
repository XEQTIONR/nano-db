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
import { Tyre } from '@/types';
import { AlertDialog } from '@radix-ui/react-alert-dialog';
import { AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '@/components/ui/alert-dialog';

interface TyreFields {
    brand: string
    size: string
    pattern: string
    lisi: string
}

const emptyFields: TyreFields = {
        brand: "",
        size: "",
        pattern: "",
        lisi: ""
}
export default function CreateForm({ edit } : {edit?: {
    data: Tyre
}}) {
    const [data, setData] = useState<TyreFields | Tyre>(() => edit ? edit.data : {...emptyFields})

    const [errors, setErrors] = useState<TyreFields>({...emptyFields})

    const validate = () => {
        const err = {...emptyFields}
        let count = 0

        if (data.brand.length === 0) {
            err.brand = "The tyre brand is required."
            count++
        } 
        
        if (data.size.length === 0) {
            err.size = "The tyre size is required."
            count++
        } 
        
        if (data.pattern.length === 0) {
            err.pattern = "The tyre pattern is required."
            count++
        } 
        
        if (data.lisi.length === 0) {
            err.lisi = "The tyre Li/Si is required."
            count++
        }

        setErrors({...err})
        
        return count
    }

    return (
        <DrawerContent>
            <form className="mx-auto w-full max-w-md">
                <DrawerHeader>
                    <DrawerTitle className="text-center">
                        {
                            edit 
                                ? "Edit tyre ID: " + edit.data.id
                                : "Add new tyre" 
                        }
                    </DrawerTitle>
                    <DrawerDescription className="text-center">
                        {
                            edit
                                ? "Edit existing tyre"
                                : "Create a new tyre"
                        }
                    </DrawerDescription>
                </DrawerHeader>
                <div className="px-4 pb-8 flex flex-col gap-6">
                    <div className="flex flex-col gap-3">
                        <Label>Brand</Label>
                        <Input
                            className={cn( errors.brand.length && "border-red-400")} 
                            value={data.brand}
                            onChange={({target}) => {
                                setData({ ... data, brand: target.value.trim()})
                                if(target.value.length) {
                                    setErrors({...errors, brand: ""})
                                }
                            }} 
                            placeholder="Input item brand" 
                        />
                        {errors.brand.length > 0 && <InputError message={errors.brand} />}
                    </div>
                    <div className="flex flex-col gap-3">
                        <Label>Size</Label>
                        <Input
                            className={cn( errors.size.length && "border-red-400")} 
                            value={data.size}
                            onChange={({target}) => {
                                setData({ ... data, size: target.value.trim()})
                                if(target.value.length) {
                                    setErrors({...errors, size: ""})
                                }
                            }} 
                            placeholder="Input item size" 
                        />
                        {errors.size.length > 0 && <InputError message={errors.size} />}
                    </div>
                    <div className="flex flex-col gap-3">
                        <Label>Pattern</Label>
                        <Input
                            className={cn( errors.pattern.length && "border-red-400")} 
                            value={data.pattern}
                            onChange={({target}) => {
                                setData({ ... data, pattern: target.value.trim()})
                                if(target.value.length) {
                                    setErrors({...errors, pattern: ""})
                                }
                            }} 
                            placeholder="Input item pattern" 
                        />
                        {errors.pattern.length > 0 && <InputError message={errors.pattern} />}
                    </div>
                    <div className="flex flex-col gap-3">
                        <Label>Li/Si</Label>
                        <Input
                            className={cn( errors.lisi.length && "border-red-400")} 
                            value={data.lisi}
                            onChange={({target}) => {
                                setData({ ... data, lisi: target.value.trim()})
                                if(target.value.length) {
                                    setErrors({...errors, lisi: ""})
                                }
                            }} 
                            placeholder="Input item Li/Si" 
                        />
                        {errors.lisi.length > 0 && <InputError message={errors.lisi} />}
                    </div>
                </div>
                <DrawerFooter>
                    <AlertDialog>
                        <AlertDialogTrigger asChild>
                            <Button type="button">Submit</Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>
                                {
                                    edit ? `Update tyre ID:${edit.data.id} ?` : "Create new tyre?"
                                }
                                </AlertDialogTitle>
                                <AlertDialogDescription>
                                {
                                    edit ? "Are you sure you want to save changes?" : "Are you sure you want to create a new tyre?"
                                }
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>
                                    Cancel
                                </AlertDialogCancel>
                                <AlertDialogAction onClick={() => {
                                    const k = validate()
                                    const copy = { ...data }
                                    setData({...emptyFields})

                                    if (k === 0) {
                                        if (edit) {
                                            router.put(route('tyres.update', {
                                                tyre: edit.data.id
                                            }), { ...copy })
                                        } else {
                                            router.post(route('tyres.store'), { ...copy })
                                        }
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