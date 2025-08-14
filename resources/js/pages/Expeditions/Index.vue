<template>
  <AppLayout title="All Expeditions">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">All Expeditions</h1>
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
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
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
        
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
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
        
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
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
        
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <!-- Simple SVG icon as fallback -->
              <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Sales</p>
              <p class="text-2xl font-bold text-gray-900">{{ formatCurrency(getTotalSales()) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Search and Filters -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
          <!-- Search -->
          <div class="lg:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Expeditions</label>
          <div class="relative">
            <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            <input
              v-model="searchQuery"
              type="text"
                placeholder="Search by order number, origin, destination..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            />
          </div>
        </div>
        
          <!-- Expedition Type Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Expedition Type</label>
            <select
              v-model="filterType"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            >
              <option value="">All Types</option>
              <option value="vendor">Vendor</option>
              <option value="fleet">Fleet</option>
            </select>
          </div>
          
          <!-- Month/Year Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Travel Date</label>
            <input
              v-model="filterDate"
              type="month"
              class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
            />
          </div>
        </div>

        <div class="flex justify-between items-center mt-4">
          <div class="text-sm text-gray-600">
            Showing {{ paginatedExpeditions.length }} of {{ filteredExpeditions.length }} expeditions
          </div>
          <Button as-child class="bg-blue-600 hover:bg-blue-700 text-white">
            <Link :href="route('expeditions.create')">
              <Icon name="plus" class="h-4 w-4 mr-2" />
              New Expedition
            </Link>
          </Button>
        </div>
      </div>

      <!-- Expeditions Table -->
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
          <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left font-semibold text-gray-700 p-4">Order #</th>
                <th class="text-left font-semibold text-gray-700 p-4">Type</th>
                <th class="text-left font-semibold text-gray-700 p-4">Travel Date</th>
                <th class="text-left font-semibold text-gray-700 p-4">Route</th>
                <th class="text-left font-semibold text-gray-700 p-4">Customer</th>
                <th class="text-left font-semibold text-gray-700 p-4">Consignee</th>
                <th class="text-left font-semibold text-gray-700 p-4">Sales</th>
                <th class="text-left font-semibold text-gray-700 p-4">Cost</th>
                <th class="text-left font-semibold text-gray-700 p-4">Profit</th>
                <th class="text-left font-semibold text-gray-700 p-4">
                  <div class="flex items-center gap-2">
                    <span>Goods</span>
                    <div class="relative group">
                      <Icon name="help-circle" class="h-4 w-4 text-gray-400 cursor-help" />
                      <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50">
                        Goods data is required to enable printing options
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                      </div>
                    </div>
                  </div>
                </th>
                <th class="text-left font-semibold text-gray-700 p-4">Actions</th>
                </tr>
              </thead>
              <tbody>
              <tr 
                v-for="expedition in paginatedExpeditions" 
                :key="expedition.id" 
                class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
                :class="expedition.expedition_type === 'vendor' ? 'bg-orange-50/30' : 'bg-blue-50/30'"
              >
                <td class="p-4">
                  <div class="font-semibold text-gray-900">{{ expedition.order_number }}</div>
                  <div class="text-sm text-gray-500">{{ expedition.origin }} → {{ expedition.destination }}</div>
                </td>
                <td class="p-4">
                  <Badge 
                    :variant="expedition.expedition_type === 'fleet' ? 'default' : 'secondary'"
                    :class="expedition.expedition_type === 'vendor' ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-blue-100 text-blue-800 border-blue-200'"
                  >
                      {{ expedition.expedition_type === 'fleet' ? 'Own Fleet' : 'Vendor' }}
                    </Badge>
                  </td>
                <td class="p-4">
                  <div class="text-gray-900">{{ formatDate(expedition.etd) }}</div>
                  <div class="text-sm text-gray-500">ETA: {{ formatDate(expedition.eta) }}</div>
                </td>
                <td class="p-4">
                  <div class="text-gray-900">{{ expedition.route?.name || '-' }}</div>
                  <div class="text-sm text-gray-500">{{ expedition.industry_sector?.name || '-' }}</div>
                  </td>
                <td class="p-4">
                  <div class="text-gray-900">
                    <Link 
                      v-if="expedition.customer" 
                      :href="route('customers.show', expedition.customer.id)" 
                      class="text-blue-600 hover:text-blue-800 hover:underline transition-colors"
                    >
                      {{ expedition.customer.name }}
                    </Link>
                    <span v-else>-</span>
                  </div>
                </td>
                <td class="p-4">
                  <div class="text-gray-900">
                    <Link 
                      v-if="expedition.consignee" 
                      :href="route('consignees.show', expedition.consignee.id)" 
                      class="text-blue-600 hover:text-blue-800 hover:underline transition-colors"
                    >
                      {{ expedition.consignee.company }}
                    </Link>
                    <span v-else>-</span>
                  </div>
                </td>
                <td class="p-4">
                  <div class="text-gray-900 font-medium">
                    {{ formatCurrency(getSalesAmount(expedition)) }}
                      </div>
                  </td>
                <td class="p-4">
                  <div class="text-gray-900 font-medium">
                    {{ formatCurrency(getTotalCost(expedition)) }}
                    </div>
                  </td>
                <td class="p-4">
                  <div class="flex flex-col">
                    <span :class="[
                      'font-semibold',
                      getSalesAmount(expedition) - getTotalCost(expedition) >= 0 ? 'text-green-600' : 'text-red-600'
                    ]">
                      {{ formatCurrency(getSalesAmount(expedition) - getTotalCost(expedition)) }}
                    </span>
                    <span :class="[
                      'text-xs text-gray-500 mt-1',
                      getSalesAmount(expedition) - getTotalCost(expedition) >= 0 
                        ? 'text-green-600' 
                        : 'text-red-600'
                    ]">
                      {{ getProfitPercentage(expedition) }}
                    </span>
                  </div>
                </td>
                <td class="p-4">
                  <!-- Goods Button - Green if exists, Red if not -->
                  <Button 
                    v-if="expedition.expedition_goods"
                    @click="viewGoods(expedition.id)"
                    variant="outline"
                    size="sm"
                    class="bg-green-100 text-green-700 border-green-300 hover:bg-green-200 hover:border-green-400 transition-all duration-200"
                  >
                    <Icon name="package" class="h-4 w-4 mr-2" />
                    Goods
                  </Button>
                  <Button 
                    v-else
                    @click="createGoods(expedition.id)"
                    variant="outline"
                    size="sm"
                    class="bg-red-100 text-red-700 border-red-300 hover:bg-red-200 hover:border-red-400 transition-all duration-200"
                  >
                    <Icon name="plus" class="h-4 w-4 mr-2" />
                    Create
                  </Button>
                  <div v-if="!expedition.expedition_goods" class="text-xs text-gray-500 mt-1 text-center">
                    Print disabled
                  </div>
                  <div v-if="expedition.expedition_goods" class="text-xs text-green-600 mt-1 text-center">
                    Print enabled
                  </div>
                </td>
                <td class="p-4">
                    <DropdownMenu>
                      <DropdownMenuTrigger as-child>
                        <Button variant="outline" size="sm" class="hover:bg-gray-50" aria-label="Actions">
                          <!-- More actions icon -->
                          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                          </svg>
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end" class="w-48 z-[60]">
                        <DropdownMenuLabel>Actions</DropdownMenuLabel>
                        <DropdownMenuGroup>
                          <DropdownMenuItem as-child>
                            <Link :href="route('expeditions.show', expedition.id)" class="flex items-center">
                              <Icon name="eye" class="mr-2 h-4 w-4" /> View
                            </Link>
                          </DropdownMenuItem>
                          <DropdownMenuItem as-child>
                            <Link :href="route('expeditions.edit', expedition.id)" class="flex items-center">
                              <Icon name="edit" class="mr-2 h-4 w-4" /> Edit
                            </Link>
                          </DropdownMenuItem>
                          <DropdownMenuSeparator />
                          <DropdownMenuLabel>Print Options</DropdownMenuLabel>
                          <DropdownMenuLabel v-if="!expedition.expedition_goods" class="text-xs text-gray-500 font-normal px-2 py-1">
                            Goods must be added first to enable printing
                          </DropdownMenuLabel>
                          <DropdownMenuItem 
                            @click="printShippingInstruction(expedition)"
                            :disabled="!expedition.expedition_goods"
                            class="flex items-center"
                            :class="{ 'opacity-50 cursor-not-allowed text-gray-400': !expedition.expedition_goods }"
                          >
                            <!-- Shipping Instruction icon -->
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Shipping Instruction</span>
                            <DropdownMenuShortcut v-if="!expedition.expedition_goods" class="text-xs text-gray-400">
                              Add goods first
                            </DropdownMenuShortcut>
                          </DropdownMenuItem>
                          <DropdownMenuItem 
                            @click="openDispatchReceive(expedition)"
                            :disabled="!expedition.expedition_goods"
                            class="flex items-center"
                            :class="{ 'opacity-50 cursor-not-allowed text-gray-400': !expedition.expedition_goods }"
                          >
                            <!-- Dispatch/Receive icon -->
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Dispatch & Receive</span>
                            <DropdownMenuShortcut v-if="!expedition.expedition_goods" class="text-xs text-gray-400">
                              Add goods first
                            </DropdownMenuShortcut>
                          </DropdownMenuItem>
                          <DropdownMenuSeparator />
                          <DropdownMenuItem @click="deleteExpedition(expedition)" class="text-red-600 focus:text-red-700">
                            <Icon name="trash" class="mr-2 h-4 w-4" /> Delete
                            <DropdownMenuShortcut>Del</DropdownMenuShortcut>
                          </DropdownMenuItem>
                        </DropdownMenuGroup>
                      </DropdownMenuContent>
                    </DropdownMenu>
                </td>
              </tr>
              <tr v-if="paginatedExpeditions.length === 0">
                <td colspan="11" class="p-8 text-center text-gray-500">
                  <Icon name="search" class="h-12 w-12 mx-auto text-gray-300 mb-4" />
                  <p class="text-lg font-medium">No expeditions found</p>
                  <p class="text-sm">Try adjusting your search criteria or create your first expedition.</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        <!-- Load More Button -->
        <div v-if="hasMoreExpeditions" class="p-4 border-t border-gray-200 text-center">
          <Button 
            @click="loadMore" 
            variant="outline" 
            :disabled="isLoadingMore"
            class="hover:bg-gray-50"
          >
            <!-- Loading spinner icon -->
            <svg v-if="isLoadingMore" class="mr-2 h-4 w-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            {{ isLoadingMore ? 'Loading...' : 'Load More Expeditions' }}
          </Button>
        </div>
      </div>
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
              <!-- Close/X icon -->
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <div class="p-6">
        <div class="space-y-6">
          <!-- Fuel Cost Configuration -->
          <div class="border border-gray-200 rounded-lg p-4 bg-gray-50/30">
            <h4 class="text-md font-medium text-gray-900 mb-4 flex items-center">
              <Icon name="zap" class="h-5 w-5 mr-2 text-blue-600" />
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

    <!-- Dispatch & Receive Modal -->
    <div v-if="showDispatchReceiveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto" @click.stop>
        <div @click="showDispatchReceiveModal = false" class="absolute inset-0 -z-10"></div>
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-xl">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Dispatch & Receive Form</h3>
            <button 
              @click="showDispatchReceiveModal = false" 
              class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100 transition-colors"
            >
              <!-- Close/X icon -->
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <div class="p-6">
          <div class="dispatch-receive-content space-y-6">
            <!-- Header Section -->
            <div class="text-center border-b border-gray-200 pb-4">
              <h1 class="text-2xl font-bold text-gray-900 mb-2">DISPATCH/RECEIVED</h1>
              <p class="text-lg text-gray-700">No: {{ selectedExpedition?.order_number }}/AHP-DR/{{ new Date().getFullYear() }}</p>
              <div class="mt-4">
                <img src="/logo.png" alt="ARKADAYA" class="h-12 mx-auto" />
                <p class="text-sm text-gray-600 mt-2">ARKADAYA</p>
              </div>
            </div>

            <!-- Dispatch/Receive Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
              <h2 class="text-lg font-semibold text-gray-900 mb-3">DISPATCH TO / RECEIVED FROM</h2>
              <p class="text-sm italic text-gray-600 mb-4">Dikirim ke / Terima dari (coret yang tidak perlu)</p>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">COMPANY:</label>
                  <p class="text-sm text-gray-900">{{ selectedExpedition?.customer?.name || 'N/A' }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Address:</label>
                  <p class="text-sm text-gray-900">{{ selectedExpedition?.customer?.address || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-700 mb-1">PIC:</label>
                  <p class="text-sm text-gray-900">{{ selectedExpedition?.customer?.pic_name || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-medium text-gray-700 mb-1">Phone No:</label>
                  <p class="text-sm text-gray-900">{{ selectedExpedition?.customer?.pic_phone || 'N/A' }}</p>
                </div>
              </div>
            </div>

            <!-- Goods Details Table -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-3">Goods Details</h3>
              <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded-lg">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">No</th>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">Description of Goods</th>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">Quantity</th>
                      <th class="text-left font-semibold text-gray-700 p-3 border-b">Remarks</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(good, index) in selectedExpedition?.expedition_goods?.good_details" :key="good.id" class="border-b border-gray-100 hover:bg-gray-50">
                      <td class="p-3">{{ index + 1 }}</td>
                      <td class="p-3">{{ good.name }}</td>
                      <td class="p-3">{{ good.quantity }} {{ good.unit }}</td>
                      <td class="p-3">{{ good.remark || '-' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Special Instructions/Remarks -->
            <div>
              <h3 class="text-lg font-semibold text-gray-900 mb-3">SPECIAL INSTRUCTIONS / REMARKS</h3>
              <div class="border border-gray-300 rounded-lg p-4 min-h-[100px] bg-white">
                <p class="text-gray-700">{{ selectedExpedition?.expedition_goods?.special_instruction || 'No special instructions' }}</p>
              </div>
            </div>

            <!-- Footer Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-200">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Issued by:</label>
                <p class="text-sm text-gray-900">{{ selectedExpedition?.user?.name || 'N/A' }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date:</label>
                <p class="text-sm text-gray-900">{{ formatDate(new Date().toISOString()) }}</p>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Signature:</label>
                <div class="mt-2">
                  <img v-if="selectedExpedition?.user?.signature" :src="`/storage/${selectedExpedition.user.signature}?t=${Date.now()}`" alt="Signature" class="h-12 w-auto border border-gray-300 rounded" />
                  <div v-else class="h-12 w-24 border-2 border-dashed border-gray-300 rounded flex items-center justify-center">
                    <span class="text-gray-400 text-xs">Signature</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Company Contact Information -->
            <div class="text-center pt-4 border-t border-gray-200">
              <p class="text-sm font-medium text-gray-900">PT. ARKADAYA HAKATO PERSADA</p>
              <p class="text-xs text-gray-600">Jl. Raya Mabes Hankam No. 41 RT07/002 Jakarta Timur, DKI Jakarta</p>
              <p class="text-xs text-gray-600">Email: info@arkadaya.com</p>
            </div>
          </div>

          <div class="flex justify-end space-x-3 mt-6">
            <Button @click="showDispatchReceiveModal = false" variant="outline" class="px-4 py-2">
              Close
            </Button>
            <Button @click="printDispatchReceive" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white">
              <Icon name="printer" class="mr-2 h-4 w-4" />
              Print
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
import { ref, computed, onMounted, watch } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
import Dialog from '@/components/ui/dialog/Dialog.vue'
import DialogContent from '@/components/ui/dialog/DialogContent.vue'
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue'
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue'
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue'
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuGroup, DropdownMenuSeparator, DropdownMenuShortcut } from '@/components/ui/dropdown-menu'
import ShippingInstructionPrint from '@/components/ShippingInstructionPrint.vue'

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
  etd: string
  origin: string
  destination: string
  description?: string
  industry_sector_id: number
  route_id: number
  expedition_type: 'vendor' | 'fleet'
  vendor_id?: number
  fleet_id?: number
  driver_id?: number
  eta: string
  user_id: number
  created_at: string
  updated_at: string
  industry_sector?: IndustrySector
  route?: { id: number; name: string; description?: string }
  vendor?: Vendor
  fleet?: Fleet
  driver?: Driver
  customer?: { id: number; name: string; address?: string; phone?: string; pic_name?: string; pic_phone?: string; email?: string }
  consignee?: { id: number; company: string; address?: string; phone?: string; email?: string }
  // Cost relationships
  fleet_costs?: ExpeditionCostFleet
  vendor_costs?: ExpeditionCostVendor
  // Accessor methods from the Expedition model
  sales_amount?: number
  total_cost?: number
  // Additional properties for printing
  distance?: number
  detail_route?: string
  user?: { id: number; name: string; signature?: string }
  // Goods relationship
  expedition_goods?: { 
    id: number; 
    remark_dispatch?: string; 
    remark_receive?: string; 
    date_dispatch?: string; 
    date_receive?: string;
    total_goods?: number;
    gross_weight?: number;
    dimension_total?: number;
    packaging?: string;
    good_description?: string;
    special_instruction?: string;
    good_details?: {
      id: number;
      name: string;
      quantity: string;
      unit: string;
      remark?: string;
    }[];
  }
}

interface Props {
  expeditions: Expedition[]
}

const props = defineProps<Props>()

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
const showDispatchReceiveModal = ref(false)
const selectedExpedition = ref<Expedition | null>(null)
const searchQuery = ref('')
const filterType = ref('')
const filterDate = ref('')

// Pagination states
const currentPage = ref(1)
const itemsPerPage = 10
const isLoadingMore = ref(false)

const filteredExpeditions = computed(() => {
  let filtered = props.expeditions
  
  // Search filter
  if (searchQuery.value) {
  const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter((expedition: Expedition) => {
      return expedition.order_number.toLowerCase().includes(query) ||
                         expedition.origin.toLowerCase().includes(query) ||
                         expedition.destination.toLowerCase().includes(query) ||
                         expedition.industry_sector?.name.toLowerCase().includes(query) ||
                         expedition.route?.name.toLowerCase().includes(query) ||
                         expedition.customer?.name.toLowerCase().includes(query) ||
                         expedition.consignee?.company.toLowerCase().includes(query) ||
                         expedition.vendor?.company.toLowerCase().includes(query) ||
                         expedition.fleet?.plate_number.toLowerCase().includes(query)
    })
  }

  // Type filter
  if (filterType.value) {
    filtered = filtered.filter((expedition: Expedition) => 
      expedition.expedition_type === filterType.value
    )
  }

  // Date filter
  if (filterDate.value) {
    filtered = filtered.filter((expedition: Expedition) => {
      const travelDate = new Date(expedition.etd)
      const filterYear = parseInt(filterDate.value.split('-')[0])
      const filterMonth = parseInt(filterDate.value.split('-')[1]) - 1
      return travelDate.getFullYear() === filterYear && travelDate.getMonth() === filterMonth
    })
  }

  return filtered
})

const paginatedExpeditions = computed(() => {
  const startIndex = 0
  const endIndex = currentPage.value * itemsPerPage
  return filteredExpeditions.value.slice(startIndex, endIndex)
})

const hasMoreExpeditions = computed(() => {
  return paginatedExpeditions.value.length < filteredExpeditions.value.length
})

const loadMore = () => {
  if (hasMoreExpeditions.value && !isLoadingMore.value) {
    isLoadingMore.value = true
    setTimeout(() => {
      currentPage.value++
      isLoadingMore.value = false
    }, 500)
  }
}

// Reset pagination when filters change
const resetPagination = () => {
  currentPage.value = 1
}

// Watch for filter changes
watch([searchQuery, filterType, filterDate], () => {
  resetPagination()
})

const deleteExpedition = (expedition: Expedition) => {
  expeditionToDelete.value = expedition
  showDeleteDialog.value = true
}

const printShippingInstruction = (expedition: Expedition) => {
  // Check if goods exist before allowing print
  if (!expedition.expedition_goods) {
    alert('Please add goods data first before printing shipping instructions.')
    return
  }
  
  // Open shipping instruction print page in new window
  window.open(`/expeditions/${expedition.id}/print-shipping-instruction`, '_blank')
}


const openDispatchReceive = (expedition: Expedition) => {
  // Check if goods exist before allowing dispatch/receive
  if (!expedition.expedition_goods) {
    alert('Please add goods data first before managing dispatch/receive.')
    return
  }
  
  selectedExpedition.value = expedition
  showDispatchReceiveModal.value = true
}

const printDispatchReceive = () => {
  if (!selectedExpedition.value) return
  
  // Open print window
  const printWindow = window.open('', '_blank')
  if (printWindow) {
    const content = document.querySelector('.dispatch-receive-content')?.innerHTML || ''
    printWindow.document.write(`
      <html>
        <head>
          <title>Dispatch & Receive - ${selectedExpedition.value.order_number}</title>
          <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .header { text-align: center; margin-bottom: 30px; }
            .company-info { background-color: #f9f9f9; padding: 15px; margin: 20px 0; }
            .footer { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 20px; }
            @media print { body { margin: 0; } }
          </style>
        </head>
        <body>
          ${content}
        </body>
      </html>
    `)
    printWindow.document.close()
    printWindow.print()
  }
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
  // Remove minus sign from currency format, keep only the number
  const absoluteAmount = Math.abs(amount)
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(absoluteAmount)
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
  const total = props.expeditions.reduce((sum: number, expedition: Expedition) => {
    let salesAmount = 0
    
    if (expedition.expedition_type === 'vendor' && expedition.vendor_costs) {
      salesAmount = expedition.vendor_costs.sales_amount || 0
    } else if (expedition.expedition_type === 'fleet' && expedition.fleet_costs) {
      salesAmount = expedition.fleet_costs.sales_amount || 0
    }
    
    return sum + salesAmount
  }, 0)
  
  return total
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

// Set current month as default filter
const setDefaultDateFilter = () => {
  const now = new Date()
  const year = now.getFullYear()
  const month = String(now.getMonth() + 1).padStart(2, '0')
  filterDate.value = `${year}-${month}`
}

// Load configuration when component mounts
onMounted(() => {
loadConfiguration()
  setDefaultDateFilter()
})

// Goods management methods
const viewGoods = (expeditionId: number) => {
  // Navigate to goods detail page
  router.visit(route('expeditions.goods.show', expeditionId))
}

const createGoods = (expeditionId: number) => {
  // Navigate to create goods page
  router.visit(route('expeditions.goods.create', expeditionId))
}
</script>

<style scoped>
table {
  width: 100%;
}

th, td {
  padding: 1rem;
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
