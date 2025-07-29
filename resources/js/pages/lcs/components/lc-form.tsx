import { InputCalendar } from '@/components/ui/input-calendar';
import { Textarea } from "@/components/ui/textarea"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Separator } from '@/components/ui/separator';

export default function LetterOfCreditForm () {
    return (<form>
        <div className="flex flex-col gap-6">
            <div className="grid gap-2">
                <Label htmlFor="lc_num">Letter of Credit Number</Label>
                <Input
                    id="lc_num"
                    placeholder="F20 | Document Credit Number"
                    required
                />
            </div>
            <div className="flex flex-col md:flex-row justify-between w-full gap-5">
                <div className="flex flex-col gap-2">
                    <Label htmlFor="date_issued">Issue Date</Label>
                    <InputCalendar id="date_issued" placeholder="F31C | Select date issued" />
                </div>
            
                <div className="grid gap-2">
                    <Label htmlFor="date_expiry">Expiry Date</Label>
                    <InputCalendar id="date_expiry"  placeholder="F31D | Select expiry date" />
                </div>
            </div>
            <div className="grid gap-2">
                <Label htmlFor="applicant">Applicant</Label>
                <Textarea placeholder='F50 | Applicant name and address' id="applicant" />
            </div>
            <div className="grid gap-2">
                <Label htmlFor="beneficiary">Beneficiary</Label>
                <Textarea placeholder='F59 | Beneficiary name and address' id="beneficiary" />
            </div>
            <Separator />
            <div className="flex flex-col md:flex-row justify-start w-full gap-5">
                <div className="grid gap-2 w-full md:w-1/2">
                    <Label htmlFor="port_depart">Departing port</Label>
                    <Input
                        id="port_depart"
                        placeholder="F44E"
                        required
                    />
                </div>
                <div className="grid gap-2 w-full md:w-1/2">
                    <Label htmlFor="port_arrive">Arriving port</Label>
                    <Input
                        id="port_arrive"
                        placeholder="F44F"
                        required
                    />
                </div>
            </div>
            <div className="flex flex-col md:flex-row justify-start w-full gap-5">
                <div className="flex flex-col md:flex-row gap-5 w-full md:w-3/6">
                    <div className="grid gap-2">
                        <Label htmlFor="currency_code">Currency Code</Label>
                        <Input
                            id="currency_code"
                            placeholder="F32B"
                            required
                        />
                    </div>
                    <div className="grid gap-2">
                        <Label className="not-only:" htmlFor="exchange_rate">Rate</Label>
                        <Input
                            className="text-right"
                            placeholder="0.00"
                            id="exchange_rate"
                            required
                        />
                    </div>
                </div>
                <div className="grid gap-2 w-full md:w-3/6">
                    <Label htmlFor="exchange_rate">Value (foreign currency)</Label>
                    <Input
                        className="text-right"
                        placeholder="0.00"
                        id="exchange_rate"
                        required
                    />
                </div>
            </div>
            <div className="w-full flex flex-col md:flex-row justify-start gap-5">
                <div className="md:grid gap-2 w-1/2 hidden">
                </div>
                <div className="grid gap-2 w-full md:w-1/2">
                    <Label htmlFor="exchange_rate">Amount in local currency</Label>
                    <Input
                        className="text-right"
                        disabled={true}
                        id="exchange_rate"
                        value={5000.0}
                        required
                    />
                </div>
            </div>
            <div className="flex justify-between w-full gap-5">
                <div className="grid gap-2 w-1/2">
                    <Label htmlFor="foreign_expense">Foreign Expenses Paid</Label>
                    <Input
                        id="foreign_expense"
                        className="text-right"
                        placeholder="0.00"
                        required
                    />
                </div>
                <div className="grid gap-2 w-1/2">
                    <Label htmlFor="domestic_expense">Domestic Expenses Paid</Label>
                    <Input
                        id="domestic_expense"
                        className="text-right"
                        placeholder="0.00"
                        required
                    />
                </div>
            </div>
            <div className="grid gap-2">
                <Label htmlFor="notes">Notes</Label>
                <Textarea id="notes" />
            </div>
        </div>
    </form>)
}