<template>
  <AppLayout title="Industry Sectors">
    
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Industry Sectors</h1>
          <p class="text-muted-foreground">Manage industry sectors for expeditions</p>
        </div>
        <Link :href="route('industry-sectors.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <Icon name="plus" class="mr-2 h-4 w-4" />
          Add Industry Sector
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
            placeholder="Search industry sectors..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
      </div>

      <!-- Industry Sectors Table -->
      <Card>
        <CardHeader>
          <div>
            <CardTitle>Industry Sectors List</CardTitle>
            <CardDescription>
              View and manage all industry sectors
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
                  <th class="text-left font-medium p-2">Expeditions</th>
                  <th class="text-left font-medium p-2">Created</th>
                  <th class="text-left font-medium p-2">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="sector in filteredSectors" :key="sector.id" class="border-b hover:bg-gray-50">
                  <td class="p-2 font-medium">{{ sector.name }}</td>
                  <td class="p-2 text-gray-600">{{ sector.description }}</td>
                  <td class="p-2 text-gray-500">
                    <div class="flex items-center">
                      <Icon name="file-text" class="h-4 w-4 mr-2 text-gray-400" />
                      {{ sector.expeditions_count || 0 }}
                    </div>
                  </td>
                  <td class="p-2 text-gray-500">{{ formatDate(sector.created_at) }}</td>
                  <td class="p-2">
                    <div class="flex items-center space-x-2">

                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('industry-sectors.edit', sector.id)">
                          <Icon name="edit" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button 
                        variant="outline" 
                        size="sm" 
                        @click="deleteSector(sector)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredSectors.length === 0">
                  <td colspan="5" class="p-8 text-center text-gray-500">
                    No industry sectors found. Create your first industry sector to get started.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="props.industrySectors.last_page > 1" class="mt-6 border-t border-gray-200 pt-4">
            <div class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link 
                  v-if="props.industrySectors.current_page > 1"
                  :href="`/industry-sectors?page=${props.industrySectors.current_page - 1}`"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Previous
                </Link>
                <Link 
                  v-if="props.industrySectors.current_page < props.industrySectors.last_page"
                  :href="`/industry-sectors?page=${props.industrySectors.current_page + 1}`"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Next
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div class="flex items-center space-x-4">
                  <div>
                    <p class="text-sm text-gray-700">
                      Showing
                      <span class="font-medium">{{ (props.industrySectors.current_page - 1) * props.industrySectors.per_page + 1 }}</span>
                      to
                      <span class="font-medium">{{ Math.min(props.industrySectors.current_page * props.industrySectors.per_page, props.industrySectors.total) }}</span>
                      of
                      <span class="font-medium">{{ props.industrySectors.total }}</span>
                      results
                    </p>
                  </div>
                  <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600">Show:</label>
                    <select 
                      :value="pageSize" 
                      @change="changePageSize"
                      class="w-20 h-8 border border-gray-300 rounded-md px-2 py-1 text-sm"
                    >
                      <option value="10">10</option>
                      <option value="15">15</option>
                      <option value="25">25</option>
                      <option value="50">50</option>
                    </select>
                  </div>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <!-- Previous Page -->
                    <Link 
                      v-if="props.industrySectors.current_page > 1"
                      :href="`/industry-sectors?page=${props.industrySectors.current_page - 1}&per_page=${pageSize}`"
                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                      <span class="sr-only">Previous</span>
                      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010 1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </Link>
                    
                    <!-- Page Numbers -->
                    <template v-for="page in getVisiblePages()" :key="page">
                      <Link 
                        v-if="page !== '...'"
                        :href="`/industry-sectors?page=${page}&per_page=${pageSize}`"
                        :class="[
                          'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                          page === props.industrySectors.current_page
                            ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                            : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                        ]"
                      >
                        {{ page }}
                      </Link>
                      <span 
                        v-else
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                      >
                        ...
                      </span>
                    </template>
                    
                    <!-- Next Page -->
                    <Link 
                      v-if="props.industrySectors.current_page < props.industrySectors.last_page"
                      :href="`/industry-sectors?page=${props.industrySectors.current_page + 1}&per_page=${pageSize}`"
                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                      <span class="sr-only">Next</span>
                      <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                      </svg>
                    </Link>
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:open="showDeleteDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Delete Industry Sector</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete "{{ sectorToDelete?.name }}"? This action cannot be undone.
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
import { ref, computed } from 'vue'
import { router, Link } from '@inertiajs/vue3'
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
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue'

interface IndustrySector {
  id: number
  name: string
  description: string
  created_at: string
  updated_at: string
  expeditions_count?: number
}

interface Props {
  industrySectors: {
    data: IndustrySector[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

const props = defineProps<Props>()

const showDeleteDialog = ref(false)
const sectorToDelete = ref<IndustrySector | null>(null)
const isDeleting = ref(false)
const searchQuery = ref('')
const pageSize = ref<number>(props.industrySectors.per_page)

const filteredSectors = computed(() => {
  if (!searchQuery.value) return props.industrySectors.data
  
  return props.industrySectors.data.filter(sector =>
    sector.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    sector.description.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const getVisiblePages = (): (number | string)[] => {
  const current = props.industrySectors.current_page
  const last = props.industrySectors.last_page
  const delta = 2
  
  const range: number[] = []
  const rangeWithDots: (number | string)[] = []
  
  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i)
  }
  
  if (current - delta > 2) {
    rangeWithDots.push(1, '...')
  } else {
    rangeWithDots.push(1)
  }
  
  rangeWithDots.push(...range)
  
  if (current + delta < last - 1) {
    rangeWithDots.push('...', last)
  } else if (last > 1) {
    rangeWithDots.push(last)
  }
  
  return rangeWithDots
}

const changePageSize = (event: Event) => {
  const target = event.target as HTMLSelectElement
  const newSize = parseInt(target.value)
  pageSize.value = newSize
  window.location.href = `/industry-sectors?page=1&per_page=${newSize}`
}

const deleteSector = (sector: IndustrySector) => {
  sectorToDelete.value = sector
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  if (!sectorToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('industry-sectors.destroy', sectorToDelete.value.id), {
    onFinish: () => {
      isDeleting.value = false
      showDeleteDialog.value = false
      sectorToDelete.value = null
    }
  })
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>
