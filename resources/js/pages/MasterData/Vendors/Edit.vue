<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { ArrowLeft, Save } from 'lucide-vue-next';
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
    {
        title: 'Edit',
        href: `/vendors/${props.vendor.id}/edit`,
    },
];

const form = useForm({
    company: props.vendor.company,
    address: props.vendor.address,
    city: props.vendor.city,
    pic: props.vendor.pic,
    title_pic: props.vendor.title_pic,
    phone: props.vendor.phone,
    moda: props.vendor.moda,
    fleet: props.vendor.fleet,
    area_service_coverage: props.vendor.area_service_coverage,
});

const submit = () => {
    form.put(`/vendors/${props.vendor.id}`);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="`Edit ${vendor.company} - Vendor`" />

        <div class="space-y-6 p-5">
            <!-- Header -->
            <div class="flex items-center space-x-4">
                <Link :href="route('vendors.show', vendor.id)">
                    <Button variant="outline" size="sm">
                        <ArrowLeft class="h-4 w-4 mr-2" />
                        Back to Vendor
                    </Button>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold">Edit Vendor</h1>
                    <p class="text-muted-foreground">Update vendor information for {{ vendor.company }}</p>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <Label for="company">Company Name *</Label>
                            <Input
                                id="company"
                                v-model="form.company"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.company }"
                            />
                            <p v-if="form.errors.company" class="text-red-500 text-sm mt-1">
                                {{ form.errors.company }}
                            </p>
                        </div>

                        <div>
                            <Label for="pic">PIC (Person In Charge) *</Label>
                            <Input
                                id="pic"
                                v-model="form.pic"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.pic }"
                            />
                            <p v-if="form.errors.pic" class="text-red-500 text-sm mt-1">
                                {{ form.errors.pic }}
                            </p>
                        </div>

                        <div>
                            <Label for="title_pic">PIC Title *</Label>
                            <Input
                                id="title_pic"
                                v-model="form.title_pic"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.title_pic }"
                            />
                            <p v-if="form.errors.title_pic" class="text-red-500 text-sm mt-1">
                                {{ form.errors.title_pic }}
                            </p>
                        </div>

                        <div>
                            <Label for="phone">Phone *</Label>
                            <Input
                                id="phone"
                                v-model="form.phone"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.phone }"
                            />
                            <p v-if="form.errors.phone" class="text-red-500 text-sm mt-1">
                                {{ form.errors.phone }}
                            </p>
                        </div>

                        <div>
                            <Label for="moda">Moda *</Label>
                            <select
                                id="moda"
                                v-model="form.moda"
                                required
                                :class="[
                                    'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                                    form.errors.moda ? 'border-red-500' : ''
                                ]"
                            >
                                <option value="">Select transportation mode</option>
                                <option value="land">Land</option>
                                <option value="air">Air</option>
                                <option value="sea">Sea</option>
                            </select>
                            <p v-if="form.errors.moda" class="text-red-500 text-sm mt-1">
                                {{ form.errors.moda }}
                            </p>
                        </div>

                        <div>
                            <Label for="fleet">Fleet *</Label>
                            <Input
                                id="fleet"
                                v-model="form.fleet"
                                type="text"
                                required
                                placeholder="e.g., Train, Truck, Ship, Aircraft"
                                :class="{ 'border-red-500': form.errors.fleet }"
                            />
                            <p v-if="form.errors.fleet" class="text-red-500 text-sm mt-1">
                                {{ form.errors.fleet }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <Label for="address">Address *</Label>
                            <Input
                                id="address"
                                v-model="form.address"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.address }"
                            />
                            <p v-if="form.errors.address" class="text-red-500 text-sm mt-1">
                                {{ form.errors.address }}
                            </p>
                        </div>

                        <div>
                            <Label for="city">City *</Label>
                            <Input
                                id="city"
                                v-model="form.city"
                                type="text"
                                required
                                :class="{ 'border-red-500': form.errors.city }"
                            />
                            <p v-if="form.errors.city" class="text-red-500 text-sm mt-1">
                                {{ form.errors.city }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <Label for="area_service_coverage">Service Coverage Area *</Label>
                        <Input
                            id="area_service_coverage"
                            v-model="form.area_service_coverage"
                            type="text"
                            required
                            placeholder="e.g., Jakarta, Surabaya, Bandung"
                            :class="{ 'border-red-500': form.errors.area_service_coverage }"
                        />
                        <p v-if="form.errors.area_service_coverage" class="text-red-500 text-sm mt-1">
                            {{ form.errors.area_service_coverage }}
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <Link :href="route('vendors.show', vendor.id)">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </Link>
                        <Button type="submit" :disabled="form.processing">
                            <Save class="h-4 w-4 mr-2" />
                            {{ form.processing ? 'Updating...' : 'Update Vendor' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
