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
    Trash2, 
    Building2, 
    MapPin, 
    Phone, 
    User, 
    FileText, 
    Car, 
    Globe, 
    Calendar,
    Truck,
    Ship,
    Train,
    Plane,
    Package,
    Route
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
    expeditions: Expedition[];
    expeditions_count: number;
    created_at: string;
    updated_at: string;
}

interface Props {
    vendor: Vendor;
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
    {
        title: props.vendor.company,
        href: `/vendors/${props.vendor.id}`,
    },
];

const getModaIcon = (moda: string) => {
    switch (moda.toLowerCase()) {
        case 'truck':
            return Truck;
        case 'ship':
            return Ship;
        case 'train':
            return Train;
        case 'air':
            return Plane;
        default:
            return Car;
    }
};

const getModaColor = (moda: string) => {
    switch (moda.toLowerCase()) {
        case 'truck':
            return 'bg-blue-100 text-blue-800 border-blue-200';
        case 'ship':
            return 'bg-indigo-100 text-indigo-800 border-indigo-200';
        case 'train':
            return 'bg-green-100 text-green-800 border-green-200';
        case 'air':
            return 'bg-purple-100 text-purple-800 border-purple-200';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-200';
    }
};

const getFleetColor = (fleet: string) => {
    switch (fleet.toLowerCase()) {
        case 'small':
            return 'bg-green-100 text-green-800 border-green-200';
        case 'medium':
            return 'bg-yellow-100 text-yellow-800 border-yellow-200';
        case 'large':
            return 'bg-red-100 text-red-800 border-red-200';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-200';
    }
};

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
        <Head :title="`${vendor.company} - Vendor Details`" />

        <div class="space-y-6 p-5">
            <!-- Header -->
            <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                <div class="flex items-center space-x-4">
                    <Link :href="route('vendors.index')">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Vendors
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-2xl font-bold">{{ vendor.company }}</h1>
                        <p class="text-muted-foreground">Vendor Details & Information</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <Link :href="route('vendors.edit', vendor.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Vendor
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Vendor Information Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
                            <h3 class="font-semibold text-gray-900">{{ vendor.company }}</h3>
                            <p class="text-sm text-gray-600">Company Name</p>
                        </div>
                        <Separator />
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <MapPin class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ vendor.address }}</p>
                                    <p class="text-xs text-gray-500">Address</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <Globe class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ vendor.city }}</p>
                                    <p class="text-xs text-gray-500">City</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Contact Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center">
                            <User class="h-5 w-5 mr-2 text-green-600" />
                            Contact Information
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-3">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ vendor.pic }}</h3>
                                <p class="text-sm text-gray-600">{{ vendor.title_pic }}</p>
                            </div>
                            <Separator />
                            <div class="flex items-center">
                                <Phone class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ vendor.phone }}</p>
                                    <p class="text-xs text-gray-500">Phone Number</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Service Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center">
                            <Car class="h-5 w-5 mr-2 text-purple-600" />
                            Service Information
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <component :is="getModaIcon(vendor.moda)" class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <Badge :class="getModaColor(vendor.moda)" class="border">
                                        {{ vendor.moda }}
                                    </Badge>
                                    <p class="text-xs text-gray-500 mt-1">Transportation Mode</p>
                                </div>
                            </div>
                            <Separator />
                            <div>
                                <Badge :class="getFleetColor(vendor.fleet)" class="border">
                                    {{ vendor.fleet }}
                                </Badge>
                                <p class="text-xs text-gray-500 mt-1">Fleet Size</p>
                            </div>
                            <Separator />
                            <div class="flex items-center">
                                <Route class="h-4 w-4 mr-2 text-gray-400" />
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ vendor.area_service_coverage }}</p>
                                    <p class="text-xs text-gray-500">Service Coverage Area</p>
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
                        <Badge variant="secondary" class="ml-2">{{ vendor.expeditions_count }}</Badge>
                    </CardTitle>
                    <CardDescription>
                        Latest expeditions associated with this vendor
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="vendor.expeditions.length > 0" class="space-y-4">
                        <div v-for="expedition in vendor.expeditions" :key="expedition.id" 
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
                        <p class="text-gray-500">This vendor hasn't been associated with any expeditions yet.</p>
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
                            <p class="text-sm text-gray-600">{{ formatDate(vendor.created_at) }}</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Last Updated</h4>
                            <p class="text-sm text-gray-600">{{ formatDate(vendor.updated_at) }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
