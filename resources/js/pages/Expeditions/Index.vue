<template>
  <AppLayout title="All Expeditions">
    
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">All Expeditions</h1>
          <p class="text-muted-foreground">View and manage all transportation expeditions</p>
        </div>
        <div class="flex items-center space-x-3">
          <Button 
            @click="showConfigModal = true" 
            variant="outline" 
            size="sm" 
            class="bg-white hover:bg-gray-50 border-gray-300 text-gray-700 hover:border-blue-400 transition-all duration-200 shadow-sm"
          >
            <Icon name="settings" class="h-4 w-4 mr-2" />
            Cost Configuration
          </Button>
        </div>
      </div>

      <!-- Success/Error Messages -->
      <div v-if="($page.props.flash as any)?.success" class="bg-green-50 border border-green-200 rounded-lg p-4">
        <p class="text-green-800">{{ ($page.props.flash as any)?.success }}</p>
      </div>
      
      <div v-if="($page.props.flash as any)?.error" class="bg-red-50 border border-red-200 rounded-lg p-4">
        <p class="text-red-800">{{ ($page.props.flash as any)?.error }}</p>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <Icon name="truck" class="h-6 w-6 text-blue-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Expeditions</p>
              <p class="text-2xl font-bold text-gray-900">{{ props.expeditions.length }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <Icon name="building" class="h-6 w-6 text-green-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Vendor Expeditions</p>
              <p class="text-2xl font-bold text-gray-900">{{ getVendorCount() }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <Icon name="car" class="h-6 w-6 text-purple-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Fleet Expeditions</p>
              <p class="text-2xl font-bold text-gray-900">{{ getFleetCount() }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <Icon name="trending-up" class="h-6 w-6 text-yellow-600" />
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Sales</p>
              <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(getTotalSales()) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Search and Filters -->
      <div class="mb-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <div class="flex-1 max-w-md">
          <div class="relative">
            <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search expeditions..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        </div>
        
        <div class="flex items-center space-x-2">
          <!-- Expedition Type Filter -->
          <div class="flex items-center space-x-1 border border-gray-300 rounded-md">
            <button
              @click="filterType = ''"
              :class="[
                'px-3 py-2 text-sm font-medium rounded-l-md transition-colors',
                filterType === '' 
                  ? 'bg-blue-600 text-white' 
                  : 'bg-white text-gray-700 hover:bg-gray-50'
              ]"
            >
              All
            </button>
            <button
              @click="filterType = 'vendor'"
              :class="[
                'px-3 py-2 text-sm font-medium transition-colors',
                filterType === 'vendor' 
                  ? 'bg-blue-600 text-white' 
                  : 'bg-white text-gray-700 hover:bg-gray-50'
              ]"
            >
              Vendor
            </button>
            <button
              @click="filterType = 'fleet'"
              :class="[
                'px-3 py-2 text-sm font-medium rounded-r-md transition-colors',
                filterType === 'fleet' 
                  ? 'bg-blue-600 text-white' 
                  : 'bg-white text-gray-700 hover:bg-gray-50'
              ]"
            >
              Fleet
            </button>
          </div>
          
          <Button as-child>
            <Link :href="route('expeditions.create')">
              <Icon name="plus" class="h-4 w-4 mr-2" />
              New Expedition
            </Link>
          </Button>
        </div>
      </div>

      <!-- Expeditions Table -->
      <Card>
        <CardHeader>
          <div>
            <CardTitle>Expeditions List</CardTitle>
            <CardDescription>
              View and manage all transportation expeditions
            </CardDescription>
          </div>
        </CardHeader>
        <CardContent>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b">
                  <th class="text-left font-medium p-2">Order #</th>
                  <th class="text-left font-medium p-2">Type</th>
                  <th class="text-left font-medium p-2">Input Date</th>
                  <th class="text-left font-medium p-2">Travel Date</th>
                  <th class="text-left font-medium p-2">ETA (Days)</th>
                  <th class="text-left font-medium p-2">Origin</th>
                  <th class="text-left font-medium p-2">Destination</th>
                  <th class="text-left font-medium p-2">Industry Sector</th>
                  <th class="text-left font-medium p-2">Route</th>
                  <th class="text-left font-medium p-2">Vendor/Fleet</th>
                  <th class="text-left font-medium p-2">Sales</th>
                  <th class="text-left font-medium p-2">Cost</th>
                  <th class="text-left font-medium p-2">Profit</th>
                  <th class="text-left font-medium p-2">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="expedition in filteredExpeditions" :key="expedition.id" class="border-b hover:bg-gray-50">
                  <td class="p-2 font-medium">{{ expedition.order_number }}</td>
                  <td class="p-2">
                    <Badge :variant="expedition.expedition_type === 'fleet' ? 'default' : 'secondary'">
                      {{ expedition.expedition_type === 'fleet' ? 'Own Fleet' : 'Vendor' }}
                    </Badge>
                  </td>
                  <td class="p-2 text-gray-600">{{ formatDate(expedition.input_date) }}</td>
                  <td class="p-2 text-gray-600">{{ formatDate(expedition.travel_date) }}</td>
                  <td class="p-2 text-gray-600">{{ expedition.eta }} days</td>
                  <td class="p-2 text-gray-600">{{ expedition.origin }}</td>
                  <td class="p-2 text-gray-600">{{ expedition.destination }}</td>
                  <td class="p-2 text-gray-600">{{ expedition.industry_sector?.name || '-' }}</td>
                  <td class="p-2 text-gray-600">{{ expedition.route?.name || '-' }}</td>
                  <td class="p-2 text-gray-600">
                    <div v-if="expedition.expedition_type === 'vendor'">
                      {{ expedition.vendor?.company || '-' }}
                    </div>
                    <div v-else-if="expedition.expedition_type === 'fleet'">
                      {{ expedition.fleet?.plate_number || '-' }}
                      <div v-if="expedition.fleet?.fleet_type" class="text-xs text-gray-500">
                        {{ expedition.fleet.fleet_type.name }}
                      </div>
                    </div>
                    <div v-else>-</div>
                  </td>
                  <td class="p-2 text-gray-600">
                                          <div v-if="expedition.expedition_type === 'vendor'">
                        {{ formatCurrency(expedition.vendor_costs?.sales_amount || 0) }}
                      </div>
                      <div v-else-if="expedition.expedition_type === 'fleet'">
                        {{ formatCurrency(expedition.fleet_costs?.sales_amount || 0) }}
                      </div>
                    <div v-else>-</div>
                  </td>
                  <td class="p-2 text-gray-600">
                    <div v-if="expedition.expedition_type === 'vendor'">
                      {{ formatCurrency(expedition.vendor_costs?.total_cost || 0) }}
                    </div>
                    <div v-else-if="expedition.expedition_type === 'fleet'">
                      {{ formatCurrency(expedition.fleet_costs?.total_cost || 0) }}
                    </div>
                    <div v-else>-</div>
                  </td>
                  <td class="p-2">
                    <span :class="[
                      'font-medium',
                      getSalesAmount(expedition) - getTotalCost(expedition) >= 0 ? 'text-green-600' : 'text-red-600'
                    ]">
                      {{ formatCurrency(getSalesAmount(expedition) - getTotalCost(expedition)) }}
                      <span class="text-sm ml-1">
                        ({{ getProfitPercentage(expedition) }})
                      </span>
                    </span>
                  </td>
                  <td class="p-2">
                    <div class="flex items-center space-x-2">
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('expeditions.show', expedition.id)">
                          <Icon name="eye" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button variant="outline" size="sm" as-child>
                        <Link :href="route('expeditions.edit', expedition.id)">
                          <Icon name="edit" class="h-4 w-4" />
                        </Link>
                      </Button>
                      <Button 
                        variant="outline" 
                        size="sm" 
                        @click="deleteExpedition(expedition)"
                        class="text-red-600 hover:text-red-700"
                      >
                        <Icon name="trash" class="h-4 w-4" />
                      </Button>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredExpeditions.length === 0">
                  <td colspan="14" class="p-8 text-center text-gray-500">
                    No expeditions found. Create your first expedition to get started.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>


        </CardContent>
      </Card>
    </div>

    <!-- Cost Configuration Modal -->
    <div v-if="showConfigModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
        <div @click="showConfigModal = false" class="absolute inset-0 -z-10"></div>
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-xl">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Cost Configuration</h3>
            <button 
              @click="showConfigModal = false" 
              class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors"
            >
              <Icon name="x" class="h-6 w-6" />
            </button>
          </div>
        </div>
        
        <div class="p-6">
        
        <div class="space-y-6">
          <!-- Fuel Cost Configuration -->
          <div class="border border-gray-200 rounded-lg p-4 bg-gray-50/30">
            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
              <Icon name="fuel" class="h-5 w-5 mr-2 text-blue-600" />
              Fuel Cost Configuration
            </h4>
            <div>
              <label for="fuel_price_per_liter" class="block text-sm font-medium text-gray-700 mb-2">
                Solar Price per Liter (Rp)
              </label>
              <input
                id="fuel_price_per_liter"
                v-model="configForm.fuel_price_per_liter"
                type="number"
                min="0"
                step="100"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                placeholder="Enter price per liter"
              />
            </div>
            <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
              <p class="text-sm text-blue-800">
                <strong>Formula:</strong> Fuel Cost = Distance ÷ 8 × Solar Price per Liter
              </p>
            </div>
          </div>

          <!-- Driver Cost Configuration -->
          <div class="border border-gray-200 rounded-lg p-4 bg-gray-50/30">
            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
              <Icon name="user" class="h-5 w-5 mr-2 text-green-600" />
              Driver Cost Configuration
            </h4>
            
            <!-- Driver Cost Type Switch -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Driver Cost Calculation Method</label>
              <div class="flex items-center space-x-4">
                <label class="flex items-center">
                  <input
                    type="radio"
                    v-model="configForm.driver_cost_type"
                    value="per_day"
                    class="mr-2 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="text-sm text-gray-700">Per Day (Based on ETA)</span>
                </label>
                <label class="flex items-center">
                  <input
                    type="radio"
                    v-model="configForm.driver_cost_type"
                    value="per_km"
                    class="mr-2 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="text-sm text-gray-700">Per Kilometer (Based on Distance)</span>
                </label>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="driver_cost_per_day" class="block text-sm font-medium text-gray-700 mb-2">
                  Driver Cost per Day (Rp)
                </label>
                <input
                  id="driver_cost_per_day"
                  v-model="configForm.driver_cost_per_day"
                  type="number"
                  min="0"
                  step="1000"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                  placeholder="Enter cost per day"
                />
              </div>
              <div>
                <label for="driver_cost_per_km" class="block text-sm font-medium text-gray-700 mb-2">
                  Driver Cost per Kilometer (Rp)
                </label>
                <input
                  id="driver_cost_per_km"
                  v-model="configForm.driver_cost_per_km"
                  type="number"
                  min="0"
                  step="100"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
                  placeholder="Enter cost per km"
                />
              </div>
            </div>

            <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
              <p class="text-sm text-green-800">
                <strong>Formula:</strong> 
                <span v-if="configForm.driver_cost_type === 'per_day'">
                  Driver Cost = ETA (Days) × Driver Cost per Day
                </span>
                <span v-else>
                  Driver Cost = Distance (KM) × Driver Cost per Kilometer
                </span>
              </p>
            </div>
          </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
          <Button @click="showConfigModal = false" variant="outline" class="px-4 py-2">
            Cancel
          </Button>
          <Button @click="saveConfiguration" :disabled="isSavingConfig" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white">
            <Icon v-if="isSavingConfig" name="loader" class="mr-2 h-4 w-4 animate-spin" />
            {{ isSavingConfig ? 'Saving...' : 'Save Configuration' }}
          </Button>
        </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Dialog -->
    <Dialog v-model:open="showDeleteDialog">
      <DialogContent>
        <DialogHeader>
          <DialogTitle>Delete Expedition</DialogTitle>
          <DialogDescription>
            Are you sure you want to delete expedition "{{ expeditionToDelete?.order_number }}"? This action cannot be undone.
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
import Badge from '@/components/ui/badge/Badge.vue'

