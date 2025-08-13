<template>
  <AppLayout title="Driver Details">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Driver Details</h1>
          <p class="text-muted-foreground">View driver information and associated shippings</p>
        </div>
        <div class="flex space-x-3">
          <Link :href="route('drivers.edit', driver.id)" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <Icon name="edit" class="mr-2 h-4 w-4" />
            Edit Driver
          </Link>
          <Link :href="route('drivers.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <Icon name="arrow-left" class="mr-2 h-4 w-4" />
            Back to Drivers
          </Link>
        </div>
      </div>

      <!-- Driver Information -->
      <Card>
        <CardHeader>
          <CardTitle>Driver Information</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="space-y-4">
              <div>
                <Label class="text-sm font-medium text-gray-500">Name</Label>
                <p class="text-lg font-semibold">{{ driver.name }}</p>
              </div>
              <div>
                <Label class="text-sm font-medium text-gray-500">Phone</Label>
                <p class="text-base">{{ driver.phone || 'Not provided' }}</p>
              </div>
            </div>
            <div class="space-y-4">
              <div>
                <Label class="text-sm font-medium text-gray-500">Email</Label>
                <p class="text-base">{{ driver.email || 'Not provided' }}</p>
              </div>
              <div>
                <Label class="text-sm font-medium text-gray-500">Address</Label>
                <p class="text-base">{{ driver.address || 'Not provided' }}</p>
              </div>
            </div>
          </div>
          <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
              <div>
                <Label class="text-sm font-medium text-gray-500">Created</Label>
                <p class="text-base">{{ formatDate(driver.created_at) }}</p>
              </div>
              <div>
                <Label class="text-sm font-medium text-gray-500">Last Updated</Label>
                <p class="text-base">{{ formatDate(driver.updated_at) }}</p>
              </div>
                             <div>
                 <Label class="text-sm font-medium text-gray-500">Total Expeditions</Label>
                 <p class="text-base font-semibold">{{ driver.expeditions?.length || 0 }}</p>
               </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Associated Expeditions -->
      <Card v-if="driver.expeditions && driver.expeditions.length > 0">
        <CardHeader>
          <CardTitle>Recent Expeditions</CardTitle>
          <CardDescription>
            Latest expedition assignments for this driver
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-2">Order Number</th>
                  <th class="text-left font-medium p-2">Origin</th>
                  <th class="text-left font-medium p-2">Destination</th>
                  <th class="text-left font-medium p-2">Status</th>
                  <th class="text-left font-medium p-2">Assigned Date</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="expedition in driver.expeditions" :key="expedition.id" class="border-b hover:bg-gray-50">
                  <td class="p-2 font-medium">{{ expedition.order_number }}</td>
                  <td class="p-2 text-gray-600">{{ expedition.origin }}</td>
                  <td class="p-2 text-gray-600">{{ expedition.destination }}</td>
                  <td class="p-2">
                    <Badge :variant="getStatusVariant(expedition.status)">
                      {{ expedition.status || 'Unknown' }}
                    </Badge>
                  </td>
                  <td class="p-2 text-gray-500">{{ formatDate(expedition.created_at) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      <!-- No Expeditions Message -->
      <Card v-else>
        <CardContent class="text-center py-8">
          <Icon name="truck" class="mx-auto h-12 w-12 text-gray-400 mb-4" />
          <h3 class="text-lg font-medium text-gray-900 mb-2">No Expeditions Yet</h3>
          <p class="text-gray-500">This driver hasn't been assigned to any expedition operations yet.</p>
        </CardContent>
      </Card>

      <!-- Actions -->
      <Card>
        <CardHeader>
          <CardTitle>Actions</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="flex space-x-3">
            <Button variant="outline" as-child>
              <Link :href="route('drivers.edit', driver.id)">
                <Icon name="edit" class="mr-2 h-4 w-4" />
                Edit Driver
              </Link>
            </Button>
            <Button 
              variant="destructive" 
              @click="deleteDriver"
              class="text-red-600 hover:text-red-700"
            >
              <Icon name="trash" class="mr-2 h-4 w-4" />
              Delete Driver
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:open="showDeleteDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Delete Driver</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete "{{ driver.name }}"? This action cannot be undone.
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="showDeleteDialog = false">
            Cancel
          </Button>
          <Button 
            variant="destructive" 
            @click="confirmDelete"
            :disabled="isDeleting"
          >
            <Icon v-if="isDeleting" name="loader" class="mr-2 h-4 w-4 animate-spin" />
            {{ isDeleting ? 'Deleting...' : 'Delete' }}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import Dialog from '@/components/ui/dialog/Dialog.vue'
import DialogContent from '@/components/ui/dialog/DialogContent.vue'
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue'
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue'
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue'
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue'
import Label from '@/components/ui/label/Label.vue'

interface Expedition {
  id: number
  order_number: string
  origin: string
  destination: string
  status?: string
  created_at: string
}

interface Driver {
  id: number
  name: string
  phone?: string
  email?: string
  address?: string
  created_at: string
  updated_at: string
  expeditions?: Expedition[]
}

interface Props {
  driver: Driver
}

const props = defineProps<Props>()
const showDeleteDialog = ref(false)
const isDeleting = ref(false)

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  const month = monthNames[date.getMonth()]
  const year = date.getFullYear()
  return `${day}-${month}-${year}`
}

const getStatusVariant = (status: string | undefined) => {
  switch (status?.toLowerCase()) {
    case 'active':
    case 'completed':
      return 'default'
    case 'pending':
      return 'secondary'
    case 'cancelled':
      return 'destructive'
    default:
      return 'outline'
  }
}

const deleteDriver = () => {
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  isDeleting.value = true
  router.delete(route('drivers.destroy', props.driver.id), {
    onFinish: () => {
      isDeleting.value = false
      showDeleteDialog.value = false
    }
  })
}
</script>
