<template>
  <AppLayout title="Customer Details">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Customer Details</h1>
          <p class="text-gray-600">View customer information and related expeditions</p>
        </div>
        <div class="flex items-center space-x-3">
          <Link :href="route('customers.edit', customer.id)" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            <Icon name="edit" class="mr-2 h-4 w-4" />
            Edit Customer
          </Link>
          <Link :href="route('customers.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <Icon name="arrow-left" class="mr-2 h-4 w-4" />
            Back to List
          </Link>
        </div>
      </div>

      <!-- Customer Information -->
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Customer Details -->
        <div class="lg:col-span-2">
          <Card>
            <CardHeader>
              <CardTitle>Customer Information</CardTitle>
            </CardHeader>
            <CardContent class="space-y-6">
              <!-- Basic Information -->
              <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <p class="mt-1 text-lg">{{ customer.name }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">NPWP</label>
                    <p class="mt-1 text-lg font-mono">{{ customer.npwp || '-' }}</p>
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Address</label>
                  <p class="mt-1 text-lg">{{ customer.address || 'No address provided' }}</p>
                </div>
              </div>

              <!-- Contact Information -->
              <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <p class="mt-1 text-lg">{{ customer.phone || 'No phone provided' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <p class="mt-1 text-lg text-blue-600">{{ customer.email || 'No email provided' }}</p>
                  </div>
                </div>
              </div>

              <!-- Person in Charge -->
              <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Person in Charge (PIC)</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">PIC Name</label>
                    <p class="mt-1 text-lg">{{ customer.pic_name || 'No PIC assigned' }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">PIC Phone</label>
                    <p class="mt-1 text-lg">{{ customer.pic_phone || 'No PIC phone provided' }}</p>
                  </div>
                </div>
              </div>

              <!-- Timestamps -->
              <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">System Information</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Created</label>
                    <p class="mt-1 text-lg">{{ formatDate(customer.created_at) }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                    <p class="mt-1 text-lg">{{ formatDate(customer.updated_at) }}</p>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle>Quick Stats</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div class="text-center">
                  <div class="text-3xl font-bold text-blue-600">{{ customer.expeditions?.length || 0 }}</div>
                  <div class="text-sm text-gray-500">Total Expeditions</div>
                </div>
                <div class="text-center">
                  <div class="text-3xl font-bold text-green-600">{{ formatDate(customer.created_at, 'year') }}</div>
                  <div class="text-sm text-gray-500">Customer Since</div>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Actions -->
          <Card>
            <CardHeader>
              <CardTitle>Quick Actions</CardTitle>
            </CardHeader>
            <CardContent class="space-y-3">
              <Link :href="route('expeditions.create')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <Icon name="plus" class="mr-2 h-4 w-4" />
                New Expedition
              </Link>
              <Link :href="route('customers.edit', customer.id)" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <Icon name="edit" class="mr-2 h-4 w-4" />
                Edit Customer
              </Link>
            </CardContent>
          </Card>
        </div>
      </div>

      <!-- Recent Expeditions -->
      <Card>
        <CardHeader>
          <CardTitle>Recent Expeditions</CardTitle>
          <CardDescription>
            Latest expeditions for this customer
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div v-if="customer.expeditions && customer.expeditions.length > 0" class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="text-left font-medium p-4">Order Number</th>
                  <th class="text-left font-medium p-4">Route</th>
                  <th class="text-left font-medium p-4">Origin</th>
                  <th class="text-left font-medium p-4">Destination</th>
                  <th class="text-left font-medium p-4">Created</th>
                  <th class="text-left font-medium p-4">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="expedition in customer.expeditions" :key="expedition.id" class="hover:bg-gray-50">
                  <td class="p-4">
                    <div class="font-medium text-gray-900">{{ expedition.order_number }}</div>
                  </td>
                  <td class="p-4">
                    <span class="text-sm">{{ expedition.route?.name || 'N/A' }}</span>
                  </td>
                  <td class="p-4">
                    <span class="text-sm">{{ expedition.origin }}</span>
                  </td>
                  <td class="p-4">
                    <span class="text-sm">{{ expedition.destination }}</span>
                  </td>
                  <td class="p-4">
                    <span class="text-sm">{{ formatDate(expedition.created_at) }}</span>
                  </td>
                  <td class="p-4">
                    <Link :href="route('expeditions.show', expedition.id)" class="text-blue-600 hover:text-blue-800">
                      <Icon name="eye" class="h-4 w-4" />
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="p-8 text-center text-gray-500">
            <Icon name="package" class="mx-auto h-12 w-12 text-gray-400 mb-4" />
            <p class="text-lg font-medium">No expeditions yet</p>
            <p class="text-sm">This customer hasn't had any expeditions yet.</p>
            <Link :href="route('expeditions.create')" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
              <Icon name="plus" class="mr-2 h-4 w-4" />
              Create First Expedition
            </Link>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'

interface Expedition {
  id: number
  order_number: string
  origin: string
  destination: string
  route?: {
    id: number
    name: string
  }
  created_at: string
}

interface Customer {
  id: number
  name: string
  address?: string
  phone?: string
  email?: string
  npwp?: string
  pic_name?: string
  pic_phone?: string
  expeditions?: Expedition[]
  created_at: string
  updated_at: string
}

interface Props {
  customer: Customer
}

const props = defineProps<Props>()

const formatDate = (dateString: string, format: 'full' | 'year' = 'full') => {
  if (!dateString) return '-'
  
  const date = new Date(dateString)
  if (format === 'year') {
    return date.getFullYear().toString()
  }
  
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>
