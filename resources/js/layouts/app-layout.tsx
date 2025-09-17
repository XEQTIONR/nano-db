import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';
import { Toaster } from "@/components/ui/sonner"
interface AppLayoutProps {
    children: ReactNode
    breadcrumbs?: BreadcrumbItem[]
    controls?: ReactNode
}

export default ({ children, breadcrumbs, controls, ...props }: AppLayoutProps) => (
    <AppLayoutTemplate breadcrumbs={breadcrumbs} controls={controls} {...props}>
        {children}
        <Toaster className="print:hidden" duration={8000} position='top-center' />
    </AppLayoutTemplate>
);
