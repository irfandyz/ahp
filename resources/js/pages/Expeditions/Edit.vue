<template>
  <AppLayout title="Edit Expedition">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Edit Expedition</h1>
          <p class="text-muted-foreground">Update expedition information</p>
        </div>
        <Link :href="route('expeditions.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <Icon name="arrow-left" class="mr-2 h-4 w-4" />
          Back to List
        </Link>
      </div>

      <!-- Form -->
      <Card>
        <CardHeader>
          <CardTitle>Expedition Information</CardTitle>
          <CardDescription>
            Update the details below to modify the expedition.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">
                  Order Number <span class="text-red-500">*</span>
                </label>
                <input
                  id="order_number"
                  v-model="form.order_number"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter order number"
                />
                <div v-if="form.errors.order_number" class="mt-1 text-sm text-red-600">
                  {{ form.errors.order_number }}
                </div>
              </div>

              <div>
                <label for="input_date" class="block text-sm font-medium text-gray-700 mb-2">
                  Input Date <span class="text-red-500">*</span>
                </label>
                <input
                  id="input_date"
                  v-model="form.input_date"
                  type="date"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <div v-if="form.errors.input_date" class="mt-1 text-sm text-red-600">
                  {{ form.errors.input_date }}
                </div>
              </div>

              <div>
                <label for="etd" class="block text-sm font-medium text-gray-700 mb-2">
                  ETD (Estimated Time of Departure) <span class="text-red-500">*</span>
                </label>
                <input
                  id="etd"
                  v-model="form.etd"
                  type="date"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <div v-if="form.errors.etd" class="mt-1 text-sm text-red-600">
                  {{ form.errors.etd }}
                </div>
              </div>

              <div>
                <label for="eta" class="block text-sm font-medium text-gray-700 mb-2">
                  ETA (Estimated Time of Arrival) <span class="text-red-500">*</span>
                </label>
                                  <input
                    id="eta"
                    v-model="form.eta"
                    type="date"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                <div v-if="form.errors.eta" class="mt-1 text-sm text-red-600">
                  {{ form.errors.eta }}
                </div>
              </div>

              <div>
                <label for="origin" class="block text-sm font-medium text-gray-700 mb-2">
                  Origin <span class="text-red-500">*</span>
                </label>
                <input
                  id="origin"
                  v-model="form.origin"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter origin location"
                />
                <div v-if="form.errors.origin" class="mt-1 text-sm text-red-600">
                  {{ form.errors.origin }}
                </div>
              </div>

              <div>
                <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">
                  Destination <span class="text-red-500">*</span>
                </label>
                <input
                  id="destination"
                  v-model="form.destination"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter destination location"
                />
                <div v-if="form.errors.destination" class="mt-1 text-sm text-red-600">
                  {{ form.errors.destination }}
                </div>
              </div>

              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                  Description
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter expedition description (optional)"
                ></textarea>
                <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                  {{ form.errors.description }}
                </div>
              </div>

              <div>
                <label for="industry_sector_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Industry Sector <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="industrySectorSearch"
                    type="text"
                    placeholder="Search industry sector..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @focus="showIndustrySectorDropdown = true"
                    @blur="handleIndustrySectorBlur"
                    @input="filterIndustrySectors"
                  />
                  <div v-if="showIndustrySectorDropdown && filteredIndustrySectors.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <div
                      v-for="sector in filteredIndustrySectors"
                      :key="sector.id"
                      @mousedown="selectIndustrySector(sector)"
                      class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                    >
                      {{ sector.name }}
                    </div>
                  </div>

                </div>
                <div v-if="form.errors.industry_sector_id" class="mt-1 text-sm text-red-600">
                  {{ form.errors.industry_sector_id }}
                </div>
              </div>

              <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Category <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="categorySearch"
                    type="text"
                    placeholder="Search category..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @focus="showCategoryDropdown = true"
                    @blur="handleCategoryBlur"
                    @input="filterCategories"
                  />
                  <div v-if="showCategoryDropdown && filteredCategories.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <div
                      v-for="route in filteredCategories"
                      :key="route.id"
                      @mousedown="selectCategory(route)"
                      class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                    >
                      {{ route.name }}
                    </div>
                  </div>

                </div>
                <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                  {{ form.errors.category_id }}
                </div>
              </div>

              <div>
                <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Vendor <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model="vendorSearch"
                    type="text"
                    placeholder="Search vendor..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @focus="showVendorDropdown = true"
                    @blur="handleVendorBlur"
                    @input="filterVendors"
                  />
                  <div v-if="showVendorDropdown && filteredVendors.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <div
                      v-for="vendor in filteredVendors"
                      :key="vendor.id"
                      @mousedown="selectVendor(vendor)"
                      class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                    >
                      {{ vendor.company }}
                    </div>
                  </div>

                </div>
                <div v-if="form.errors.vendor_id" class="mt-1 text-sm text-red-600">
                  {{ form.errors.vendor_id }}
                </div>
              </div>

              <div>
                <label for="sales" class="block text-sm font-medium text-gray-700 mb-2">
                  Sales Amount <span class="text-red-500">*</span>
                </label>
                <input
                  id="sales"
                  v-model="form.sales"
                  type="number"
                  min="0"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter sales amount"
                />
                <div v-if="form.errors.sales" class="mt-1 text-sm text-red-600">
                  {{ form.errors.sales }}
                </div>
              </div>

              <div>
                <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">
                  Cost Amount <span class="text-red-500">*</span>
                </label>
                <input
                  id="cost"
                  v-model="form.cost"
                  type="number"
                  min="0"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter cost amount"
                />
                <div v-if="form.errors.cost" class="mt-1 text-sm text-red-600">
                  {{ form.errors.cost }}
                </div>
              </div>
            </div>

            <div class="flex justify-end space-x-3">
              <Link :href="route('expeditions.index')" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Cancel
              </Link>
              <Button type="submit" :disabled="form.processing" class="px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <Icon v-if="form.processing" name="loader" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Updating...' : 'Update Expedition' }}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'

