import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { AppSidebarHeader } from '@/components/app-sidebar-header';
import { type BreadcrumbItem } from '@/types';
import { ReactNode, type PropsWithChildren } from 'react';

export default function AppSidebarLayout({ 
    children,
    breadcrumbs = [], 
    controls
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[], controls?: ReactNode}>) {
    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent variant="sidebar" className="overflow-x-hidden print:overflow-visible">
                <AppSidebarHeader breadcrumbs={breadcrumbs} controls={controls} />
                {children}
            </AppContent>
        </AppShell>
    );
}
