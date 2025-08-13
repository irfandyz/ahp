<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronDown, ChevronRight } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    title: string;
    items: NavItem[];
    collapsible?: boolean;
    defaultOpen?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    collapsible: false,
    defaultOpen: true,
});

const page = usePage();
const isOpen = ref(props.defaultOpen);

const toggleSection = () => {
    if (props.collapsible) {
        isOpen.value = !isOpen.value;
    }
};
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel 
            class="text-xs font-semibold text-muted-foreground uppercase tracking-wider cursor-pointer hover:text-foreground transition-colors"
            @click="toggleSection"
        >
            <div class="flex items-center justify-between w-full">
                <span>{{ title }}</span>
                <component 
                    v-if="collapsible" 
                    :is="isOpen ? ChevronDown : ChevronRight" 
                    class="h-3 w-3 transition-transform"
                    :class="{ 'rotate-0': isOpen, '-rotate-90': !isOpen }"
                />
            </div>
        </SidebarGroupLabel>
        
        <SidebarMenu v-show="isOpen" class="mt-2">
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton 
                    as-child 
                    :is-active="item.href === page.url" 
                    :tooltip="item.title"
                    class="w-full justify-start"
                >
                    <Link :href="item.href" class="flex items-center gap-3 w-full">
                        <component :is="item.icon" class="h-4 w-4" />
                        <span class="text-sm">{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
