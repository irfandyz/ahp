<template>
  <AppLayout title="Edit Customer">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Edit Customer</h1>
          <p class="text-gray-600">Update customer information</p>
        </div>
        <Link :href="route('customers.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <Icon name="arrow-left" class="mr-2 h-4 w-4" />
          Back to List
        </Link>
      </div>

      <!-- Form -->
      <Card>
        <CardHeader>
          <CardTitle>Customer Information</CardTitle>
          <CardDescription>
            Update the customer information below.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Basic Information -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
              
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Company Name <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter company name"
                  />
                  <div v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.name }}
                  </div>
                </div>

                <div>
                  <label for="npwp" class="block text-sm font-medium text-gray-700 mb-2">
                    NPWP
                  </label>
                  <input
                    id="npwp"
                    v-model="form.npwp"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter NPWP number"
                  />
                  <div v-if="form.errors.npwp" class="mt-1 text-sm text-red-600">
                    {{ form.errors.npwp }}
                  </div>
                </div>
              </div>

              <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                  Address
                </label>
                <textarea
                  id="address"
                  v-model="form.address"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter company address"
                ></textarea>
                <div v-if="form.errors.address" class="mt-1 text-sm text-red-600">
                  {{ form.errors.address }}
                </div>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
              
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number
                  </label>
                  <input
                    id="phone"
                    v-model="form.phone"
                    type="tel"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter phone number"
                  />
                  <div v-if="form.errors.phone" class="mt-1 text-sm text-red-600">
                    {{ form.errors.phone }}
                  </div>
                </div>

                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address
                  </label>
                  <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter email address"
                  />
                  <div v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                    {{ form.errors.email }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Person in Charge (PIC) -->
            <div class="space-y-4">
              <h3 class="text-lg font-medium text-gray-900">Person in Charge (PIC)</h3>
              
              <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                  <label for="pic_name" class="block text-sm font-medium text-gray-700 mb-2">
                    PIC Name
                  </label>
                  <input
                    id="pic_name"
                    v-model="form.pic_name"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter PIC name"
                  />
                  <div v-if="form.errors.pic_name" class="mt-1 text-sm text-red-600">
                    {{ form.errors.pic_name }}
                  </div>
                </div>

                <div>
                  <label for="pic_phone" class="block text-sm font-medium text-gray-700 mb-2">
                    PIC Phone
                  </label>
                  <input
                    id="pic_phone"
                    v-model="form.pic_phone"
                    type="tel"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter PIC phone number"
                  />
                  <div v-if="form.errors.pic_phone" class="mt-1 text-sm text-red-600">
                    {{ form.errors.pic_phone }}
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
              <Link :href="route('customers.index')" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Cancel
              </Link>
              <Button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <Icon v-if="form.processing" name="loader" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Updating...' : 'Update Customer' }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'

interface Customer {
  id: number
  name: string
  address?: string
  phone?: string
  email?: string
  npwp?: string
  pic_name?: string
  pic_phone?: string
  created_at: string
  updated_at: string
}

interface Props {
  customer: Customer
}

const props = defineProps<Props>()

const form = useForm({
  name: props.customer.name,
  address: props.customer.address || '',
  phone: props.customer.phone || '',
  email: props.customer.email || '',
  npwp: props.customer.npwp || '',
  pic_name: props.customer.pic_name || '',
  pic_phone: props.customer.pic_phone || '',
})

const submit = () => {
  form.put(route('customers.update', props.customer.id))
}
</script>
