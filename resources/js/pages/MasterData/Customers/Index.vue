<template>
  <AppLayout title="Customers">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
          <p class="text-muted-foreground">Manage your customer database and track customer relationships</p>
        </div>
        <Link 
          :href="route('customers.create')" 
          class="inline-flex items-center px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
        >
          <Icon name="plus" class="mr-2 h-4 w-4" />
          Add Customer
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
                placeholder="Search customers by name, email, phone, or PIC..."
                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                @input="handleSearchInput"
                @keyup.enter="performSearch"
              />
              <div v-if="isSearching" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <Icon name="loader" class="h-5 w-5 animate-spin text-blue-500" />
              </div>
            </div>
            <div class="flex items-center space-x-3">
              <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Show:</label>
                <select
                  v-model="filters.per_page"
                  class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors"
                  @change="performSearch"
                >
                  <option value="10">10</option>
                  <option value="15">15</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-500">per page</span>
              </div>
              <Button
                v-if="hasSearchQuery"
                variant="outline"
                @click="clearSearch"
                class="shrink-0"
                title="Clear search and filters"
              >
                <Icon name="x" class="mr-2 h-4 w-4" />
                Clear
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Customers Table -->
      <Card>
        <CardHeader>
          <div>
            <CardTitle>Customers List</CardTitle>
            <CardDescription>
              View and manage all customer information
            </CardDescription>
          </div>
        </CardHeader>
        <CardContent class="p-0">
          <!-- Table Summary -->
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <div class="flex items-center space-x-2">
                <Icon name="users" class="h-5 w-5 text-gray-500" />
                <span class="text-sm text-gray-600">
                  Showing
                  <span class="font-medium text-gray-900">{{ customers.from || 0 }}</span>
                  to
                  <span class="font-medium text-gray-900">{{ customers.to || 0 }}</span>
                  of
                  <span class="font-medium text-gray-900">{{ customers.total }}</span>
                  customers
                </span>
              </div>
              <div v-if="hasSearchQuery" class="text-sm text-gray-500">
                <span class="font-medium">{{ customers.data.length }}</span> results found
              </div>
            </div>
          </div>
          
          <div class="overflow-x-auto relative">
            <!-- Loading Overlay -->
            <div v-if="isSearching" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
              <div class="flex items-center space-x-2 text-blue-600">
                <Icon name="loader" class="h-6 w-6 animate-spin" />
                <span class="text-sm font-medium">Loading customers...</span>
              </div>
            </div>
            
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-4">Name</th>
                  <th class="text-left font-medium p-4">Contact Info</th>
                  <th class="text-left font-medium p-4">PIC</th>
                  <th class="text-left font-medium p-4">NPWP</th>
                  <th class="text-left font-medium p-4">Created</th>
                  <th class="text-left font-medium p-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="customer in customers.data" :key="customer.id" class="border-b hover:bg-gray-50 transition-colors">
                  <td class="p-4">
                    <div>
                      <p class="font-medium text-gray-900">{{ customer.name }}</p>
                      <p v-if="customer.address" class="text-sm text-gray-500 mt-1">{{ customer.address }}</p>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="space-y-1">
                      <div v-if="customer.phone" class="flex items-center text-sm text-gray-600">
                        <Icon name="phone" class="h-4 w-4 mr-2 text-gray-400" />
                        {{ customer.phone }}
                      </div>
                      <div v-if="customer.email" class="flex items-center text-sm text-gray-600">
                        <Icon name="mail" class="h-4 w-4 mr-2 text-gray-400" />
                        {{ customer.email }}
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <div v-if="customer.pic_name" class="space-y-1">
                      <p class="text-sm font-medium text-gray-900">{{ customer.pic_name }}</p>
                      <p v-if="customer.pic_phone" class="text-sm text-gray-500">{{ customer.pic_phone }}</p>
                    </div>
                    <span v-else class="text-gray-400">-</span>
                  </td>
                  <td class="p-4">
                    <span v-if="customer.npwp" class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ customer.npwp }}</span>
                    <span v-else class="text-gray-400">-</span>
                  </td>
                  <td class="p-4 text-gray-500">{{ formatDate(customer.created_at) }}</td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('customers.show', customer.id)">
                          <Icon name="eye" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('customers.edit', customer.id)">
                          <Icon name="edit" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button 
                        variant="outline" 
                        size="sm" 
                        @click="deleteCustomer(customer)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
                <tr v-if="customers.data.length === 0">
                  <td colspan="6" class="p-8 text-center text-gray-500">
                    <div class="flex flex-col items-center space-y-2">
                      <Icon name="users" class="h-12 w-12 text-gray-300" />
                      <p class="text-lg font-medium">No customers found</p>
                      <p class="text-sm">Create your first customer to get started.</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>

      <!-- Pagination -->
      <div v-if="customers.last_page > 1" class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
          <Link 
            v-if="customers.prev_page_url"
            :href="customers.prev_page_url"
            class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
          >
            Previous
          </Link>
          <Link 
            v-if="customers.next_page_url"
            :href="customers.next_page_url"
            class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
          >
            Next
          </Link>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ customers.from }}</span>
              to
              <span class="font-medium">{{ customers.to }}</span>
              of
              <span class="font-medium">{{ customers.total }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
              <template v-for="link in customers.links" :key="link.label">
                <Link 
                  v-if="link.url"
                  :href="link.url"
                  :class="[
                    'relative inline-flex items-center px-4 py-2 text-sm font-medium border',
                    link.active 
                      ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' 
                      : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                  ]"
                  v-html="link.label"
                />
                <span 
                  v-else
                  :class="[
                    'relative inline-flex items-center px-4 py-2 text-sm font-medium border cursor-not-allowed opacity-50',
                    'bg-white border-gray-300 text-gray-400'
                  ]"
                  v-html="link.label"
                />
              </template>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref, computed, watch, onUnmounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import Button from '@/components/ui/button/Button.vue'

