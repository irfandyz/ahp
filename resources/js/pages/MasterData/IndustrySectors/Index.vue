<template>
  <AppLayout title="Industry Sectors">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Industry Sectors</h1>
          <p class="text-muted-foreground">Manage industry sectors for expeditions</p>
        </div>
        <Link :href="route('industry-sectors.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
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
                placeholder="Search industry sectors by name or description..."
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
        <CardContent class="p-0">
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-4">Name</th>
                  <th class="text-left font-medium p-4">Description</th>
                  <th class="text-left font-medium p-4">Expeditions</th>
                  <th class="text-left font-medium p-4">Created</th>
                  <th class="text-left font-medium p-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="sector in filteredSectors" :key="sector.id" class="border-b hover:bg-gray-50 transition-colors">
                  <td class="p-4 font-medium">{{ sector.name }}</td>
                  <td class="p-4 text-gray-600">{{ sector.description }}</td>
                  <td class="p-4 text-gray-500">
                    <div class="flex items-center">
                      <Icon name="file-text" class="h-4 w-4 mr-2 text-gray-400" />
                      {{ sector.expeditions_count || 0 }}
                    </div>
                  </td>
                  <td class="p-4 text-gray-500">{{ formatDate(sector.created_at) }}</td>
                  <td class="p-4">
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
                    <div class="flex flex-col items-center space-y-2">
                      <Icon name="building" class="h-12 w-12 text-gray-300" />
                      <p class="text-lg font-medium">No industry sectors found</p>
                      <p class="text-sm">Create your first industry sector to get started.</p>
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
          <DialogTitle>Delete Industry Sector</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete "{{ sectorToDelete?.name }}"? This action cannot be undone.
            <span v-if="sectorToDelete?.expeditions_count && sectorToDelete.expeditions_count > 0" class="block mt-2 text-red-600">
              This sector has {{ sectorToDelete.expeditions_count }} expeditions and cannot be deleted.
            </span>
          </DialogDescription>
        </DialogHeader>
        <DialogFooter>
          <Button variant="outline" @click="showDeleteDialog = false">
            Cancel
          </Button>
          <Button 
            v-if="!sectorToDelete?.expeditions_count || sectorToDelete.expeditions_count === 0"
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
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const month = (date.getMonth() + 1).toString().padStart(2, '0')
  const year = date.getFullYear()
  return `${day}-${month}-${year}`
}

const clearSearch = () => {
  searchQuery.value = ''
}
</script>
