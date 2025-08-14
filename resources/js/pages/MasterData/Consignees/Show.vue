<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Separator } from '@/components/ui/separator';
import { 
    ArrowLeft, 
    Edit, 
    Building2, 
    MapPin, 
    Phone, 
    Mail, 
    FileText, 
    Calendar,
    Package
} from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

interface Expedition {
    id: number;
    title: string;
    description?: string;
    status: string;
    created_at: string;
    updated_at: string;
}

interface Consignee {
    id: number;
    company: string;
    address: string;
    phone: string;
    email: string;
    expeditions: Expedition[];
    expeditions_count: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    consignee: Consignee;
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
    {
        title: props.consignee.company,
        href: `/consignees/${props.consignee.id}`,
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="`${consignee.company} - Consignee Details`" />

        <div class="space-y-6 p-5">
            <!-- Header -->
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div class="flex items-center space-x-4">
                    <Link :href="route('consignees.index')">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Consignees
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold">{{ consignee.company }}</h1>
                        <p class="text-muted-foreground">Consignee Details & Information</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <Link :href="route('consignees.edit', consignee.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Consignee
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Consignee Information Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Company Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center">
                            <Building2 class="h-5 w-5 mr-2 text-blue-600" />
                            Company Information
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ consignee.company }}</h3>
                            <p class="text-sm text-gray-600">Company Name</p>
                        </div>
                        <Separator />
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <MapPin class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ consignee.address }}</p>
                                    <p class="text-xs text-gray-500">Address</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Contact Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center">
                            <Mail class="h-5 w-5 mr-2 text-green-600" />
                            Contact Information
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <Phone class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ consignee.phone }}</p>
                                    <p class="text-xs text-gray-500">Phone Number</p>
                                </div>
                            </div>
                            <Separator />
                            <div class="flex items-center">
                                <Mail class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ consignee.email }}</p>
                                    <p class="text-xs text-gray-500">Email Address</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Expeditions Section -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center">
                        <Package class="h-5 w-5 mr-2 text-orange-600" />
                        Recent Expeditions
                        <Badge variant="secondary" class="ml-2">{{ consignee.expeditions_count }}</Badge>
                    </CardTitle>
                    <CardDescription>
                        Latest expeditions associated with this consignee
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="consignee.expeditions.length > 0" class="space-y-4">
                        <div v-for="expedition in consignee.expeditions" :key="expedition.id" 
                             class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <Package class="h-5 w-5 text-blue-600" />
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ expedition.title }}</h4>
                                    <p v-if="expedition.description" class="text-sm text-gray-600">
                                        {{ expedition.description }}
                                    </p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        <Calendar class="h-3 w-3 text-gray-400" />
                                        <span class="text-xs text-gray-500">
                                            {{ formatDate(expedition.created_at) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <Badge variant="outline">{{ expedition.status }}</Badge>
                        </div>
                    </div>
                    <div v-else class="text-center py-8">
                        <Package class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Expeditions Yet</h3>
                        <p class="text-gray-500">This consignee hasn't been associated with any expeditions yet.</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Additional Information -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center">
                        <FileText class="h-5 w-5 mr-2 text-gray-600" />
                        Additional Information
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Created</h4>
                            <p class="text-sm text-gray-600">{{ formatDate(consignee.created_at) }}</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Last Updated</h4>
                            <p class="text-sm text-gray-600">{{ formatDate(consignee.updated_at) }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
