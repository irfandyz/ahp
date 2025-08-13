<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Plus, Search, Edit, Trash2, Eye, Building2, Mail, Phone, MapPin, User, FileText, Car, Globe } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

interface Vendor {
    id: number;
    company: string;
    address: string;
    city: string;
    pic: string;
    title_pic: string;
    phone: string;
    moda: string;
    fleet: string;
    area_service_coverage: string;
    expeditions_count: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    vendors: {
        data: Vendor[];
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
        title: 'Vendors',
        href: '/vendors',
    },
];

const searchQuery = ref('');
const isDeleteDialogOpen = ref(false);
const vendorToDelete = ref<Vendor | null>(null);
const pageSize = ref<number>(props.vendors.per_page);

const deleteForm = useForm({});

const filteredVendors = computed(() => {
    if (!searchQuery.value) return props.vendors.data;
    
    return props.vendors.data.filter(vendor =>
        vendor.company.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        vendor.pic.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        vendor.city.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const openDeleteDialog = (vendor: Vendor) => {
    vendorToDelete.value = vendor;
    isDeleteDialogOpen.value = true;
};

const confirmDelete = () => {
    if (vendorToDelete.value) {
        deleteForm.delete(`/vendors/${vendorToDelete.value.id}`, {
            onSuccess: () => {
                isDeleteDialogOpen.value = false;
                vendorToDelete.value = null;
            },
        });
    }
};

const getVisiblePages = (): (number | string)[] => {
    const current = props.vendors.current_page;
    const last = props.vendors.last_page;
    const delta = 2;
    
    const range: number[] = [];
    const rangeWithDots: (number | string)[] = [];
    
    for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
        range.push(i);
    }
    
    if (current - delta > 2) {
        rangeWithDots.push(1, '...');
    } else {
        rangeWithDots.push(1);
    }
    
    rangeWithDots.push(...range);
    
    if (current + delta < last - 1) {
        rangeWithDots.push('...', last);
    } else if (last > 1) {
        rangeWithDots.push(last);
    }
    
    return rangeWithDots;
};

const changePageSize = (newSize: string) => {
    pageSize.value = parseInt(newSize);
    window.location.href = `/vendors?page=1&per_page=${newSize}`;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Vendors" />

        <div class="space-y-6 p-5">
            <!-- Header -->
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div>
                    <h1 class="text-2xl font-bold">Vendors</h1>
                    <p class="text-muted-foreground">Manage your vendor master data</p>
                </div>
                <Link :href="route('vendors.create')">
                    <Button>
                        <Plus class="h-4 w-4 mr-2" />
                        Add Vendor
                    </Button>
                </Link>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="props.success" class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ props.success }}</p>
                    </div>
                </div>
            </div>

            <div v-if="props.error" class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ props.error }}</p>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
                <div class="relative flex-1 max-w-sm">
                    <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input
                        v-model="searchQuery"
                        placeholder="Search vendors..."
                        class="pl-10"
                    />
                </div>
            </div>

            <!-- Vendors Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Company
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact Info
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Services
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Expeditions
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="vendor in filteredVendors" :key="vendor.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <Building2 class="h-5 w-5 text-blue-600" />
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ vendor.company }}</div>
                                            <div class="text-sm text-gray-500">{{ vendor.pic }} - {{ vendor.title_pic }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 space-y-1">
                                        <div class="flex items-center">
                                            <User class="h-4 w-4 mr-2 text-gray-400" />
                                            {{ vendor.pic }}
                                        </div>
                                        <div class="flex items-center">
                                            <Phone class="h-4 w-4 mr-2 text-gray-400" />
                                            {{ vendor.phone }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 space-y-1">
                                        <div class="flex items-center">
                                            <MapPin class="h-4 w-4 mr-2 text-gray-400" />
                                            {{ vendor.address }}
                                        </div>
                                        <div class="flex items-center">
                                            <Globe class="h-4 w-4 mr-2 text-gray-400" />
                                            {{ vendor.city }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 space-y-1">
                                        <div class="flex items-center">
                                            <Car class="h-4 w-4 mr-2 text-gray-400" />
                                            {{ vendor.moda }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Fleet: {{ vendor.fleet }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Coverage: {{ vendor.area_service_coverage }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <FileText class="h-4 w-4 mr-2 text-gray-400" />
                                        {{ vendor.expeditions_count }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <Link :href="route('vendors.show', vendor.id)">
                                            <Button variant="ghost" size="sm">
                                                <Eye class="h-4 w-4" />
                                            </Button>
                                        </Link>
                                        <Link :href="route('vendors.edit', vendor.id)">
                                            <Button variant="ghost" size="sm">
                                                <Edit class="h-4 w-4" />
                                            </Button>
                                        </Link>
                                        <Button 
                                            variant="ghost" 
                                            size="sm"
                                            @click="openDeleteDialog(vendor)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="props.vendors.last_page > 1" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link 
                                v-if="props.vendors.current_page > 1"
                                :href="`/vendors?page=${props.vendors.current_page - 1}`"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Previous
                            </Link>
                            <Link 
                                v-if="props.vendors.current_page < props.vendors.last_page"
                                :href="`/vendors?page=${props.vendors.current_page + 1}`"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">{{ (props.vendors.current_page - 1) * props.vendors.per_page + 1 }}</span>
                                        to
                                        <span class="font-medium">{{ Math.min(props.vendors.current_page * props.vendors.per_page, props.vendors.total) }}</span>
                                        of
                                        <span class="font-medium">{{ props.vendors.total }}</span>
                                        results
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <Label for="page-size" class="text-sm text-gray-600">Show:</Label>
                                    <Select :value="pageSize" @update:value="changePageSize">
                                        <SelectTrigger class="w-20 h-8">
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem :value="'10'">10</SelectItem>
                                            <SelectItem :value="'15'">15</SelectItem>
                                            <SelectItem :value="'25'">25</SelectItem>
                                            <SelectItem :value="'50'">50</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <!-- Previous Page -->
                                    <Link 
                                        v-if="props.vendors.current_page > 1"
                                        :href="`/vendors?page=${props.vendors.current_page - 1}&per_page=${pageSize}`"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </Link>
                                    
                                    <!-- Page Numbers -->
                                    <template v-for="page in getVisiblePages()" :key="page">
                                        <Link 
                                            v-if="page !== '...'"
                                            :href="`/vendors?page=${page}&per_page=${pageSize}`"
                                            :class="[
                                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                                page === props.vendors.current_page
                                                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                            ]"
                                        >
                                            {{ page }}
                                        </Link>
                                        <span 
                                            v-else
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                        >
                                            ...
                                        </span>
                                    </template>
                                    
                                    <!-- Next Page -->
                                    <Link 
                                        v-if="props.vendors.current_page < props.vendors.last_page"
                                        :href="`/vendors?page=${props.vendors.current_page + 1}&per_page=${pageSize}`"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        <span class="sr-only">Next</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </Link>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:open="isDeleteDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Vendor</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete "{{ vendorToDelete?.company }}"? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="isDeleteDialogOpen = false">
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="confirmDelete" :disabled="deleteForm.processing">
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>