interface IndustrySector {
  id: number
  name: string
  description: string
}

interface Route {
  id: number
  name: string
  description: string
}

interface Vendor {
  id: number
  company: string
  address: string
  city: string
  pic: string
  title_pic: string
  phone: string
  moda: string
  fleet: string
  area_service_coverage: string
}

interface Expedition {
  id: number
  order_number: string
  input_date: string
  etd: string
  origin: string
  destination: string
  description?: string
  industry_sector_id: number
  category_id: number
  sales: number
  cost: number
  vendor_id: number
  shipping_id?: number
  eta: string
  user_id: number
  created_at: string
  updated_at: string
}

interface Props {
  expedition: Expedition
  industrySectors: IndustrySector[]
  routes: Route[]
  vendors: Vendor[]
}

const props = defineProps<Props>()

// Search states
const industrySectorSearch = ref('')
const categorySearch = ref('')
const vendorSearch = ref('')

// Dropdown visibility states
const showIndustrySectorDropdown = ref(false)
const showCategoryDropdown = ref(false)
const showVendorDropdown = ref(false)

const form = useForm({
  order_number: props.expedition.order_number,
  input_date: props.expedition.input_date ? new Date(props.expedition.input_date).toISOString().split('T')[0] : '',
  etd: props.expedition.etd ? new Date(props.expedition.etd).toISOString().split('T')[0] : '',
  origin: props.expedition.origin,
  destination: props.expedition.destination,
  description: props.expedition.description || '',
  industry_sector_id: props.expedition.industry_sector_id,
  category_id: props.expedition.category_id,
  sales: props.expedition.sales,
  cost: props.expedition.cost,
  vendor_id: props.expedition.vendor_id,
  shipping_id: props.expedition.shipping_id || '',
  eta: props.expedition.eta ? new Date(props.expedition.eta).toISOString().split('T')[0] : '',
})

// Initialize search values with current selections
onMounted(() => {
  if (props.expedition.industry_sector_id) {
    const sector = props.industrySectors.find(s => s.id === props.expedition.industry_sector_id)
    if (sector) industrySectorSearch.value = sector.name
  }
  if (props.expedition.category_id) {
    const route = props.routes.find(r => r.id === props.expedition.category_id)
    if (route) categorySearch.value = route.name
  }
  if (props.expedition.vendor_id) {
    const vendor = props.vendors.find(v => v.id === props.expedition.vendor_id)
    if (vendor) vendorSearch.value = vendor.company
  }
})

// Filtered lists
const filteredIndustrySectors = computed(() => {
  if (!industrySectorSearch.value) return props.industrySectors
  return props.industrySectors.filter(sector =>
    sector.name.toLowerCase().includes(industrySectorSearch.value.toLowerCase())
  )
})

const filteredCategories = computed(() => {
  if (!categorySearch.value) return props.routes
  return props.routes.filter(route =>
    route.name.toLowerCase().includes(categorySearch.value.toLowerCase())
  )
})

const filteredVendors = computed(() => {
  if (!vendorSearch.value) return props.vendors
  return props.vendors.filter(vendor =>
    vendor.company.toLowerCase().includes(vendorSearch.value.toLowerCase())
  )
})

// Selection functions
const selectIndustrySector = (sector: IndustrySector) => {
  form.industry_sector_id = sector.id
  industrySectorSearch.value = sector.name
  showIndustrySectorDropdown.value = false
}

const selectCategory = (route: Route) => {
  form.category_id = route.id
  categorySearch.value = route.name
  showCategoryDropdown.value = false
}

const selectVendor = (vendor: Vendor) => {
  form.vendor_id = vendor.id
  vendorSearch.value = vendor.company
  showVendorDropdown.value = false
}

// Clear functions
const clearIndustrySector = () => {
  form.industry_sector_id = 0
  industrySectorSearch.value = ''
}

const clearCategory = () => {
  form.category_id = 0
  categorySearch.value = ''
}

const clearVendor = () => {
  form.vendor_id = 0
  vendorSearch.value = ''
}

// Filter functions
const filterIndustrySectors = () => {
  showIndustrySectorDropdown.value = true
}

const filterCategories = () => {
  showCategoryDropdown.value = true
}

const filterVendors = () => {
  showVendorDropdown.value = true
}

// Blur handler functions
const handleIndustrySectorBlur = () => {
  setTimeout(() => {
    showIndustrySectorDropdown.value = false
  }, 150)
}

const handleCategoryBlur = () => {
  setTimeout(() => {
    showCategoryDropdown.value = false
  }, 150)
}

const handleVendorBlur = () => {
  setTimeout(() => {
    showVendorDropdown.value = false
  }, 150)
}

// Get name functions
const getIndustrySectorName = (id: number | string) => {
  const sector = props.industrySectors.find(s => s.id === id)
  return sector ? sector.name : ''
}

const getCategoryName = (id: number | string) => {
  const route = props.routes.find(r => r.id === id)
  return route ? route.name : ''
}

const getVendorName = (id: number | string) => {
  const vendor = props.vendors.find(v => v.id === id)
  return vendor ? vendor.company : ''
}

// Close dropdowns when clicking outside
const handleClickOutside = (event: Event) => {
  const target = event.target as HTMLElement
  if (!target.closest('.relative')) {
    showIndustrySectorDropdown.value = false
    showCategoryDropdown.value = false
    showVendorDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

const submit = () => {
  form.put(route('expeditions.update', props.expedition.id))
}
</script>
