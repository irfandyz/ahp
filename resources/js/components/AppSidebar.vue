<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type SidebarSection } from '@/types';
import { Link } from '@inertiajs/vue3';
import { 
    BookOpen, 
    Folder, 
    LayoutGrid, 
    Truck, 
    Users, 
    Tag, 
    Building2, 
    BarChart3, 
    Plus,
    Settings,
    FileText,
    MapPin,
    UserCheck
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const sidebarSections: SidebarSection[] = [
    {
        title: 'Dashboard',
        items: [
            {
                title: 'Overview',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ],
    },
    {
        title: 'Expeditions',
        items: [
            {
                title: 'All Expeditions',
                href: '/expeditions',
                icon: Truck,
            },
            {
                title: 'Create New',
                href: '/expeditions/create',
                icon: Plus,
            },
            {
                title: 'Map View',
                href: '/expeditions/map',
                icon: MapPin,
            },
        ],
    },
    {
        title: 'Master Data',
        items: [
            {
                title: 'Vendors',
                href: '/vendors',
                icon: Users,
            },
            {
                title: 'Customers',
                href: '/customers',
                icon: Users,
            },
            {
                title: 'Routes',
                href: '/routes',
                icon: Tag,
            },
            {
                title: 'Fleet Types',
                href: '/fleet-types',
                icon: Truck,
            },
            {
                title: 'Drivers',
                href: '/drivers',
                icon: UserCheck,
            },
            {
                title: 'Fleets',
                href: '/fleets',
                icon: Truck,
            },
            {
                title: 'Industry Sectors',
                href: '/industry-sectors',
                icon: Building2,
            },
        ],
    },
    {
        title: 'Reports & Analytics',
        items: [
            {
                title: 'Dashboard Reports',
                href: '/reports/dashboard',
                icon: BarChart3,
            },
            {
                title: 'Financial Reports',
                href: '/reports/financial',
                icon: FileText,
            },
            {
                title: 'Export Data',
                href: '/reports/export',
                icon: Folder,
            },
        ],
    },
    {
        title: 'Settings',
        items: [
            {
                title: 'General Settings',
                href: '/settings/general',
                icon: Settings,
            },
            {
                title: 'User Management',
                href: '/settings/users',
                icon: Users,
            },
        ],
    },
];

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :sections="sidebarSections" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