interface IndustrySector {
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

interface Driver {
  id: number
  name: string
  phone?: string
  email?: string
}

interface FleetType {
  id: number
  name: string
}

interface Fleet {
  id: number
  plate_number: string
  fleet_type_id: number
  fleet_type?: FleetType
}

interface ExpeditionCostFleet {
  id: number
  sales_amount?: number
  total_cost?: number
  transportation_cost?: number
  fuel_cost?: number
  tolling_cost?: number
  port_cost?: number
  insurance_cost?: number
  driver_cost?: number
  deposit_cost?: number
  other_cost?: number
  calculateTotalCost(): number
}

interface ExpeditionCostVendor {
  id: number
  sales_amount?: number
  total_cost?: number
  vendor_cost?: number
  deposit_cost?: number
  other_cost?: number
  calculateTotalCost(): number
}

interface Expedition {
  id: number
  order_number: string
  input_date: string
  travel_date: string
  origin: string
  destination: string
  description?: string
  industry_sector_id: number
  route_id: number
  expedition_type: 'vendor' | 'fleet'
  vendor_id?: number
  fleet_id?: number
  driver_id?: number
  eta: number
  user_id: number
  created_at: string
  updated_at: string
  industry_sector?: IndustrySector
  route?: { id: number; name: string; description?: string }
  vendor?: Vendor
  fleet?: Fleet
  driver?: Driver
  // Cost relationships
  fleet_costs?: ExpeditionCostFleet
  vendor_costs?: ExpeditionCostVendor
  // Accessor methods from the Expedition model
  sales_amount?: number
  total_cost?: number
}

interface Props {
  expeditions: Expedition[]
}

const props = defineProps<Props>()

// Debug: Log data yang diterima
console.log('Expeditions data received:', props.expeditions)
console.log('Sample expedition:', props.expeditions[0])
if (props.expeditions[0]) {
  console.log('Sample fleet costs:', props.expeditions[0].fleet_costs)
  console.log('Sample vendor costs:', props.expeditions[0].vendor_costs)
}

// Configuration modal states
const showConfigModal = ref(false)
const isSavingConfig = ref(false)

// Configuration form
const configForm = ref({
  fuel_price_per_liter: 15000, // Default solar price
  driver_cost_type: 'per_day', // Default to per day calculation
  driver_cost_per_day: 200000, // Default cost per day
  driver_cost_per_km: 5000, // Default cost per km
})

const showDeleteDialog = ref(false)
const expeditionToDelete = ref<Expedition | null>(null)
const isDeleting = ref(false)
const searchQuery = ref('')

const filterType = ref('')

const filteredExpeditions = computed(() => {
  if (!searchQuery.value && filterType.value === '') return props.expeditions
  
  const query = searchQuery.value.toLowerCase()
  return props.expeditions.filter((expedition: Expedition) => {
    const matchesQuery = expedition.order_number.toLowerCase().includes(query) ||
                         expedition.origin.toLowerCase().includes(query) ||
                         expedition.destination.toLowerCase().includes(query) ||
                         expedition.industry_sector?.name.toLowerCase().includes(query) ||
                         expedition.route?.name.toLowerCase().includes(query) ||
                         expedition.vendor?.company.toLowerCase().includes(query) ||
                         expedition.fleet?.plate_number.toLowerCase().includes(query)

    const matchesFilter = filterType.value === '' ||
                          (filterType.value === 'vendor' && expedition.expedition_type === 'vendor') ||
                          (filterType.value === 'fleet' && expedition.expedition_type === 'fleet')

    return matchesQuery && matchesFilter
  })
})



const deleteExpedition = (expedition: Expedition) => {
  expeditionToDelete.value = expedition
  showDeleteDialog.value = true
}

const confirmDelete = () => {
  if (!expeditionToDelete.value) return
  
  isDeleting.value = true
  router.delete(route('expeditions.destroy', expeditionToDelete.value.id), {
    onFinish: () => {
      isDeleting.value = false
      showDeleteDialog.value = false
      expeditionToDelete.value = null
    }
  })
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const month = (date.getMonth() + 1).toString().padStart(2, '0')
  const year = date.getFullYear()
  return `${day}/${month}/${year}`
}

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}

