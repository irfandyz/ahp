<template>
  <AppLayout :title="`Expedition ${expedition.order_number}`">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Expedition Details</h1>
          <p class="text-muted-foreground">View detailed information for expedition {{ expedition.order_number }}</p>
        </div>
        <div class="flex items-center space-x-3">
          <Button variant="outline" as-child>
            <Link :href="route('expeditions.edit', expedition.id)">
              <Icon name="edit" class="h-4 w-4 mr-2" />
              Edit Expedition
            </Link>
          </Button>
          <Button variant="outline" as-child>
            <Link :href="route('expeditions.index')">
              <Icon name="arrow-left" class="h-4 w-4 mr-2" />
              Back to List
            </Link>
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

      <!-- Expedition Information -->
      <Card>
        <CardHeader>
          <div class="flex items-center justify-between">
            <div>
              <CardTitle class="flex items-center space-x-3">
                <Icon name="truck" class="h-6 w-6 text-blue-600" />
                <span>{{ expedition.order_number }}</span>
                <Badge :variant="expedition.expedition_type === 'fleet' ? 'default' : 'secondary'">
                  {{ expedition.expedition_type === 'fleet' ? 'Own Fleet' : 'Vendor' }}
                </Badge>
              </CardTitle>
              <CardDescription>
                {{ expedition.description || 'No description provided' }}
              </CardDescription>
            </div>
            <div class="text-right">
              <p class="text-sm text-gray-500">Created by</p>
              <p class="font-medium">{{ expedition.user?.name || 'Unknown' }}</p>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="space-y-4">
              <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Basic Information</h3>
              
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Input Date</label>
                  <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.input_date) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Travel Date</label>
                  <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.travel_date) }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Origin</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.origin }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Destination</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.destination }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">Distance</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.distance }} km</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700">ETA</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.eta }} days</p>
                </div>
              </div>
            </div>

            <!-- Route & Sector Information -->
            <div class="space-y-4">
              <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Route & Sector</h3>
              
              <div class="space-y-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700">Industry Sector</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.industry_sector?.name || 'Not specified' }}</p>
                  <p v-if="expedition.industry_sector?.description" class="mt-1 text-xs text-gray-500">
                    {{ expedition.industry_sector.description }}
                  </p>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700">Route</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.route?.name || 'Not specified' }}</p>
                  <p v-if="expedition.route?.description" class="mt-1 text-xs text-gray-500">
                    {{ expedition.route.description }}
                  </p>
                </div>
                
                <div v-if="expedition.detail_route">
                  <label class="block text-sm font-medium text-gray-700">Detail Route</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.detail_route }}</p>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Transportation Details -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="car" class="h-5 w-5 text-green-600" />
            <span>Transportation Details</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="expedition.expedition_type === 'vendor'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Vendor Company</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.vendor?.company || 'Not specified' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Vendor Address</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.vendor?.address || 'Not specified' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Vendor City</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.vendor?.city || 'Not specified' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">PIC</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.vendor?.pic || 'Not specified' }}</p>
              </div>
            </div>
          </div>
          
          <div v-else-if="expedition.expedition_type === 'fleet'" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Fleet Plate Number</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.fleet?.plate_number || 'Not specified' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Fleet Type</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.fleet?.fleet_type?.name || 'Not specified' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Driver</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.driver?.name || 'Not specified' }}</p>
              </div>
              <div v-if="expedition.driver?.phone">
                <label class="block text-sm font-medium text-gray-700">Driver Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.driver.phone }}</p>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Cost Information -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="trending-up" class="h-5 w-5 text-yellow-600" />
            <span>Cost Information</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="expedition.expedition_type === 'vendor' && expedition.vendor_costs" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-blue-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-blue-700">Sales Amount</label>
                <p class="mt-1 text-lg font-bold text-blue-900">{{ formatCurrency(expedition.vendor_costs.sales_amount || 0) }}</p>
              </div>
              <div class="bg-green-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-green-700">Total Cost</label>
                <p class="mt-1 text-lg font-bold text-green-900">{{ formatCurrency(expedition.vendor_costs.total_cost || 0) }}</p>
              </div>
              <div class="bg-purple-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-purple-700">Profit</label>
                <p class="mt-1 text-lg font-bold" :class="getProfitColor(expedition)">
                  {{ formatCurrency(getSalesAmount(expedition) - getTotalCost(expedition)) }}
                  <span class="text-sm ml-1">
                    ({{ getProfitPercentage(expedition) }})
                  </span>
                </p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Vendor Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.vendor_costs.vendor_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Deposit Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.vendor_costs.deposit_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Other Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.vendor_costs.other_cost || 0) }}</p>
              </div>
              <div v-if="expedition.vendor_costs.deposit_date">
                <label class="block text-sm font-medium text-gray-700">Deposit Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.vendor_costs.deposit_date) }}</p>
              </div>
            </div>
          </div>
          
          <div v-else-if="expedition.expedition_type === 'fleet' && expedition.fleetCosts" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-blue-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-blue-700">Sales Amount</label>
                <p class="mt-1 text-lg font-bold text-blue-900">{{ formatCurrency(expedition.fleetCosts.sales_amount || 0) }}</p>
              </div>
              <div class="bg-green-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-green-700">Total Cost</label>
                <p class="mt-1 text-lg font-bold text-green-900">{{ formatCurrency(expedition.fleetCosts.total_cost || 0) }}</p>
              </div>
              <div class="bg-purple-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-purple-700">Profit</label>
                <p class="mt-1 text-lg font-bold" :class="getProfitColor(expedition)">
                  {{ formatCurrency(getSalesAmount(expedition) - getTotalCost(expedition)) }}
                  <span class="text-sm ml-1">
                    ({{ getProfitPercentage(expedition) }})
                  </span>
                </p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Transportation Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.transportation_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Fuel Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.fuel_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Tolling Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.tolling_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Port Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.port_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Insurance Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.insurance_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Driver Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.driver_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Deposit Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.deposit_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Other Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleetCosts.other_cost || 0) }}</p>
              </div>
              <div v-if="expedition.fleetCosts.deposit_date">
                <label class="block text-sm font-medium text-gray-700">Deposit Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.fleetCosts.deposit_date) }}</p>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-8 text-gray-500">
            <Icon name="alert-circle" class="h-12 w-12 mx-auto text-gray-400 mb-4" />
            <p>No cost information available for this expedition.</p>
          </div>
        </CardContent>
      </Card>

      <!-- Description -->
      <Card v-if="getCostDescription(expedition)">
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="file-text" class="h-5 w-5 text-gray-600" />
            <span>Additional Description</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <p class="text-gray-700 whitespace-pre-wrap">{{ getCostDescription(expedition) }}</p>
        </CardContent>
      </Card>

      <!-- Timestamps -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="clock" class="h-5 w-5 text-gray-600" />
            <span>Timestamps</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Created At</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(expedition.created_at) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Last Updated</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDateTime(expedition.updated_at) }}</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import Badge from '@/components/ui/badge/Badge.vue'

