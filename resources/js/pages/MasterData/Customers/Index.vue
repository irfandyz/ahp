<template>
  <AppLayout title="Customers">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">Customers</h1>
          <p class="text-gray-600 mt-2">Manage your customer database and track customer relationships</p>
        </div>
        <Link 
          :href="route('customers.create')" 
          class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-sm"
        >
          <Icon name="plus" class="mr-2 h-5 w-5" />
          Add Customer
        </Link>
      </div>

      <!-- Search and Filters -->
      <Card>
        <CardContent class="p-6">
          <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4">
            <div class="flex-1 relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <Icon name="search" class="h-5 w-5 text-gray-400" />
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search customers by name, email, phone, or PIC..."
                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
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
              <button
                v-if="hasSearchQuery"
                @click="clearSearch"
                class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md transition-colors flex items-center space-x-2"
                title="Clear search and filters"
              >
                <Icon name="x" class="h-4 w-4" />
                <span>Clear</span>
              </button>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Customers Table -->
      <Card>
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
            
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="text-left font-semibold text-gray-900 p-4">Name</th>
                  <th class="text-left font-semibold text-gray-900 p-4">Contact Info</th>
                  <th class="text-left font-semibold text-gray-900 p-4">PIC</th>
                  <th class="text-left font-semibold text-gray-900 p-4">NPWP</th>
                  <th class="text-left font-semibold text-gray-900 p-4">Expeditions</th>
                  <th class="text-left font-semibold text-gray-900 p-4">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="customer in customers.data" :key="customer.id" class="hover:bg-gray-50 transition-colors duration-150">
                  <td class="p-4">
                    <div>
                      <div class="font-medium text-gray-900">{{ customer.name }}</div>
                      <div v-if="customer.address" class="text-sm text-gray-500 mt-1">{{ customer.address }}</div>
                    </div>
                  </td>
                  <td class="p-4">
                    <div class="text-sm space-y-1">
                      <div v-if="customer.phone" class="flex items-center">
                        <Icon name="phone" class="h-4 w-4 text-gray-400 mr-2" />
                        {{ customer.phone }}
                      </div>
                      <div v-if="customer.email" class="flex items-center">
                        <Icon name="mail" class="h-4 w-4 text-gray-400 mr-2" />
                        <span class="text-blue-600 hover:text-blue-800">{{ customer.email }}</span>
                      </div>
                    </div>
                  </td>
                  <td class="p-4">
                    <div v-if="customer.pic_name" class="text-sm">
                      <div class="font-medium text-gray-900">{{ customer.pic_name }}</div>
                      <div v-if="customer.pic_phone" class="text-gray-500 mt-1 flex items-center">
                        <Icon name="phone" class="h-4 w-4 text-gray-400 mr-1" />
                        {{ customer.pic_phone }}
                      </div>
                    </div>
                    <span v-else class="text-gray-400 text-sm">-</span>
                  </td>
                  <td class="p-4">
                    <span v-if="customer.npwp" class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ customer.npwp }}</span>
                    <span v-else class="text-gray-400 text-sm">-</span>
                  </td>
                  <td class="p-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      <Icon name="truck" class="h-3 w-3 mr-1" />
                      {{ customer.expeditions_count || 0 }} expeditions
                    </span>
                  </td>
                  <td class="p-4">
                    <div class="flex items-center space-x-2">
                      <Link 
                        :href="route('customers.show', customer.id)" 
                        class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition-colors"
                        title="View customer details"
                      >
                        <Icon name="eye" class="h-4 w-4" />
                      </Link>
                      <Link 
                        :href="route('customers.edit', customer.id)" 
                        class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-50 transition-colors"
                        title="Edit customer"
                      >
                        <Icon name="edit" class="h-4 w-4" />
                      </Link>
                      <button
                        @click="confirmDelete(customer)"
                        class="text-red-600 hover:text-red-800 disabled:opacity-50 disabled:cursor-not-allowed p-1 rounded hover:bg-red-50 transition-colors"
                        :disabled="(customer.expeditions_count || 0) > 0"
                        :title="(customer.expeditions_count || 0) > 0 ? 'Cannot delete customer with expeditions' : 'Delete customer'"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- No Data State -->
          <div v-if="customers.data.length === 0" class="p-12 text-center text-gray-500">
            <Icon name="users" class="mx-auto h-16 w-16 text-gray-300 mb-4" />
            <p class="text-xl font-medium text-gray-900 mb-2">
              {{ searchQuery ? 'No customers found matching your search' : 'No customers found' }}
            </p>
            <p class="text-gray-600 mb-6">
              {{ searchQuery ? 'Try adjusting your search terms or clear your search to see all customers.' : 'Create your first customer to get started with managing your customer database.' }}
            </p>
            <Link 
              v-if="!searchQuery" 
              :href="route('customers.create')" 
              class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
            >
              <Icon name="plus" class="mr-2 h-5 w-5" />
              Add Customer
            </Link>
          </div>
        </CardContent>
      </Card>

      <!-- Pagination -->
      <div v-if="customers.links && customers.links.length > 3" class="flex justify-center">
        <nav class="flex space-x-1">
          <Link
            v-for="link in customers.links"
            :key="link.label"
            :href="link.url || '#'"
            :class="[
              'px-4 py-2 text-sm font-medium rounded-md transition-colors',
              link.active
                ? 'bg-blue-600 text-white shadow-sm'
                : link.url
                ? 'text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 hover:border-gray-400'
                : 'text-gray-400 bg-white border border-gray-200 cursor-not-allowed'
            ]"
            v-html="link.label"
          />
        </nav>
      </div>
    </div>
  </AppLayout>
  
  <!-- Delete Confirmation Dialog -->
  <Dialog v-model:open="showDeleteDialog">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Delete Customer</DialogTitle>
        <DialogDescription>
          Are you sure you want to delete "{{ customerToDelete?.name }}"? This action cannot be undone.
          <span v-if="customerToDelete?.expeditions_count && customerToDelete.expeditions_count > 0" class="block mt-2 text-red-600">
            This customer has {{ customerToDelete.expeditions_count }} expeditions and cannot be deleted.
          </span>
        </DialogDescription>
      </DialogHeader>
      
      <DialogFooter>
        <button
          @click="closeDeleteDialog"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Cancel
        </button>
        <button
          v-if="customerToDelete && (!customerToDelete.expeditions_count || customerToDelete.expeditions_count === 0)"
          @click="deleteCustomer"
          :disabled="isDeleting"
          class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50"
        >
          <Icon v-if="isDeleting" name="loader" class="mr-2 h-4 w-4 animate-spin" />
          {{ isDeleting ? 'Deleting...' : 'Delete' }}
        </button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { ref, computed, watch, onUnmounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import Dialog from '@/components/ui/dialog/Dialog.vue'
import DialogContent from '@/components/ui/dialog/DialogContent.vue'
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue'
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue'
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue'
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue'

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

const deleteCustomer = () => {
  if (!customerToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('customers.destroy', customerToDelete.value.id), {
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
</script>
