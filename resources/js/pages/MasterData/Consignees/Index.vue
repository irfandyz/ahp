<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Plus, Search, Edit, Trash2, Eye, Building2, Mail, Phone, MapPin, FileText, X } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

interface Consignee {
    id: number;
    company: string;
    address: string;
    phone: string;
    email: string;
    expeditions_count: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    consignees: {
        data: Consignee[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    success?: string;
    error?: string;
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Master Data',
        href: '#',
    },
    {
        title: 'Consignees',
        href: '/consignees',
    },
];

const searchQuery = ref('');
const isDeleteDialogOpen = ref(false);
const consigneeToDelete = ref<Consignee | null>(null);

const deleteForm = useForm({});

const filteredConsignees = computed(() => {
    if (!searchQuery.value) return props.consignees.data;
    
    return props.consignees.data.filter(consignee =>
        consignee.company.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        consignee.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const openDeleteDialog = (consignee: Consignee) => {
    consigneeToDelete.value = consignee;
    isDeleteDialogOpen.value = true;
};

const confirmDelete = () => {
    if (consigneeToDelete.value) {
        deleteForm.delete(`/consignees/${consigneeToDelete.value.id}`, {
            onSuccess: () => {
                isDeleteDialogOpen.value = false;
                consigneeToDelete.value = null;
            },
        });
    }
};

const clearSearch = () => {
    searchQuery.value = '';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Consignees" />

        <div class="space-y-6 p-5">
            <!-- Header -->
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Consignees</h1>
                    <p class="text-muted-foreground">Manage your consignee master data</p>
                </div>
                <Link :href="route('consignees.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Consignee
                </Link>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="props.success" class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800">{{ props.success }}</p>
            </div>

            <div v-if="props.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-800">{{ props.error }}</p>
            </div>

            <!-- Search and Filters -->
            <Card>
                <CardContent>
                    <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                        <div class="flex-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <Search class="h-5 w-5 text-gray-400" />
                            </div>
                            <Input
                                v-model="searchQuery"
                                placeholder="Search consignees by company or email..."
                                class="pl-10 w-full"
                            />
                        </div>
                        <Button
                            v-if="searchQuery"
                            variant="outline"
                            @click="clearSearch"
                            class="shrink-0"
                        >
                            <X class="mr-2 h-4 w-4" />
                            Clear
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Consignees Table -->
            <Card>
                <CardHeader>
                    <div>
                        <CardTitle>Consignees List</CardTitle>
                        <CardDescription>
                            View and manage all consignee information
                        </CardDescription>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left font-medium p-4">Company</th>
                                    <th class="text-left font-medium p-4">Contact Info</th>
                                    <th class="text-left font-medium p-4">Address</th>
                                    <th class="text-left font-medium p-4">Expeditions</th>
                                    <th class="text-left font-medium p-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="consignee in filteredConsignees" :key="consignee.id" class="border-b hover:bg-gray-50 transition-colors">
                                    <td class="p-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <Building2 class="h-5 w-5 text-blue-600" />
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ consignee.company }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <Phone class="h-4 w-4 mr-2 text-gray-400" />
                                                {{ consignee.phone }}
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <Mail class="h-4 w-4 mr-2 text-gray-400" />
                                                {{ consignee.email }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <MapPin class="h-4 w-4 mr-2 text-gray-400" />
                                                {{ consignee.address }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-gray-500">
                                        <div class="flex items-center">
                                            <FileText class="h-4 w-4 mr-2 text-gray-400" />
                                            {{ consignee.expeditions_count || 0 }}
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center space-x-2">
                                            <Button variant="outline" size="sm" as-child>
                                                <Link :href="route('consignees.show', consignee.id)">
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="outline" size="sm" as-child>
                                                <Link :href="route('consignees.edit', consignee.id)">
                                                    <Edit class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                            <Button 
                                                variant="outline" 
                                                size="sm" 
                                                @click="openDeleteDialog(consignee)"
                                                class="text-red-600 hover:text-red-700"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="filteredConsignees.length === 0">
                                    <td colspan="5" class="p-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center space-y-2">
                                            <Building2 class="h-12 w-12 text-gray-300" />
                                            <p class="text-lg font-medium">No consignees found</p>
                                            <p class="text-sm">Create your first consignee to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:open="isDeleteDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Consignee</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete "{{ consigneeToDelete?.company }}"? This action cannot be undone.
                        <span v-if="consigneeToDelete?.expeditions_count && consigneeToDelete.expeditions_count > 0" class="block mt-2 text-red-600">
                            This consignee has {{ consigneeToDelete.expeditions_count }} expeditions and cannot be deleted.
                        </span>
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="isDeleteDialogOpen = false">
                        Cancel
                    </Button>
                    <Button 
                        v-if="!consigneeToDelete?.expeditions_count || consigneeToDelete.expeditions_count === 0"
                        variant="destructive" 
                        @click="confirmDelete"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