interface User {
  id: number
  name: string
}

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
  deposit_date?: string
  description?: string
}

interface ExpeditionCostVendor {
  id: number
  sales_amount?: number
  total_cost?: number
  vendor_cost?: number
  deposit_cost?: number
  other_cost?: number
  deposit_date?: string
  description?: string
}

interface Expedition {
  id: number
  order_number: string
  input_date: string
  travel_date: string
  origin: string
  destination: string
  distance: number
  description?: string
  industry_sector_id: number
  route_id: number
  detail_route?: string
  expedition_type: 'vendor' | 'fleet'
  vendor_id?: number
  fleet_id?: number
  driver_id?: number
  eta: number
  user_id: number
  created_at: string
  updated_at: string
  user?: User
  industry_sector?: IndustrySector
  route?: { id: number; name: string; description?: string }
  vendor?: Vendor
  fleet?: Fleet
  driver?: Driver
  // Cost relationships
  fleetCosts?: ExpeditionCostFleet
  vendorCosts?: ExpeditionCostVendor
  // Accessor methods from the Expedition model
  sales_amount?: number
  total_cost?: number
}

interface Props {
  expedition: Expedition
}

const props = defineProps<Props>()

// Helper functions
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const month = (date.getMonth() + 1).toString().padStart(2, '0')
  const year = date.getFullYear()
  return `${day}/${month}/${year}`
}

const formatDateTime = (dateString: string) => {
  const date = new Date(dateString)
  const day = date.getDate().toString().padStart(2, '0')
  const month = (date.getMonth() + 1).toString().padStart(2, '0')
  const year = date.getFullYear()
  const hours = date.getHours().toString().padStart(2, '0')
  const minutes = date.getMinutes().toString().padStart(2, '0')
  return `${day}/${month}/${year} ${hours}:${minutes}`
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
  if (expedition.expedition_type === 'vendor' && expedition.vendorCosts?.sales_amount) {
    return expedition.vendorCosts.sales_amount
  } else if (expedition.expedition_type === 'fleet' && expedition.fleetCosts?.sales_amount) {
    return expedition.fleetCosts.sales_amount
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

// Calculate vendor total cost
const calculateVendorTotalCost = (expedition: Expedition) => {
  if (expedition.expedition_type !== 'vendor') return 0
  
  const vendorCosts = expedition.vendorCosts
  if (vendorCosts) {
    const vendorCost = parseFloat(vendorCosts.vendor_cost?.toString() || '0')
    const depositCost = parseFloat(vendorCosts.deposit_cost?.toString() || '0')
    const otherCost = parseFloat(vendorCosts.other_cost?.toString() || '0')
    const total = vendorCost + depositCost + otherCost
    
    return total
  }
  
  return expedition.total_cost || 0
}

// Calculate fleet total cost
const calculateFleetTotalCost = (expedition: Expedition) => {
  if (expedition.expedition_type !== 'fleet') return 0
  
  const fleetCosts = expedition.fleetCosts
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
    
    return total
  }
  
  return expedition.total_cost || 0
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

// Get profit color class
const getProfitColor = (expedition: Expedition) => {
  const profit = getSalesAmount(expedition) - getTotalCost(expedition)
  return profit >= 0 ? 'text-green-600' : 'text-red-600'
}

// Get cost description
const getCostDescription = (expedition: Expedition) => {
  if (expedition.expedition_type === 'vendor' && expedition.vendor_costs?.description) {
    return expedition.vendor_costs.description
  } else if (expedition.expedition_type === 'fleet' && expedition.fleet_costs?.description) {
    return expedition.fleet_costs.description
  }
  return null
}
</script>

<style scoped>
.whitespace-pre-wrap {
  white-space: pre-wrap;
}
</style>
