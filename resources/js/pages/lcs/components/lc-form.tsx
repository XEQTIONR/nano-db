import { InputCalendar } from '@/components/ui/input-calendar';
import { Textarea } from "@/components/ui/textarea"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Separator } from '@/components/ui/separator';
import { useState } from 'react';
import { LetterOfCredit } from '@/types';
import InputError from '@/components/input-error';
import {
  CardAction,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';

export default function LetterOfCreditForm ({ initialValue } : { 
    initialValue?: LetterOfCredit,
    // onChange?: (data: LetterOfCredit) => void 
}) {

    const [formData, setFormData] = useState<LetterOfCredit>(() => initialValue ?? {
        lc_num: "",
        date_issued: undefined,
        date_expiry: undefined,
        applicant: "",
        beneficiary: "",
        port_depart: "",
        port_arrive: "",
        currency_code: "",
        rate: 0,
        value: 0,
        foreign_expense: 0,
        domestic_expense: 0,
        notes: "",
    })

    const [errors, setErrors] = useState({})

    const sendData = () => {
        //setFormData(data)
        // if (onChange) {
        //     onChange(data)
        // }
        console.log('sendData:', formData)
        let errs = {...errors}
        if (typeof formData.lc_num != 'string' || formData.lc_num.length === 0) {
            errs = {...errs, lc_num: "LC number is required"}

        } 

        if (formData.date_issued === undefined) {
            errs = {...errs, date_issued: "The issue date is required"}
        }

        if (formData.date_expiry === undefined) {
            errs = {...errs, date_expiry: "The expiry date is required"}
        }

        if (typeof formData.applicant != 'string' || formData.applicant.length === 0) {
            errs = {...errs, applicant: "Applicant information is required"}
        }

        if (typeof formData.beneficiary != 'string' || formData.beneficiary.length === 0) {
            errs = {...errs, beneficiary: "Beneficiary information is required"}
        }

        if (typeof formData.port_depart != 'string' || formData.port_depart.length === 0) {
            errs = {...errs, port_depart: "Departing port is required"}
        }

        if (typeof formData.port_arrive != 'string' || formData.port_arrive.length === 0) {
            errs = {...errs, port_arrive: "Port of arrival is required"}
        }

        if (typeof formData.currency_code != 'string' || formData.currency_code.length === 0) {
            errs = {...errs, currency_code: "The currency code is required"}
        } else if (formData.currency_code.length !== 3) {
            errs = {...errs, currency_code: "The currency code must be 3 letters long"}
        }

        if (typeof formData.rate != 'number' || formData.rate <= 0) {
            errs = {...errs, rate: "The exchange rate must be a number greater than 0"}
        }

        if (typeof formData.value != 'number' || formData.value <= 0) {
            errs = {...errs, value: "The LC value must be a number greater than 0"}
        }

        if (typeof formData.foreign_expense != 'number') {
            errs = {...errs, foreign_expense: "The foreign expense must be a number"}
        } else if (formData.foreign_expense < 0) {
            errs = {...errs, foreign_expense: "The foreign expense cannot be less than 0"}
        }

        if (typeof formData.domestic_expense != 'number') {
            errs = {...errs, domestic_expense: "The domestic expense must be a number"}
        } else if (formData.domestic_expense < 0) {
            errs = {...errs, domestic_expense: "The foreign expense cannot be less than 0"}
        }

        if (typeof formData.notes != 'string') {
            errs = {...errs, notes: "Note must be a string"}
        }

        setErrors(errs)
    }

    return (<>
        <CardHeader>
            <CardTitle>Letter of Credit Information</CardTitle>
            <CardDescription>
                Enter details about your new letter of credit
            </CardDescription>
            <CardAction>
                <Button
                    onClick={sendData} 
                    variant="secondary"
                >
                    Next Step
                    <ChevronRight />
                </Button>
            </CardAction>
        </CardHeader>
        <CardContent>
            <form>
                <div className="flex flex-col gap-6">
                    <div className="grid gap-2">
                        <Label className={cn(errors?.lc_num && "text-red-400")} htmlFor="lc_num">Letter of Credit Number</Label>
                        <Input
                            className={cn(errors?.lc_num && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                            onChange={(event) => {
                                const v = {...errors}
                                delete v.lc_num
                                setErrors({ ...v })
                                setFormData({...formData, lc_num: event.target.value})
                            }}
                            value={formData.lc_num}
                            id="lc_num"
                            placeholder="F20 | Document Credit Number"
                            required
                        />
                        {errors?.lc_num && <InputError message={errors.lc_num} />}
                    </div>
                    <div className="flex flex-col md:flex-row justify-between w-full gap-5">
                        <div className="flex flex-col gap-2 md:w-1/2">
                            <Label className={cn(errors?.date_issued && "text-red-400")} htmlFor="date_issued">Issue Date</Label>
                            <InputCalendar
                                className={cn(errors?.date_issued && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                                date={formData.date_issued}
                                onChange={(date) => {
                                    const v = {...errors}
                                    delete v.date_issued
                                    setErrors({ ...v })
                                    setFormData({...formData, date_issued: date})
                                }}
                                id="date_issued"
                                placeholder="F31C | Select date issued"
                            />
                            {errors?.date_issued && <InputError message={errors.date_issued} />}
                        </div>
                    
                        <div className="flex flex-col gap-2 md:w-1/2">
                            <Label className={cn(errors?.date_expiry && "text-red-400")} htmlFor="date_expiry">Expiry Date</Label>
                            <InputCalendar
                                className={cn(errors?.date_expiry && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                                date={formData.date_expiry}
                                onChange={(date) => {
                                    const v = {...errors}
                                    delete v.date_expiry
                                    setErrors({ ...v })
                                    setFormData({...formData, date_expiry: date})
                                }} 
                                id="date_expiry"  
                                placeholder="F31D | Select expiry date"
                            />
                            {errors?.date_expiry && <InputError message={errors.date_expiry} />}
                        </div>
                    </div>
                    <div className="flex flex-col gap-2">
                        <Label className={cn(errors?.applicant && "text-red-400")} htmlFor="applicant">Applicant</Label>
                        <Textarea
                            className={cn(errors?.applicant && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                            value={formData.applicant} 
                            onChange={(event) => {
                                const v = {...errors}
                                delete v.applicant
                                setErrors({ ...v })
                                setFormData({...formData, applicant: event.target.value})
                            }}
                            placeholder='F50 | Applicant name and address'
                            id="applicant"
                        />
                        {errors?.applicant && <InputError message={errors.applicant} />}
                    </div>
                    <div className="flex flex-col gap-2">
                        <Label className={cn(errors?.beneficiary && "text-red-400")} htmlFor="beneficiary">Beneficiary</Label>
                        <Textarea
                            className={cn(errors?.beneficiary && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                            onChange={(event) => {
                                const v = {...errors}
                                delete v.beneficiary
                                setErrors({ ...v })
                                setFormData({...formData, beneficiary: event.target.value})
                            }}
                            value={formData.beneficiary} 
                            placeholder='F59 | Beneficiary name and address'
                            id="beneficiary"
                        />
                        {errors?.beneficiary && <InputError message={errors.beneficiary} />}
                    </div>
                    <Separator />
                    <div className="flex flex-col md:flex-row justify-start w-full gap-5">
                        <div className="grid gap-2 w-full md:w-1/2">
                            <Label className={cn(errors?.port_depart && "text-red-400")} htmlFor="port_depart">Departing port</Label>
                            <Input
                                className={cn(errors?.port_depart && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                                onChange={(event) => {
                                    const v = {...errors}
                                    delete v.port_depart
                                    setErrors({ ...v })
                                    setFormData({...formData, port_depart: event.target.value})
                                }}
                                value={formData.port_depart}
                                id="port_depart"
                                placeholder="F44E"
                                required
                            />
                            {errors?.port_depart && <InputError message={errors.port_depart} />}
                        </div>
                        <div className="grid gap-2 w-full md:w-1/2">
                            <Label className={cn(errors?.port_arrive && "text-red-400")} htmlFor="port_arrive">Arriving port</Label>
                            <Input
                                className={cn(errors?.port_arrive && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                                onChange={(event) => {
                                    const v = {...errors}
                                    delete v.port_arrive
                                    setErrors({ ...v })
                                    setFormData({...formData, port_arrive: event.target.value})
                                }}
                                value={formData.port_arrive}
                                id="port_arrive"
                                placeholder="F44F"
                                required
                            />
                            {errors?.port_arrive && <InputError message={errors.port_arrive} />}
                        </div>
                    </div>
                    <div className="flex flex-col md:flex-row justify-start w-full gap-5">
                        <div className="flex flex-col md:flex-row gap-5 w-full md:w-3/6">
                            <div className="flex flex-col gap-2">
                                <Label className={cn(errors?.currency_code && "text-red-400")} htmlFor="currency_code">Currency Code</Label>
                                <Input
                                    className={cn(errors?.currency_code && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                                    onChange={(event) => {
                                        const v = {...errors}
                                        delete v.currency_code
                                        setErrors({ ...v })
                                        setFormData({...formData, currency_code: event.target.value})
                                    }}
                                    value={formData.currency_code}
                                    id="currency_code"
                                    placeholder="F32B"
                                    required
                                />
                                {errors?.currency_code && <InputError message={errors.currency_code} />}
                            </div>
                            <div className="flex flex-col gap-2">
                                <Label className={cn(errors?.rate && "text-red-400")} htmlFor="exchange_rate">Rate</Label>
                                <Input
                                    className={cn(
                                        errors?.rate && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                        "text-right"
                                    )}
                                    onChange={(event) => {
                                        const v = {...errors}
                                        delete v.rate
                                        setErrors({ ...v })
                                        setFormData({
                                            ...formData,
                                            rate: (isNaN(parseFloat(event.target.value))) ? 0 : parseFloat(event.target.value)
                                        })
                                    }}
                                    type="number"
                                    value={formData.rate}
                                    placeholder="0.00"
                                    id="exchange_rate"
                                    required
                                />
                                {errors?.rate && <InputError message={errors.rate} />}
                            </div>
                        </div>
                        <div className="flex flex-col gap-2 w-full md:w-3/6">
                            <Label className={cn(errors?.value && "text-red-400")} htmlFor="value">Value (foreign currency)</Label>
                            <Input
                                className={cn(
                                    errors?.value && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                    "text-right"
                                )}
                                onChange={(event) => {
                                    const v = {...errors}
                                    delete v.value
                                    setErrors({ ...v })
                                    setFormData({
                                        ...formData,
                                        value: (isNaN(parseFloat(event.target.value))) ? 0 : parseFloat(event.target.value)
                                    })
                                }}
                                type="number"
                                value={formData.value}
                                placeholder="0.00"
                                id="value"
                                required
                            />
                            {errors?.value && <InputError message={errors.value} />}
                        </div>
                    </div>
                    <div className="w-full flex flex-col md:flex-row justify-start gap-5">
                        <div className="md:grid gap-2 w-1/2 hidden">
                        </div>
                        <div className="grid gap-2 w-full md:w-1/2">
                            <Label htmlFor="local_value">Amount in local currency</Label>
                            <Input
                                value={formData.rate * formData.value}
                                className="text-right"
                                disabled={true}
                                id="local_value"
                                required
                            />
                        </div>
                    </div>
                    <div className="flex justify-between w-full gap-5">
                        <div className="flex flex-col gap-2 w-1/2">
                            <Label className={cn(errors?.foreign_expense && "text-red-400")} htmlFor="foreign_expense">Foreign Expenses Paid</Label>
                            <Input
                                className={cn(
                                    errors?.foreign_expense && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                    "text-right"
                                )}
                                onChange={(event) => {
                                    const v = {...errors}
                                    delete v.foreign_expense
                                    setErrors({ ...v })
                                    setFormData({
                                        ...formData,
                                        foreign_expense: (isNaN(parseFloat(event.target.value))) ? 0 : parseFloat(event.target.value)
                                    })
                                }}
                                value={formData.foreign_expense}
                                id="foreign_expense"
                                placeholder="0.00"
                                required
                            />
                            {errors?.foreign_expense && <InputError message={errors.foreign_expense} />}
                        </div>
                        <div className="flex flex-col gap-2 w-1/2">
                            <Label className={cn(errors?.domestic_expense && "text-red-400")} htmlFor="domestic_expense">Domestic Expenses Paid</Label>
                            <Input
                                className={cn(
                                    errors?.domestic_expense && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50",
                                    "text-right"
                                )}
                                onChange={(event) => {
                                    const v = {...errors}
                                    delete v.domestic_expense
                                    setErrors({ ...v })
                                    setFormData({
                                        ...formData,
                                        domestic_expense: (isNaN(parseFloat(event.target.value))) ? 0 : parseFloat(event.target.value)
                                    })
                                }}
                                value={formData.domestic_expense}
                                id="domestic_expense"
                                placeholder="0.00"
                                required
                            />
                            {errors?.domestic_expense && <InputError message={errors.domestic_expense} />}
                        </div>
                    </div>
                    <div className="flex flex-col gap-2">
                        <Label className={cn(errors?.notes && "text-red-400")} htmlFor="notes">Notes</Label>
                        <Textarea
                            className={cn(errors?.notes && "border-red-400 focus-visible:border-red-400 focus-visible:ring-red-400/50")}
                            onChange={(event) => setFormData({
                                ...formData,
                                notes: event.target.value
                            })} 
                            value={formData.notes} 
                            id="notes" 
                        />
                        {errors?.notes && <InputError message={errors.notes} />}
                    </div>
                </div>
            </form>
        </CardContent>
    </>)
}