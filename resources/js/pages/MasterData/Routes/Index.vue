<template>
  <AppLayout title="Routes">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Routes</h1>
          <p class="text-muted-foreground">Manage transportation routes for expeditions</p>
        </div>
        <Link :href="route('routes.create')" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
          <Icon name="plus" class="mr-2 h-4 w-4" />
          Add Route
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
                placeholder="Search routes by code, name, or description..."
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

      <!-- Routes Table -->
      <Card>
        <CardHeader>
          <div>
            <CardTitle>Routes List</CardTitle>
            <CardDescription>
              View and manage all transportation routes
            </CardDescription>
          </div>
        </CardHeader>
        <CardContent class="p-0">
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-4">Code</th>
                  <th class="text-left font-medium p-4">Name</th>
                  <th class="text-left font-medium p-4">Description</th>
                  <th class="text-left font-medium p-4">Created</th>
                  <th class="text-left font-medium p-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="routeItem in filteredRoutes" :key="routeItem.id" class="border-b hover:bg-gray-50 transition-colors">
                  <td class="p-4">
                    <span class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ routeItem.code }}</span>
                  </td>
                  <td class="p-4 font-medium">{{ routeItem.name }}</td>
                  <td class="p-4 text-gray-600">{{ routeItem.description }}</td>
                  <td class="p-4 text-gray-500">{{ formatDate(routeItem.created_at) }}</td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('routes.edit', routeItem.id)">
                          <Icon name="edit" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button 
                        variant="outline" 
                        size="sm" 
                        @click="deleteRoute(routeItem)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredRoutes.length === 0">
                  <td colspan="5" class="p-8 text-center text-gray-500">
                    <div class="flex flex-col items-center space-y-2">
                      <Icon name="map" class="h-12 w-12 text-gray-300" />
                      <p class="text-lg font-medium">No routes found</p>
                      <p class="text-sm">Create your first route to get started.</p>
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
          <DialogTitle>Delete Route</DialogTitle>
                      <DialogDescription>
              Are you sure you want to delete "{{ routeToDelete?.name }}"? This action cannot be undone.
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
import Heading from '@/components/Heading.vue'
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

interface Route {
  id: number
  name: string
  description: string
  created_at: string
  updated_at: string
  code: string
}

interface Props {
  routes: {
    data: Route[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

const props = defineProps<Props>()

const showDeleteDialog = ref(false)
const routeToDelete = ref<Route | null>(null)
const isDeleting = ref(false)
const searchQuery = ref('')
const pageSize = ref<number>(props.routes.per_page)

const filteredRoutes = computed(() => {
  if (!searchQuery.value) return props.routes.data
  
  return props.routes.data.filter(route =>
    route.code.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    route.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    route.description.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

const clearSearch = () => {
  searchQuery.value = ''
}

const getVisiblePages = (): (number | string)[] => {
  const current = props.routes.current_page
  const last = props.routes.last_page
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
  window.location.href = `/routes?page=1&per_page=${newSize}`
}

const deleteRoute = (route: Route) => {
  routeToDelete.value = route
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  if (!routeToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('routes.destroy', routeToDelete.value.id), {
    onSuccess: () => {
      showDeleteDialog.value = false
      routeToDelete.value = null
      isDeleting.value = false
    },
    onError: () => {
      isDeleting.value = false
    }
  })
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString()
}
</script>