const getSalesAmount = (expedition: Expedition) => {
  // Try to get sales amount from cost relationships first, then fallback to expedition
  if (expedition.expedition_type === 'vendor' && expedition.vendor_costs?.sales_amount) {
    return expedition.vendor_costs.sales_amount
  } else if (expedition.expedition_type === 'fleet' && expedition.fleet_costs?.sales_amount) {
    return expedition.fleet_costs.sales_amount
  }
  // Fallback to expedition sales_amount
  return expedition.sales_amount || 0
}

const getTotalCost = (expedition: Expedition) => {
  if (expedition.expedition_type === 'vendor') {
    return calculateVendorTotalCost(expedition)
  } else if (expedition.expedition_type === 'fleet') {
    return calculateFleetTotalCost(expedition)
  }
  return expedition.total_cost || 0
}

const getVendorCount = () => {
  return props.expeditions.filter((expedition: Expedition) => expedition.expedition_type === 'vendor').length
}

const getFleetCount = () => {
  return props.expeditions.filter((expedition: Expedition) => expedition.expedition_type === 'fleet').length
}

const getTotalSales = () => {
  return props.expeditions.reduce((sum: number, expedition: Expedition) => sum + (expedition.sales_amount || 0), 0)
}

// Calculate profit percentage
const getProfitPercentage = (expedition: Expedition) => {
  const salesAmount = getSalesAmount(expedition)
  const totalCost = getTotalCost(expedition)
  
  if (totalCost === 0) return salesAmount > 0 ? '+∞%' : '0%'
  
  const profit = salesAmount - totalCost
  const percentage = (profit / totalCost) * 100
  
  if (percentage >= 0) {
    return `+${percentage.toFixed(1)}%`
  } else {
    return `${percentage.toFixed(1)}%`
  }
}

