<template>
  <AppLayout title="Fleet Types">
    
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Fleet Types</h1>
          <p class="text-muted-foreground">Manage fleet types for shipping</p>
        </div>
        <Link :href="route('fleet-types.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <Icon name="plus" class="mr-2 h-4 w-4" />
          Add Fleet Type
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
        <div class="relative flex-1 max-w-sm">
          <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search fleet types..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
      </div>

      <!-- Fleet Types Table -->
      <Card>
        <CardHeader>
          <div>
            <CardTitle>Fleet Types List</CardTitle>
            <CardDescription>
              View and manage all fleet types
            </CardDescription>
          </div>
        </CardHeader>
        <CardContent>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-2">Name</th>
                  <th class="text-left font-medium p-2">Description</th>
                  <th class="text-left font-medium p-2">Shippings</th>
                  <th class="text-left font-medium p-2">Created</th>
                  <th class="text-left font-medium p-2">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="fleetType in filteredFleetTypes" :key="fleetType.id" class="border-b hover:bg-gray-50">
                  <td class="p-2 font-medium">{{ fleetType.name }}</td>
                  <td class="p-2 text-gray-600">{{ fleetType.description || '-' }}</td>
                  <td class="p-2 text-gray-500">
                    <div class="flex items-center">
                      <Icon name="truck" class="h-4 w-4 mr-2 text-gray-400" />
                      {{ fleetType.shippings_count || 0 }}
                    </div>
                  </td>
                  <td class="p-2 text-gray-500">{{ formatDate(fleetType.created_at) }}</td>
                  <td class="p-2">
                    <div class="flex items-center space-x-2">
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('fleet-types.edit', fleetType.id)">
                          <Icon name="edit" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button 
                        variant="outline" 
                        size="sm" 
                        @click="deleteFleetType(fleetType)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredFleetTypes.length === 0">
                  <td colspan="5" class="p-8 text-center text-gray-500">
                    No fleet types found. Create your first fleet type to get started.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="fleetTypes.links && fleetTypes.links.length > 3" class="mt-6">
            <nav class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link 
                  v-if="fleetTypes.prev_page_url" 
                  :href="fleetTypes.prev_page_url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Previous
                </Link>
                <Link 
                  v-if="fleetTypes.next_page_url" 
                  :href="fleetTypes.next_page_url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Next
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ fleetTypes.from }}</span>
                    to
                    <span class="font-medium">{{ fleetTypes.to }}</span>
                    of
                    <span class="font-medium">{{ fleetTypes.total }}</span>
                    results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <Link 
                      v-for="(link, index) in fleetTypes.links" 
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
          <DialogTitle>Delete Fleet Type</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete "{{ fleetTypeToDelete?.name }}"? This action cannot be undone.
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
import { computed, ref } from 'vue'
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
  description?: string
  shippings_count?: number
  created_at: string
  updated_at: string
}

interface FleetTypesPaginated {
  data: FleetType[]
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
  fleetTypes: FleetTypesPaginated
}

const props = defineProps<Props>()
const page = usePage()
const searchQuery = ref('')
const showDeleteDialog = ref(false)
const fleetTypeToDelete = ref<FleetType | null>(null)
const isDeleting = ref(false)

const filteredFleetTypes = computed(() => {
  if (!searchQuery.value) {
    return props.fleetTypes.data
  }
  
  const query = searchQuery.value.toLowerCase()
  return props.fleetTypes.data.filter(fleetType => 
    fleetType.name.toLowerCase().includes(query) ||
    (fleetType.description && fleetType.description.toLowerCase().includes(query))
  )
})

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  const month = monthNames[date.getMonth()]
  const year = date.getFullYear()
  return `${day}-${month}-${year}`
}

const deleteFleetType = (fleetType: FleetType) => {
  fleetTypeToDelete.value = fleetType
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  if (!fleetTypeToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('fleet-types.destroy', fleetTypeToDelete.value.id), {
    onFinish: () => {
      isDeleting.value = false
      showDeleteDialog.value = false
      fleetTypeToDelete.value = null
    }
  })
}
</script>