interface Customer {
  id: number
  name: string
  address?: string
  phone?: string
  email?: string
  npwp?: string
  pic_name?: string
  pic_phone?: string
  expeditions_count: number
  created_at: string
  updated_at: string
}

interface PaginatedData {
  data: Customer[]
  current_page: number
  last_page: number
  per_page: number
  total: number
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
  from: number
  to: number
  prev_page_url: string | null
  next_page_url: string | null
}

interface Props {
  customers: PaginatedData
  filters?: {
    search?: string
    per_page?: number
  }
}

const props = defineProps<Props>()

// Search and filter state
const searchQuery = ref(props.filters?.search || '')
const filters = ref({
  per_page: props.filters?.per_page || 15
})
const isSearching = ref(false)

// Delete dialog state
const showDeleteDialog = ref(false)
const customerToDelete = ref<Customer | null>(null)
const isDeleting = ref(false)

// Computed properties
const hasSearchQuery = computed(() => {
  return searchQuery.value.trim() !== '' || filters.value.per_page !== 15
})

// Methods
const performSearch = () => {
  isSearching.value = true
  router.get(route('customers.index'), {
    search: searchQuery.value,
    per_page: filters.value.per_page
  }, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      isSearching.value = false
    },
    onError: () => {
      isSearching.value = false
    }
  })
}

const clearSearch = () => {
  searchQuery.value = ''
  filters.value.per_page = 15
  isSearching.value = true
  router.get(route('customers.index'), {}, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      isSearching.value = false
    },
    onError: () => {
      isSearching.value = false
    }
  })
}

const confirmDelete = (customer: Customer) => {
  if (!customer || !customer.id) return
  
  customerToDelete.value = customer
  showDeleteDialog.value = true
}

const deleteCustomer = (customer: Customer) => {
  if (!customer || !customer.id) return
  
  isDeleting.value = true
  router.delete(route('customers.destroy', customer.id), {
    onSuccess: () => {
      closeDeleteDialog()
      isDeleting.value = false
    },
    onError: () => {
      isDeleting.value = false
    }
  })
}

const closeDeleteDialog = () => {
  showDeleteDialog.value = false
  customerToDelete.value = null
  isDeleting.value = false
}

// Watch for prop changes
watch(() => props.filters, (newFilters) => {
  if (newFilters) {
    searchQuery.value = newFilters.search || ''
    filters.value.per_page = newFilters.per_page || 15
  }
}, { immediate: true })

// Cleanup effect
onUnmounted(() => {
  closeDeleteDialog()
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
})

// Debounced search to improve performance
let searchTimeout: number | null = null
const debouncedSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout)
  }
  searchTimeout = setTimeout(() => {
    performSearch()
  }, 300)
}

// Update the input event to use debounced search
const handleSearchInput = () => {
  debouncedSearch()
}

// Helper function for date formatting
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString()
}
</script>
