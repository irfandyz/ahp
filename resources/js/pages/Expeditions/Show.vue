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
                  <label class="block text-sm font-medium text-gray-700">ETD (Estimated Time of Departure)</label>
                  <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.etd) }}</p>
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
                  <label class="block text-sm font-medium text-gray-700">ETA (Estimated Time of Arrival)</label>
                  <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.eta) }}</p>
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

      <!-- Customer Information -->
      <!-- Note: Ensure backend loads customer relationship with: ->with(['customer', 'industry_sector', 'route', 'vendor', 'fleet', 'driver']) -->
      <Card v-if="expedition.customer">
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="user" class="h-5 w-5 text-indigo-600" />
            <span>Customer Information</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Customer Info -->
            <div class="space-y-4">
              <h4 class="text-sm font-semibold text-gray-700 border-b pb-2">Basic Information</h4>
              <div>
                <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                <p class="mt-1 text-sm text-gray-900">
                  <Link 
                    :href="route('customers.show', expedition.customer.id)" 
                    class="text-indigo-600 hover:text-indigo-800 hover:underline transition-colors font-medium"
                  >
                    {{ expedition.customer.name }}
                  </Link>
                </p>
              </div>
              <div v-if="expedition.customer.npwp">
                <label class="block text-sm font-medium text-gray-700">NPWP</label>
                <p class="mt-1 text-sm text-gray-900 font-mono">{{ expedition.customer.npwp }}</p>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-4">
              <h4 class="text-sm font-semibold text-gray-700 border-b pb-2">Contact Information</h4>
              <div v-if="expedition.customer.address">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.customer.address }}</p>
              </div>
              <div v-if="expedition.customer.phone">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.customer.phone }}</p>
              </div>
              <div v-if="expedition.customer.email">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.customer.email }}</p>
              </div>
            </div>

            <!-- PIC Information -->
            <div class="space-y-4 md:col-span-2">
              <h4 class="text-sm font-semibold text-gray-700 border-b pb-2">Person in Charge (PIC)</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-if="expedition.customer.pic_name">
                  <label class="block text-sm font-medium text-gray-700">PIC Name</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.customer.pic_name }}</p>
                </div>
                <div v-if="expedition.customer.pic_phone">
                  <label class="block text-sm font-medium text-gray-700">PIC Phone</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.customer.pic_phone }}</p>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Consignee Information -->
      <Card v-if="expedition.consignee">
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="building" class="h-5 w-5 text-purple-600" />
            <span>Consignee Information</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Consignee Info -->
            <div class="space-y-4">
              <h4 class="text-sm font-semibold text-gray-700 border-b pb-2">Basic Information</h4>
              <div>
                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                <p class="mt-1 text-sm text-gray-900">
                  <Link 
                    :href="route('consignees.show', expedition.consignee.id)" 
                    class="text-purple-600 hover:text-purple-800 hover:underline transition-colors font-medium"
                  >
                    {{ expedition.consignee.company }}
                  </Link>
                </p>
              </div>
            </div>

            <!-- Contact Information -->
            <div class="space-y-4">
              <h4 class="text-sm font-semibold text-gray-700 border-b pb-2">Contact Information</h4>
              <div v-if="expedition.consignee.address">
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.consignee.address }}</p>
              </div>
              <div v-if="expedition.consignee.phone">
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.consignee.phone }}</p>
              </div>
              <div v-if="expedition.consignee.email">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900">{{ expedition.consignee.email }}</p>
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
          
          <div v-else-if="expedition.expedition_type === 'fleet' && expedition.fleet_costs" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-blue-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-blue-700">Sales Amount</label>
                <p class="mt-1 text-lg font-bold text-blue-900">{{ formatCurrency(expedition.fleet_costs.sales_amount || 0) }}</p>
              </div>
              <div class="bg-green-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-green-700">Total Cost</label>
                <p class="mt-1 text-lg font-bold text-green-900">{{ formatCurrency(expedition.fleet_costs.total_cost || 0) }}</p>
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
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.transportation_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Fuel Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.fuel_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Tolling Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.tolling_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Port Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.port_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Insurance Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.insurance_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Driver Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.driver_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Deposit Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.deposit_cost || 0) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Other Cost</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(expedition.fleet_costs.other_cost || 0) }}</p>
              </div>
              <div v-if="expedition.fleet_costs.deposit_date">
                <label class="block text-sm font-medium text-gray-700">Deposit Date</label>
                <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.fleet_costs.deposit_date) }}</p>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-8 text-gray-500">
            <Icon name="alert-circle" class="h-12 w-12 mx-auto text-gray-400 mb-4" />
            <p>No cost information available for this expedition.</p>
          </div>
        </CardContent>
      </Card>

      <!-- Goods Information -->
      <Card v-if="expedition.expedition_goods">
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="package" class="h-5 w-5 text-green-600" />
            <span>Goods Information</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="space-y-6">
            <!-- Goods Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-700 border-b pb-2">Goods Summary</h4>
                <div class="grid grid-cols-2 gap-4">
                  <div v-if="expedition.expedition_goods.total_goods">
                    <label class="block text-sm font-medium text-gray-700">Total Goods</label>
                    <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.total_goods }}</p>
                  </div>
                  <div v-if="expedition.expedition_goods.gross_weight">
                    <label class="block text-sm font-medium text-gray-700">Gross Weight</label>
                    <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.gross_weight }} kg</p>
                  </div>
                  <div v-if="expedition.expedition_goods.dimension_total">
                    <label class="block text-sm font-medium text-gray-700">Dimension Total</label>
                    <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.dimension_total }} m³</p>
                  </div>
                  <div v-if="expedition.expedition_goods.packaging">
                    <label class="block text-sm font-medium text-gray-700">Packaging</label>
                    <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.packaging }}</p>
                  </div>
                </div>
                <div v-if="expedition.expedition_goods.good_description">
                  <label class="block text-sm font-medium text-gray-700">Goods Description</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.good_description }}</p>
                </div>
                <div v-if="expedition.expedition_goods.special_instruction">
                  <label class="block text-sm font-medium text-gray-700">Special Instructions</label>
                  <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.special_instruction }}</p>
                </div>
              </div>
              
              <div class="space-y-4">
                <h4 class="text-sm font-semibold text-gray-700 border-b pb-2">Additional Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Dispatch Section -->
                  <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-800">Dispatch</h5>
                    <div v-if="expedition.expedition_goods.date_dispatch">
                      <label class="block text-xs font-medium text-gray-700">Date</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.expedition_goods.date_dispatch) }}</p>
                    </div>
                    <div v-if="expedition.expedition_goods.remark_dispatch">
                      <label class="block text-xs font-medium text-gray-700">Remarks</label>
                      <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.remark_dispatch }}</p>
                    </div>
                  </div>
                  
                  <!-- Receive Section -->
                  <div class="space-y-2">
                    <h5 class="text-sm font-medium text-gray-800">Receive</h5>
                    <div v-if="expedition.expedition_goods.date_receive">
                      <label class="block text-xs font-medium text-gray-700">Date</label>
                      <p class="mt-1 text-sm text-gray-900">{{ formatDate(expedition.expedition_goods.date_receive) }}</p>
                    </div>
                    <div v-if="expedition.expedition_goods.remark_receive">
                      <label class="block text-xs font-medium text-gray-700">Remarks</label>
                      <p class="mt-1 text-sm text-gray-900">{{ expedition.expedition_goods.remark_receive }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Dispatch & Receive Dates moved into Additional Details sections above -->
            
            <!-- Individual Goods Details -->
            <div v-if="expedition.expedition_goods.good_details && expedition.expedition_goods.good_details.length > 0">
              <h4 class="text-sm font-semibold text-gray-700 border-b pb-2 mb-4">Individual Goods Details</h4>
              <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded-lg">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">Name</th>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">Quantity</th>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">Unit</th>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="good in expedition.expedition_goods.good_details" :key="good.id" class="border-b border-gray-100 hover:bg-gray-50">
                      <td class="p-3">{{ good.name }}</td>
                      <td class="p-3">{{ good.quantity }}</td>
                      <td class="p-3">{{ good.unit }}</td>
                      <td class="p-3">{{ good.remark || '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- No Goods Message -->
            <div v-else-if="!expedition.expedition_goods.good_details || expedition.expedition_goods.good_details.length === 0" class="text-center py-8 text-gray-500">
              <Icon name="package" class="h-12 w-12 mx-auto text-gray-300 mb-4" />
              <p class="text-lg font-medium">No individual goods details</p>
              <p class="text-sm">Goods summary information is available but no individual items have been added yet.</p>
            </div>
          </div>
        </CardContent>
      </Card>
      
      <!-- No Goods Message -->
      <Card v-else>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="package" class="h-5 w-5 text-gray-400" />
            <span>Goods Information</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div class="text-center py-8 text-gray-500">
            <Icon name="package" class="h-12 w-12 mx-auto text-gray-300 mb-4" />
            <p class="text-lg font-medium">No goods information available</p>
            <p class="text-sm">This expedition doesn't have any goods data yet.</p>
            <div class="mt-4">
              <Button as-child variant="outline">
                <Link :href="route('expeditions.goods.create', expedition.id)">
                  <Icon name="plus" class="h-4 w-4 mr-2" />
                  Add Goods
                </Link>
              </Button>
            </div>
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
  etd: string
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
  eta: string
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
  fleet_costs?: ExpeditionCostFleet
  vendor_costs?: ExpeditionCostVendor
  // Accessor methods from the Expedition model
  sales_amount?: number
  total_cost?: number
  customer?: {
    id: number
    name: string
    address?: string
    phone?: string
    email?: string
    npwp?: string
    pic_name?: string
    pic_phone?: string
  }
  consignee?: {
    id: number
    company: string
    address?: string
    phone?: string
    email?: string
  }
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

// Calculate vendor total cost
const calculateVendorTotalCost = (expedition: Expedition) => {
  if (expedition.expedition_type !== 'vendor') return 0
  
  const vendor_costs = expedition.vendor_costs
  if (vendor_costs) {
    const vendorCost = parseFloat(vendor_costs.vendor_cost?.toString() || '0')
    const depositCost = parseFloat(vendor_costs.deposit_cost?.toString() || '0')
    const otherCost = parseFloat(vendor_costs.other_cost?.toString() || '0')
    const total = vendorCost + depositCost + otherCost
    
    return total
  }
  
  return expedition.total_cost || 0
}

// Calculate fleet total cost
const calculateFleetTotalCost = (expedition: Expedition) => {
  if (expedition.expedition_type !== 'fleet') return 0
  
  const fleet_costs = expedition.fleet_costs
  if (fleet_costs) {
    const transportationCost = parseFloat(fleet_costs.transportation_cost?.toString() || '0')
    const fuelCost = parseFloat(fleet_costs.fuel_cost?.toString() || '0')
    const tollingCost = parseFloat(fleet_costs.tolling_cost?.toString() || '0')
    const portCost = parseFloat(fleet_costs.port_cost?.toString() || '0')
    const insuranceCost = parseFloat(fleet_costs.insurance_cost?.toString() || '0')
    const driverCost = parseFloat(fleet_costs.driver_cost?.toString() || '0')
    const depositCost = parseFloat(fleet_costs.deposit_cost?.toString() || '0')
    const otherCost = parseFloat(fleet_costs.other_cost?.toString() || '0')
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
