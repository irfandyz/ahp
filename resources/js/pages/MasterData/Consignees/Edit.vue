<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Save } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

interface Consignee {
    id: number;
    company: string;
    address: string;
    phone: string;
    email: string;
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
    {
        title: 'Edit',
        href: `/consignees/${props.consignee.id}/edit`,
    },
];

const form = useForm({
    company: props.consignee.company,
    address: props.consignee.address,
    phone: props.consignee.phone,
    email: props.consignee.email,
});

const submit = () => {
    form.put(`/consignees/${props.consignee.id}`);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head :title="`Edit ${consignee.company} - Consignee`" />

        <div class="space-y-6 p-5">
            <!-- Header -->
            <div class="flex items-center space-x-4">
                <Link :href="route('consignees.show', consignee.id)">
                    <Button variant="outline" size="sm">
                        <ArrowLeft class="h-4 w-4 mr-2" />
                        Back to Consignee
                    </Button>
                </Link>
                <div>
                    <h1 class="text-2xl font-bold">Edit Consignee</h1>
                    <p class="text-muted-foreground">Update consignee information for {{ consignee.company }}</p>
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
                    </div>

                    <div>
                        <Label for="email">Email *</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            :class="{ 'border-red-500': form.errors.email }"
                        />
                        <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                            {{ form.errors.email }}
                        </p>
                    </div>

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

                    <div class="flex justify-end space-x-3">
                        <Link :href="route('consignees.show', consignee.id)">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </Link>
                        <Button type="submit" :disabled="form.processing">
                            <Save class="h-4 w-4 mr-2" />
                            {{ form.processing ? 'Updating...' : 'Update Consignee' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
