<template>
  <AppLayout title="Fleet Vehicles">
    
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Fleet Vehicles</h1>
          <p class="text-muted-foreground">Manage your fleet</p>
        </div>
        <Link :href="route('fleets.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <Icon name="plus" class="mr-2 h-4 w-4" />
          Add Vehicle
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
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
        <form @submit.prevent="performSearch" class="flex-1 max-w-sm">
          <div class="relative">
            <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search vehicles..."
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

      <!-- Fleet Vehicles Table -->
      <Card>
        <CardHeader>
          <div>
                      <CardTitle>Fleet Vehicles List</CardTitle>
          <CardDescription>
            View and manage all fleet vehicles
          </CardDescription>
          </div>
        </CardHeader>
        <CardContent>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-2">Plate Number</th>
                  <th class="text-left font-medium p-2">Fleet Type</th>
                  <th class="text-left font-medium p-2">Description</th>
                  <th class="text-left font-medium p-2">Expeditions</th>
                  <th class="text-left font-medium p-2">Created</th>
                  <th class="text-left font-medium p-2">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fleet in fleets.data" :key="fleet.id" class="border-b hover:bg-gray-50">
                  <td class="p-2 font-medium">{{ fleet.plate_number }}</td>
                  <td class="p-2 text-gray-600">{{ fleet.fleet_type?.name || '-' }}</td>
                  <td class="p-2 text-gray-600">{{ fleet.description || '-' }}</td>
                  <td class="p-2 text-gray-500">
                    <div class="flex items-center">
                      <Icon name="truck" class="h-4 w-4 mr-2 text-gray-400" />
                      {{ fleet.expeditions_count || 0 }}
                    </div>
                  </td>
                  <td class="p-2 text-gray-500">{{ formatDate(fleet.created_at) }}</td>
                  <td class="p-2">
                    <div class="flex items-center space-x-2">
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('fleets.show', fleet.id)">
                          <Icon name="eye" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('fleets.edit', fleet.id)">
                          <Icon name="edit" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button 
                        variant="outline" 
                        size="sm" 
                        @click="deleteFleet(fleet)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
                <tr v-if="fleets.data.length === 0">
                  <td colspan="6" class="p-8 text-center text-gray-500">
                    {{ searchQuery ? 'No fleet vehicles found matching your search.' : 'No fleet vehicles found. Create your first vehicle to get started.' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="fleets.links && fleets.links.length > 3" class="mt-6">
            <nav class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link 
                  v-if="fleets.prev_page_url" 
                  :href="fleets.prev_page_url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Previous
                </Link>
                <Link 
                  v-if="fleets.next_page_url" 
                  :href="fleets.next_page_url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Next
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ fleets.from }}</span>
                    to
                    <span class="font-medium">{{ fleets.to }}</span>
                    of
                    <span class="font-medium">{{ fleets.total }}</span>
                    results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <Link 
                      v-for="(link, index) in fleets.links" 
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
          <DialogTitle>Delete Fleet Vehicle</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete "{{ fleetToDelete?.plate_number }}"? This action cannot be undone.
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

interface FleetType {
  id: number
  name: string
}

interface Fleet {
  id: number
  plate_number: string
  description?: string
  fleet_type?: FleetType
  expeditions_count?: number
  created_at: string
  updated_at: string
}

interface FleetsPaginated {
  data: Fleet[]
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
  fleets: FleetsPaginated
  filters?: {
    search?: string
  }
}

const props = defineProps<Props>()
const page = usePage()
const searchQuery = ref(props.filters?.search || '')
const showDeleteDialog = ref(false)
const fleetToDelete = ref<Fleet | null>(null)
const isDeleting = ref(false)

// Watch for search query changes and update from filters
watch(() => props.filters?.search, (newSearch) => {
  searchQuery.value = newSearch || ''
})

const hasSearchQuery = computed(() => !!searchQuery.value)

const performSearch = () => {
  router.get(route('fleets.index'), { search: searchQuery.value }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  })
}

const clearSearch = () => {
  searchQuery.value = ''
  router.get(route('fleets.index'), {}, {
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

const deleteFleet = (fleet: Fleet) => {
  fleetToDelete.value = fleet
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  if (!fleetToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('fleets.destroy', fleetToDelete.value.id), {
    onFinish: () => {
      isDeleting.value = false
      showDeleteDialog.value = false
      fleetToDelete.value = null
    }
  })
}
</script>
