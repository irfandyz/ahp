<script setup lang="ts">
import { type SidebarSection } from '@/types';
import SidebarSectionItem from './SidebarSectionItem.vue';

defineProps<{
    sections: SidebarSection[];
}>();
</script>

<template>
    <div class="space-y-6">
        <SidebarSectionItem 
            v-for="section in sections" 
            :key="section.title"
            :title="section.title"
            :items="section.items"
        />
    </div>
</template>
