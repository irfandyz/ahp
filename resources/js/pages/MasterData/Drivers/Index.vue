<template>
  <AppLayout title="Drivers">
    
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Drivers</h1>
          <p class="text-muted-foreground">Manage drivers for shipping operations</p>
        </div>
        <Link :href="route('drivers.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
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
      <div class="flex flex-col space-y-4 sm:flex-row sm-:items-center sm:space-y-0 sm:space-x-4">
        <form @submit.prevent="performSearch" class="flex-1 max-w-sm">
          <div class="relative">
            <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search drivers..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <button
              type="submit"
              class="absolute right-2 top-1/2 transform -translate-y-1/2 p-1 text-gray-400 hover:text-gray-600"
            >
              <Icon name="search" class="h-4 w-4" />
            </button>
          </div>
        </form>
        <Button 
          v-if="hasSearchQuery" 
          variant="outline" 
          @click="clearSearch"
          class="shrink-0"
        >
          Clear Search
        </Button>
      </div>

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
        <CardContent>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-2">Name</th>
                  <th class="text-left font-medium p-2">Phone</th>
                  <th class="text-left font-medium p-2">Email</th>
                  <th class="text-left font-medium p-2">Address</th>
                  <th class="text-left font-medium p-2">Expeditions</th>
                  <th class="text-left font-medium p-2">Created</th>
                  <th class="text-left font-medium p-2">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="driver in drivers.data" :key="driver.id" class="border-b hover:bg-gray-50">
                  <td class="p-2 font-medium">{{ driver.name }}</td>
                  <td class="p-2 text-gray-600">{{ driver.phone || '-' }}</td>
                  <td class="p-2 text-gray-600">{{ driver.email || '-' }}</td>
                  <td class="p-2 text-gray-600">{{ driver.address || '-' }}</td>
                  <td class="p-2 text-gray-500">
                    <div class="flex items-center">
                      <Icon name="truck" class="h-4 w-4 mr-2 text-gray-400" />
                      {{ driver.expeditions_count || 0 }}
                    </div>
                  </td>
                  <td class="p-2 text-gray-500">{{ formatDate(driver.created_at) }}</td>
                  <td class="p-2">
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
                <tr v-if="drivers.data.length === 0">
                  <td colspan="7" class="p-8 text-center text-gray-500">
                    {{ searchQuery ? 'No drivers found matching your search.' : 'No drivers found. Create your first driver to get started.' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="drivers.links && drivers.links.length > 3" class="mt-6">
            <nav class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link 
                  v-if="drivers.prev_page_url" 
                  :href="drivers.prev_page_url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Previous
                </Link>
                <Link 
                  v-if="drivers.next_page_url" 
                  :href="drivers.next_page_url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Next
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ drivers.from }}</span>
                    to
                    <span class="font-medium">{{ drivers.to }}</span>
                    of
                    <span class="font-medium">{{ drivers.total }}</span>
                    results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <Link 
                      v-for="(link, index) in drivers.links" 
                      :key="index"
                      :href="link.url || '#'"
                      v-html="link.label"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 text-sm font-medium border',
                        link.url === null 
                          ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                          : link.active 
                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' 
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                      ]"
                    />
                  </nav>
                </div>
              </div>
            </nav>
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
const searchQuery = ref(props.filters?.search || '')
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
  router.get(route('drivers.index'), {}, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
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