// Calculate vendor total cost
const calculateVendorTotalCost = (expedition: Expedition) => {
  if (expedition.expedition_type !== 'vendor') return 0
  
  const vendorCosts = expedition.vendor_costs
  if (vendorCosts) {
    const vendorCost = parseFloat(vendorCosts.vendor_cost?.toString() || '0')
    const depositCost = parseFloat(vendorCosts.deposit_cost?.toString() || '0')
    const otherCost = parseFloat(vendorCosts.other_cost?.toString() || '0')
    const total = vendorCost + depositCost + otherCost
    
    // Debug logging
    console.log('Vendor costs:', { vendorCost, depositCost, otherCost, total })
    
    return total
  }
  
  // Fallback to expedition total_cost if vendorCosts not available
  console.log('Vendor costs not available, using fallback:', expedition.total_cost)
  return expedition.total_cost || 0
}

// Calculate fleet total cost
const calculateFleetTotalCost = (expedition: Expedition) => {
  if (expedition.expedition_type !== 'fleet') return 0
  
  const fleetCosts = expedition.fleet_costs
  if (fleetCosts) {
    const transportationCost = parseFloat(fleetCosts.transportation_cost?.toString() || '0')
    const fuelCost = parseFloat(fleetCosts.fuel_cost?.toString() || '0')
    const tollingCost = parseFloat(fleetCosts.tolling_cost?.toString() || '0')
    const portCost = parseFloat(fleetCosts.port_cost?.toString() || '0')
    const insuranceCost = parseFloat(fleetCosts.insurance_cost?.toString() || '0')
    const driverCost = parseFloat(fleetCosts.driver_cost?.toString() || '0')
    const depositCost = parseFloat(fleetCosts.deposit_cost?.toString() || '0')
    const otherCost = parseFloat(fleetCosts.other_cost?.toString() || '0')
    const total = transportationCost + fuelCost + tollingCost + portCost + insuranceCost + driverCost + depositCost + otherCost
    
    // Debug logging
    console.log('Fleet costs:', { transportationCost, fuelCost, tollingCost, portCost, insuranceCost, driverCost, depositCost, otherCost, total })
    
    return total
  }
  
  // Fallback to expedition total_cost if fleetCosts not available
  console.log('Fleet costs not available, using fallback:', expedition.total_cost)
  return expedition.total_cost || 0
}

// Save configuration function
const saveConfiguration = () => {
  isSavingConfig.value = true
  
  // Save to localStorage for now (can be extended to save to backend)
  localStorage.setItem('expedition_cost_config', JSON.stringify(configForm.value))
  
  setTimeout(() => {
    isSavingConfig.value = false
    showConfigModal.value = false
    // You can add a success message here
  }, 1000)
}

// Load configuration on component mount
const loadConfiguration = () => {
  const savedConfig = localStorage.getItem('expedition_cost_config')
  if (savedConfig) {
    try {
      const config = JSON.parse(savedConfig)
      configForm.value = { ...configForm.value, ...config }
    } catch (error) {
      console.error('Error loading configuration:', error)
    }
  }
}

// Load configuration when component mounts
loadConfiguration()
</script>

<style scoped>
table {
  width: 100%;
}

th, td {
  padding: 0.5rem;
  text-align: left;
  vertical-align: top;
}

.overflow-x-auto {
  scrollbar-width: thin;
  scrollbar-color: #cbd5e0 #f7fafc;
}

.overflow-x-auto::-webkit-scrollbar {
  height: 8px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f7fafc;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #cbd5e0;
  border-radius: 4px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #a0aec0;
}
</style>
