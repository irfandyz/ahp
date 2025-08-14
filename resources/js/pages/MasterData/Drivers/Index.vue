<template>
  <AppLayout title="Drivers">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Drivers</h1>
          <p class="text-muted-foreground">Manage drivers for shipping operations</p>
        </div>
        <Link :href="route('drivers.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
          <Icon name="plus" class="mr-2 h-4 w-4" />
          Add Driver
        </Link>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="($page.props.flash as any)?.success" class="bg-green-50 border border-green-200 rounded-lg p-4">
        <p class="text-green-800">{{ ($page.props.flash as any)?.success }}</p>
      </div>
      
      <div v-if="($page.props.flash as any)?.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ ($page.props.flash as any)?.error }}</p>
      </div>

      <!-- Search and Filters -->
      <Card>
        <CardContent>
          <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
            <div class="flex-1 relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Icon name="search" class="h-5 w-5 text-gray-400" />
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search drivers by name, phone, email, or address..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
              />
            </div>
            <Button
              v-if="searchQuery"
              variant="outline"
              @click="clearSearch"
              class="shrink-0"
            >
              <Icon name="x" class="mr-2 h-4 w-4" />
              Clear
            </Button>
          </div>
        </CardContent>
      </Card>

      <!-- Drivers Table -->
      <Card>
        <CardHeader>
          <div>
            <CardTitle>Drivers List</CardTitle>
            <CardDescription>
              View and manage all drivers
            </CardDescription>
          </div>
        </CardHeader>
        <CardContent class="p-0">
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-4">Name</th>
                  <th class="text-left font-medium p-4">Contact Info</th>
                  <th class="text-left font-medium p-4">Address</th>
                  <th class="text-left font-medium p-4">Expeditions</th>
                  <th class="text-left font-medium p-4">Created</th>
                  <th class="text-left font-medium p-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="driver in filteredDrivers" :key="driver.id" class="border-b hover:bg-gray-50 transition-colors">
                  <td class="p-4 font-medium">{{ driver.name }}</td>
                  <td class="p-4">
                    <div class="space-y-1">
                      <div v-if="driver.phone" class="flex items-center text-sm text-gray-600">
                        <Icon name="phone" class="h-4 w-4 mr-2 text-gray-400" />
                        {{ driver.phone }}
                      </div>
                      <div v-if="driver.email" class="flex items-center text-sm text-gray-600">
                        <Icon name="mail" class="h-4 w-4 mr-2 text-gray-400" />
                        {{ driver.email }}
                      </div>
                    </div>
                  </td>
                  <td class="p-4 text-gray-600">{{ driver.address || '-' }}</td>
                  <td class="p-4 text-gray-500">
                    <div class="flex items-center">
                      <Icon name="truck" class="h-4 w-4 mr-2 text-gray-400" />
                      {{ driver.expeditions_count || 0 }}
                    </div>
                  </td>
                  <td class="p-4 text-gray-500">{{ formatDate(driver.created_at) }}</td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('drivers.show', driver.id)">
                          <Icon name="eye" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('drivers.edit', driver.id)">
                          <Icon name="edit" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button 
                        variant="outline" 
                        size="sm" 
                        @click="deleteDriver(driver)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredDrivers.length === 0">
                  <td colspan="6" class="p-8 text-center text-gray-500">
                    <div class="flex flex-col items-center space-y-2">
                      <Icon name="user" class="h-12 w-12 text-gray-300" />
                      <p class="text-lg font-medium">No drivers found</p>
                      <p class="text-sm">Create your first driver to get started.</p>
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
    <Dialog v-model:open="showDeleteDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Delete Driver</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete "{{ driverToDelete?.name }}"? This action cannot be undone.
            <span v-if="driverToDelete?.expeditions_count && driverToDelete.expeditions_count > 0" class="block mt-2 text-red-600">
              This driver has {{ driverToDelete.expeditions_count }} expeditions and cannot be deleted.
            </span>
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="showDeleteDialog = false">
            Cancel
          </Button>
          <Button 
            v-if="!driverToDelete?.expeditions_count || driverToDelete.expeditions_count === 0"
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
import { computed, ref, watch } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
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

interface Driver {
  id: number
  name: string
  phone?: string
  email?: string
  address?: string
  expeditions_count?: number
  created_at: string
  updated_at: string
}

interface DriversPaginated {
  data: Driver[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
  prev_page_url: string | null
  next_page_url: string | null
}

interface Props {
  drivers: DriversPaginated
  filters?: {
    search?: string
  }
}

const props = defineProps<Props>()
const page = usePage()
const searchQuery = ref('')

const filteredDrivers = computed(() => {
  if (!searchQuery.value) return props.drivers.data
  
  return props.drivers.data.filter(driver =>
    driver.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    (driver.phone && driver.phone.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
    (driver.email && driver.email.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
    (driver.address && driver.address.toLowerCase().includes(searchQuery.value.toLowerCase()))
  )
})

const showDeleteDialog = ref(false)
const driverToDelete = ref<Driver | null>(null)
const isDeleting = ref(false)

// Watch for search query changes and update from filters
watch(() => props.filters?.search, (newSearch) => {
  searchQuery.value = newSearch || ''
})

const hasSearchQuery = computed(() => !!searchQuery.value)

const performSearch = () => {
  router.get(route('drivers.index'), { search: searchQuery.value }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
}

const clearSearch = () => {
  searchQuery.value = ''
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  const month = monthNames[date.getMonth()]
  const year = date.getFullYear()
  return `${day}-${month}-${year}`
}

const deleteDriver = (driver: Driver) => {
  driverToDelete.value = driver
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  if (!driverToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('drivers.destroy', driverToDelete.value.id), {
    onFinish: () => {
      isDeleting.value = false
      showDeleteDialog.value = false
      driverToDelete.value = null
    }
  })
}
</script>
