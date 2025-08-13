<template>
  <AppLayout title="Create Expedition">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold">Create Expedition</h1>
          <p class="text-muted-foreground">Add a new transportation expedition</p>
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
            Fill in the details below to create a new expedition. The order number will be generated automatically based on the selected customer.
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Customer <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    id="customer_id"
                    autocomplete="off"
                    v-model="customerSearch"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search and select customer"
                    @focus="showCustomerDropdown = true"
                    @blur="handleCustomerBlur"
                  />
                  <input type="hidden" v-model="form.customer_id" />
                  <div v-if="form.errors.customer_id" class="mt-1 text-sm text-red-600">
                    {{ form.errors.customer_id }}
                  </div>
                  
                  <!-- Customer Dropdown -->
                  <div v-if="showCustomerDropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                    <div v-for="customer in filteredCustomers" :key="customer.id" 
                         class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                         @click="selectCustomer(customer)">
                      <div class="font-medium">{{ customer.name }}</div>
                      <div class="text-sm text-gray-600">{{ customer.address }}</div>
                    </div>
                  </div>
                </div>
              </div>

              <div>
                <label for="input_date" class="block text-sm font-medium text-gray-700 mb-2">
                  Input Date <span class="text-red-500">*</span>
                </label>
                <input
                  id="input_date"
                  autocomplete="off"
                  v-model="form.input_date"
                  type="date"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                />
                <div v-if="form.errors.input_date" class="mt-1 text-sm text-red-600">
                  {{ form.errors.input_date }}
                </div>
              </div>

              <div class="grid grid-cols-3 gap-4">
                <div>
                  <label for="travel_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Travel Date <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="travel_date"
                    autocomplete="off"
                    v-model="form.travel_date"
                    type="date"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  />
                  <div v-if="form.errors.travel_date" class="mt-1 text-sm text-red-600">
                    {{ form.errors.travel_date }}
                  </div>
                </div>

                <div>
                  <label for="eta" class="block text-sm font-medium text-gray-700 mb-2">
                    ETA (Days) <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="eta"
                    autocomplete="off"
                    v-model="form.eta"
                    type="number"
                    min="1"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Days"
                  />
                  <div v-if="form.errors.eta" class="mt-1 text-sm text-red-600">
                    {{ form.errors.eta }}
                  </div>
                </div>

                <div>
                  <label for="distance" class="block text-sm font-medium text-gray-700 mb-2">
                    Distance <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="distance"
                    autocomplete="off"
                    v-model="form.distance"
                    type="number"
                    min="0"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="km"
                  />
                  <div v-if="form.errors.distance" class="mt-1 text-sm text-red-600">
                    {{ form.errors.distance }}
                  </div>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
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
              </div>

              <div>
                <label for="industry_sector_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Industry Sector <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    autocomplete="off"
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
                <label for="route_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Route <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    autocomplete="off"
                    v-model="routeSearch"
                    type="text"
                    placeholder="Search route..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @focus="showRouteDropdown = true"
                    @blur="handleRouteBlur"
                    @input="filterRoutes"
                  />
                  <div v-if="showRouteDropdown && filteredRoutes.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <div
                      v-for="route in filteredRoutes"
                      :key="route.id"
                      @mousedown="selectRoute(route)"
                      class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                    >
                      {{ route.name }}
                    </div>
                  </div>
                </div>
                <div v-if="form.errors.route_id" class="mt-1 text-sm text-red-600">
                  {{ form.errors.route_id }}
                </div>
              </div>

              <!-- Product Details -->
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                  Product Details
                </label>
                <input
                  id="description"
                  v-model="form.description"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter product details, specifications, or special requirements"
                />
                <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                  {{ form.errors.description }}
                </div>
              </div>

              <div>
                <label for="detail_route" class="block text-sm font-medium text-gray-700 mb-2">
                  Detail Route
                </label>
                <input
                  id="detail_route"
                  v-model="form.detail_route"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter specific route details, PP, One Way, Daily, Roro, Container, etc."
                />
                <div v-if="form.errors.detail_route" class="mt-1 text-sm text-red-600">
                  {{ form.errors.detail_route }}
                </div>
              </div>

              
            </div>

                         <!-- Expedition Assignment Section -->
             <div class="space-y-6">
               <div class="border-t pt-6">
                 <h3 class="text-lg font-medium text-gray-900 mb-4">Expedition Assignment</h3>
                 
                 <!-- Expedition Type Switch -->
                 <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
                   <div class="text-center mb-4">
                     <h4 class="text-lg font-semibold text-gray-800 mb-2">Choose Expedition Type</h4>
                     <p class="text-sm text-gray-600">Select whether to use a vendor or your own fleet for this expedition</p>
                   </div>
                   
                   <div class="flex items-center justify-center space-x-4">
                     <button
                       type="button"
                       @click="expeditionType = 'vendor'"
                       :class="[
                         'group relative flex flex-col items-center justify-center w-32 h-24 px-4 py-3 rounded-xl border-2 transition-all duration-300 transform hover:scale-105',
                         expeditionType === 'vendor'
                           ? 'border-blue-500 bg-blue-500 text-white shadow-lg shadow-blue-200'
                           : 'border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:bg-blue-50'
                       ]"
                     >
                       <div class="mb-2">
                         <Icon 
                           name="building" 
                           :class="`w-8 h-8 transition-all duration-300 ${expeditionType === 'vendor' ? 'text-white' : 'text-blue-500 group-hover:text-blue-600'}`"
                         />
                       </div>
                       <span class="font-semibold text-sm">Vendor</span>
                       <div v-if="expeditionType === 'vendor'" class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                         <Icon name="check" class="w-4 h-4 text-white" />
                       </div>
                     </button>
                     
                     <button
                       type="button"
                       @click="expeditionType = 'fleet'"
                       :class="[
                         'group relative flex flex-col items-center justify-center w-32 h-24 px-4 py-3 rounded-xl border-2 transition-all duration-300 transform hover:scale-105',
                         expeditionType === 'fleet'
                           ? 'border-blue-500 bg-blue-500 text-white shadow-lg shadow-blue-200'
                           : 'border-gray-200 bg-white text-gray-700 hover:border-blue-300 hover:bg-blue-50'
                       ]"
                     >
                       <div class="mb-2">
                         <Icon 
                           name="truck" 
                           :class="`w-8 h-8 transition-all duration-300 ${expeditionType === 'fleet' ? 'text-white' : 'text-blue-500 group-hover:text-blue-600'}`"
                         />
                       </div>
                       <span class="font-semibold text-sm">Own Fleet</span>
                       <div v-if="expeditionType === 'fleet'" class="absolute -top-2 -right-2 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                         <Icon name="check" class="w-4 h-4 text-white" />
                       </div>
                     </button>
                   </div>
                   
                   <div class="mt-4 text-center">
                     <span class="text-xs text-gray-500">
                       {{ expeditionType === 'vendor' ? 'Using external vendor services' : 'Using your own fleet and driver' }}
                     </span>
                   </div>
                 </div>
                 
                                   <!-- Vendor Field (shown when expedition type is 'vendor') -->
                  <div v-if="expeditionType === 'vendor'" class="w-full">
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
                    
                    <!-- Vendor Details Display -->
                    <div v-if="selectedVendor" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                      <h4 class="text-sm font-semibold text-blue-900 mb-3">Vendor Information</h4>
                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div>
                          <span class="font-medium text-gray-700">Company:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.company }}</span>
                        </div>
                        <div>
                          <span class="font-medium text-gray-700">PIC:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.pic }}</span>
                        </div>
                        <div>
                          <span class="font-medium text-gray-700">Title:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.title_pic }}</span>
                        </div>
                        <div>
                          <span class="font-medium text-gray-700">Phone:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.phone }}</span>
                        </div>
                        <div class="sm:col-span-2">
                          <span class="font-medium text-gray-700">Address:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.address }}, {{ selectedVendor.city }}</span>
                        </div>
                        <div>
                          <span class="font-medium text-gray-700">Mode:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.moda }}</span>
                        </div>
                        <div>
                          <span class="font-medium text-gray-700">Fleet:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.fleet }}</span>
                        </div>
                        <div class="sm:col-span-2">
                          <span class="font-medium text-gray-700">Service Coverage:</span>
                          <span class="ml-2 text-gray-900">{{ selectedVendor.area_service_coverage }}</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                  <!-- Fleet Field (shown when expedition type is 'fleet') -->
                  <div v-if="expeditionType === 'fleet'">
                    <label for="fleet_id" class="block text-sm font-medium text-gray-700 mb-2">
                      Fleet Vehicle <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                      <input
                        v-model="fleetSearch"
                        type="text"
                        placeholder="Search fleet vehicle..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @focus="showFleetDropdown = true"
                        @blur="handleFleetBlur"
                        @input="filterFleets"
                      />
                      <div v-if="showFleetDropdown && filteredFleets.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div
                          v-for="fleet in filteredFleets"
                          :key="fleet.id"
                          @mousedown="selectFleet(fleet)"
                          class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                        >
                          {{ fleet.plate_number }} - {{ fleet.fleet_type?.name || 'Unknown Type' }}
                        </div>
                      </div>
                    </div>
                    <div v-if="form.errors.fleet_id" class="mt-1 text-sm text-red-600">
                      {{ form.errors.fleet_id }}
                    </div>
              </div>

                  <!-- Driver Field (shown when expedition type is 'fleet') -->
                  <div v-if="expeditionType === 'fleet'">
                    <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                      Driver <span class="text-red-500">*</span>
                </label>
                    <div class="relative">
                <input
                        v-model="driverSearch"
                        type="text"
                        placeholder="Search driver..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @focus="showDriverDropdown = true"
                        @blur="handleDriverBlur"
                        @input="filterDrivers"
                      />
                      <div v-if="showDriverDropdown && filteredDrivers.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        <div
                          v-for="driver in filteredDrivers"
                          :key="driver.id"
                          @mousedown="selectDriver(driver)"
                          class="px-3 py-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
                        >
                          {{ driver.name }} - {{ driver.phone }}
                        </div>
                      </div>
                    </div>
                    <div v-if="form.errors.driver_id" class="mt-1 text-sm text-red-600">
                      {{ form.errors.driver_id }}
                    </div>
                  </div>

                                   </div>
               </div>
             </div>

              <!-- Financial Section -->
              <div class="space-y-4">
                <div class="border-t pt-4">
                  <h3 class="text-lg font-medium text-gray-900 mb-3">Financial Information</h3>

                  <!-- Sales Amount Field -->
                  <div class="mb-4">
                    <label for="sales_amount" class="block text-sm font-medium text-gray-700 mb-2">
                      Sales Amount <span class="text-red-500">*</span>
                    </label>
                    <input
                      id="sales_amount"
                      v-model="form.sales_amount"
                      type="number"
                      min="0"
                      required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                      placeholder="Enter sales amount"
                    />
                    <div v-if="form.errors.sales_amount" class="mt-1 text-sm text-red-600">
                      {{ form.errors.sales_amount }}
                    </div>
                  </div>
                  
                  <!-- Vendor Costs (when expedition type is vendor) -->
                  <div v-if="expeditionType === 'vendor'" class="space-y-3">
                    <h4 class="text-md font-medium text-gray-800 mb-2">Vendor Costs</h4>
                    
                    <!-- Deposit Group -->
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                      <h5 class="text-sm font-medium text-gray-700 mb-2">Deposit Information</h5>
                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <label for="deposit_cost_v" class="block text-sm font-medium text-gray-700 mb-1">Deposit Cost</label>
                          <input id="deposit_cost_v" v-model="form.deposit_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter deposit cost" />
                        </div>
                        <div>
                          <label for="deposit_date_v" class="block text-sm font-medium text-gray-700 mb-1">Deposit Date</label>
                          <input id="deposit_date_v" v-model="form.deposit_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                      </div>
                    </div>
                    
                    <!-- Other Vendor Costs -->
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label for="vendor_cost" class="block text-sm font-medium text-gray-700 mb-1">Vendor Cost</label>
                        <input id="vendor_cost" v-model="form.vendor_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter vendor cost" />
                      </div>
                      <div>
                        <label for="other_cost_v" class="block text-sm font-medium text-gray-700 mb-1">Other Cost</label>
                        <input id="other_cost_v" v-model="form.other_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter other cost" />
                      </div>
                    </div>
                    
                    <!-- Total Vendor Cost Display -->
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                      <div class="flex justify-between items-center">
                        <span class="text-md font-semibold text-green-900">Total Vendor Cost:</span>
                        <span class="text-xl font-bold text-green-700">Rp {{ totalVendorCost.toLocaleString() }}</span>
                      </div>
                    </div>
                    
                    <!-- Vendor Description -->
                    <div>
                      <label for="vendor_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                      <textarea
                        id="vendor_description"
                        v-model="form.vendor_description"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Enter additional details, notes, or special requirements for vendor expedition"
                      ></textarea>
                    </div>
                  </div>

                  <!-- Fleet Costs (when expedition type is fleet) -->
                  <div v-else class="space-y-3">
                    <h4 class="text-md font-medium text-gray-800 mb-2">Fleet Costs</h4>
                    
                    <!-- Deposit Group -->
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                      <h5 class="text-sm font-medium text-gray-700 mb-2">Deposit Information</h5>
                      <div class="grid grid-cols-2 gap-4">
                        <div>
                          <label for="deposit_cost_f" class="block text-sm font-medium text-gray-700 mb-1">Deposit Cost</label>
                          <input id="deposit_cost_f" v-model="form.deposit_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter deposit cost" />
                        </div>
                        <div>
                          <label for="deposit_date_f" class="block text-sm font-medium text-gray-700 mb-1">Deposit Date</label>
                          <input id="deposit_date_f" v-model="form.deposit_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                      </div>
                    </div>
                    
                    <!-- Other Fleet Costs -->
                    <div class="grid grid-cols-3 gap-4">
                      <div>
                        <label for="transportation_cost" class="block text-sm font-medium text-gray-700 mb-1">Transportation</label>
                        <input id="transportation_cost" v-model="form.transportation_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost" />
                      </div>
                      <div>
                        <label for="fuel_cost" class="block text-sm font-medium text-gray-700 mb-1">
                          Fuel Cost
                          <button type="button" @click="calculateFuelCost" class="ml-2 text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200">
                            Auto Calculate
                          </button>
                        </label>
                        <input id="fuel_cost" v-model="form.fuel_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost" />
                        <div v-if="fuelCostCalculated" class="mt-1 text-xs text-green-600">
                          ✓ Calculated: {{ formatCurrency(calculatedFuelCost) }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                          💡 Formula: Distance ÷ 8 × Solar Price per Liter
                          <span class="ml-2 text-blue-600">(Solar Price per Liter: {{ getCurrentFuelPrice() }})</span>
                        </div>
                      </div>
                      <div>
                        <label for="tolling_cost" class="block text-sm font-medium text-gray-700 mb-1">Tolling</label>
                        <input id="tolling_cost" v-model="form.tolling_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost" />
                      </div>
                      <div>
                        <label for="port_cost" class="block text-sm font-medium text-gray-700 mb-1">Port Cost</label>
                        <input id="port_cost" v-model="form.port_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost" />
                      </div>
                      <div>
                        <label for="insurance_cost_f" class="block text-sm font-medium text-gray-700 mb-1">Insurance</label>
                        <input id="insurance_cost_f" v-model="form.insurance_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost" />
                      </div>
                      <div>
                        <label for="driver_cost" class="block text-sm font-medium text-gray-700 mb-1">
                          Driver Cost
                          <button type="button" @click="calculateDriverCost" class="ml-2 text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200">
                            Auto Calculate
                          </button>
                        </label>
                        <input id="driver_cost" v-model="form.driver_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost" />
                        <div v-if="driverCostCalculated" class="mt-1 text-xs text-green-600">
                          ✓ Calculated: {{ formatCurrency(calculatedDriverCost) }}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">
                          💡 Formula: {{ getDriverCostFormula() }}
                          <span class="ml-2 text-blue-600">({{ getCurrentDriverCostInfo() }})</span>
                        </div>
                      </div>
                      <div>
                        <label for="other_cost_f" class="block text-sm font-medium text-gray-700 mb-1">Other Cost</label>
                        <input id="other_cost_f" v-model="form.other_cost" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter cost" />
                      </div>
                    </div>
                    
                    <!-- Total Cost Display -->
                    <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                      <div class="flex justify-between items-center">
                        <span class="text-md font-semibold text-green-900">Total Cost:</span>
                        <span class="text-xl font-bold text-green-700">Rp {{ totalFleetCost.toLocaleString() }}</span>
                      </div>
                    </div>
                    
                    <!-- Fleet Description -->
                    <div>
                      <label for="fleet_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                      <textarea
                        id="fleet_description"
                        v-model="form.fleet_description"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Enter additional details, notes, or special requirements for fleet expedition"
                      ></textarea>
                    </div>
                  </div>
              </div>
            </div>

            <div class="flex justify-end space-x-3">
              <Link :href="route('expeditions.index')" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Cancel
              </Link>
              <Button type="submit" :disabled="form.processing" class="px-4 py-2 bg-black text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <Icon v-if="form.processing" name="loader" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Creating...' : 'Create Expedition' }}
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
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
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

interface Fleet {
  id: number
  plate_number: string
  description?: string
  fleet_type?: {
    id: number
    name: string
  }
}

interface Driver {
  id: number
  name: string
  phone: string
  email: string
}

interface Customer {
  id: number
  name: string
  address: string
  city: string
  phone: string
}

interface Props {
  industrySectors: IndustrySector[]
  routes: Route[]
  vendors: Vendor[]
  fleets: Fleet[]
  drivers: Driver[]
  customers: Customer[]
}

const props = defineProps<Props>()

// Expedition type state
const expeditionType = ref<'vendor' | 'fleet'>('vendor')

// No cost type switch; costs depend on expedition type (vendor/fleet)

// Watch for expedition type changes and clear appropriate fields
watch(expeditionType, (newType) => {
  // Update form expedition_type
  form.expedition_type = newType
  
  if (newType === 'vendor') {
    form.fleet_id = ''
    form.driver_id = ''
    fleetSearch.value = ''
    driverSearch.value = ''
  } else {
    form.vendor_id = ''
    vendorSearch.value = ''
    selectedVendor.value = null
  }
})

// Search states
const industrySectorSearch = ref('')
const routeSearch = ref('')
const vendorSearch = ref('')
const fleetSearch = ref('')
const driverSearch = ref('')
const customerSearch = ref('')

// Dropdown visibility states
const showIndustrySectorDropdown = ref(false)
const showRouteDropdown = ref(false)
const showVendorDropdown = ref(false)
const showFleetDropdown = ref(false)
const showDriverDropdown = ref(false)
const showCustomerDropdown = ref(false)

const form = useForm({
  customer_id: '',
  input_date: new Date().toISOString().split('T')[0],
  travel_date: new Date().toISOString().split('T')[0],
  origin: '',
  destination: '',
  distance: '',
  description: '',
  industry_sector_id: '',
  route_id: '',
  detail_route: '',
  sales_amount: '',
  expedition_type: 'vendor',
  vendor_id: '',
  fleet_id: '',
  driver_id: '',
  // Vendor costs
  vendor_cost: '',
  // Shared
  deposit_cost: '',
  deposit_date: '',
  other_cost: '',
  // Fleet costs
  transportation_cost: '',
  fuel_cost: '',
  tolling_cost: '',
  port_cost: '',
  insurance_cost: '',
  driver_cost: '',
  eta: '',
  // New fields for vendor expedition
  vendor_description: '',
  fleet_description: '',
})

// Selected vendor for display
const selectedVendor = ref<Vendor | null>(null)

// Filtered lists
const filteredCustomers = computed(() => {
  if (!customerSearch.value) return props.customers
  return props.customers.filter(customer =>
    customer.name.toLowerCase().includes(customerSearch.value.toLowerCase()) ||
    customer.address.toLowerCase().includes(customerSearch.value.toLowerCase())
  )
})

const filteredIndustrySectors = computed(() => {
  if (!industrySectorSearch.value) return props.industrySectors
  return props.industrySectors.filter(sector =>
    sector.name.toLowerCase().includes(industrySectorSearch.value.toLowerCase())
  )
})

const filteredRoutes = computed(() => {
  if (!routeSearch.value) return props.routes
  return props.routes.filter(route =>
    route.name.toLowerCase().includes(routeSearch.value.toLowerCase())
  )
})

const filteredVendors = computed(() => {
  if (!vendorSearch.value) return props.vendors
  return props.vendors.filter(vendor =>
    vendor.company.toLowerCase().includes(vendorSearch.value.toLowerCase())
  )
})

const filteredFleets = computed(() => {
  if (!fleetSearch.value) return props.fleets
  return props.fleets.filter(fleet =>
    fleet.plate_number.toLowerCase().includes(fleetSearch.value.toLowerCase()) ||
    (fleet.fleet_type?.name && fleet.fleet_type.name.toLowerCase().includes(fleetSearch.value.toLowerCase()))
  )
})

const filteredDrivers = computed(() => {
  if (!driverSearch.value) return props.drivers
  return props.drivers.filter(driver =>
    driver.name.toLowerCase().includes(driverSearch.value.toLowerCase()) ||
    driver.phone.toLowerCase().includes(driverSearch.value.toLowerCase())
  )
})

// Total fleet cost computation
const totalFleetCost = computed(() => {
  const transportation = parseFloat(form.transportation_cost) || 0
  const fuel = parseFloat(form.fuel_cost) || 0
  const tolling = parseFloat(form.tolling_cost) || 0
  const port = parseFloat(form.port_cost) || 0
  const insurance = parseFloat(form.insurance_cost) || 0
  const driver = parseFloat(form.driver_cost) || 0
  const deposit = parseFloat(form.deposit_cost) || 0
  const other = parseFloat(form.other_cost) || 0
  return transportation + fuel + tolling + port + insurance + driver + deposit + other
})

// Total vendor cost computation
const totalVendorCost = computed(() => {
  const vendorCost = parseFloat(form.vendor_cost) || 0
  const depositCost = parseFloat(form.deposit_cost) || 0
  const otherCost = parseFloat(form.other_cost) || 0
  return vendorCost + depositCost + otherCost
})

// Auto calculation states
const fuelCostCalculated = ref(false)
const driverCostCalculated = ref(false)
const calculatedFuelCost = ref(0)
const calculatedDriverCost = ref(0)

// Auto calculation functions
const calculateFuelCost = () => {
  const distance = parseFloat(form.distance) || 0
  const config = JSON.parse(localStorage.getItem('expedition_cost_config') || '{}')
  const fuelPrice = config.fuel_price_per_liter || 15000
  
  // Formula: jarak / 8 * harga solar
  calculatedFuelCost.value = (distance / 8) * fuelPrice
  form.fuel_cost = calculatedFuelCost.value.toString()
  fuelCostCalculated.value = true
  
  // Hide calculation indicator after 3 seconds
  setTimeout(() => {
    fuelCostCalculated.value = false
  }, 3000)
}

const calculateDriverCost = () => {
  const config = JSON.parse(localStorage.getItem('expedition_cost_config') || '{}')
  const costType = config.driver_cost_type || 'per_day'
  
  if (costType === 'per_day') {
    const eta = parseFloat(form.eta) || 0
    const costPerDay = config.driver_cost_per_day || 200000
    calculatedDriverCost.value = eta * costPerDay
  } else {
    const distance = parseFloat(form.distance) || 0
    const costPerKm = config.driver_cost_per_km || 5000
    calculatedDriverCost.value = distance * costPerKm
  }
  
  form.driver_cost = calculatedDriverCost.value.toString()
  driverCostCalculated.value = true
  
  // Hide calculation indicator after 3 seconds
  setTimeout(() => {
    driverCostCalculated.value = false
  }, 3000)
}

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(amount)
}

// Get driver cost formula based on configuration
const getDriverCostFormula = () => {
  const config = JSON.parse(localStorage.getItem('expedition_cost_config') || '{}')
  const costType = config.driver_cost_type || 'per_day'
  
  if (costType === 'per_day') {
    return 'ETA (Days) × Driver Cost per Day'
  } else {
    return 'Distance (KM) × Driver Cost per Kilometer'
  }
}

// Get current fuel price from configuration
const getCurrentFuelPrice = () => {
  const config = JSON.parse(localStorage.getItem('expedition_cost_config') || '{}')
  const fuelPrice = config.fuel_price_per_liter || 15000
  return formatCurrency(fuelPrice)
}

// Get current driver cost info from configuration
const getCurrentDriverCostInfo = () => {
  const config = JSON.parse(localStorage.getItem('expedition_cost_config') || '{}')
  const costType = config.driver_cost_type || 'per_day'
  
  if (costType === 'per_day') {
    const costPerDay = config.driver_cost_per_day || 200000
    return `Harga per Hari: ${formatCurrency(costPerDay)}`
  } else {
    const costPerKm = config.driver_cost_per_km || 5000
    return `Harga per KM: ${formatCurrency(costPerKm)}`
  }
}

// Selection functions
const selectCustomer = (customer: Customer) => {
  form.customer_id = customer.id.toString()
  customerSearch.value = customer.name
  showCustomerDropdown.value = false
}

const selectIndustrySector = (sector: IndustrySector) => {
  form.industry_sector_id = sector.id.toString()
  industrySectorSearch.value = sector.name
  showIndustrySectorDropdown.value = false
}

const selectRoute = (route: Route) => {
  form.route_id = route.id.toString()
  routeSearch.value = route.name
  showRouteDropdown.value = false
}

const selectVendor = (vendor: Vendor) => {
  form.vendor_id = vendor.id.toString()
  vendorSearch.value = vendor.company
  selectedVendor.value = vendor
  showVendorDropdown.value = false
}

const selectFleet = (fleet: Fleet) => {
  form.fleet_id = fleet.id.toString()
  fleetSearch.value = `${fleet.plate_number} - ${fleet.fleet_type?.name || 'Unknown Type'}`
  showFleetDropdown.value = false
}

const selectDriver = (driver: Driver) => {
  form.driver_id = driver.id.toString()
  driverSearch.value = `${driver.name} - ${driver.phone}`
  showDriverDropdown.value = false
}

// Clear functions
const clearCustomer = () => {
  form.customer_id = ''
  customerSearch.value = ''
}

const clearIndustrySector = () => {
  form.industry_sector_id = ''
  industrySectorSearch.value = ''
}

const clearRoute = () => {
  form.route_id = ''
  routeSearch.value = ''
}

const clearVendor = () => {
  form.vendor_id = ''
  vendorSearch.value = ''
  selectedVendor.value = null
}

const clearFleet = () => {
  form.fleet_id = ''
  fleetSearch.value = ''
}

const clearDriver = () => {
  form.driver_id = ''
  driverSearch.value = ''
}

// Filter functions
const filterIndustrySectors = () => {
  showIndustrySectorDropdown.value = true
}

const filterRoutes = () => {
  showRouteDropdown.value = true
}

const filterVendors = () => {
  showVendorDropdown.value = true
}

const filterFleets = () => {
  showFleetDropdown.value = true
}

const filterDrivers = () => {
  showDriverDropdown.value = true
}

// Blur handler functions
const handleCustomerBlur = () => {
  setTimeout(() => {
    showCustomerDropdown.value = false
  }, 150)
}

const handleIndustrySectorBlur = () => {
  setTimeout(() => {
    showIndustrySectorDropdown.value = false
  }, 150)
}

const handleRouteBlur = () => {
  setTimeout(() => {
    showRouteDropdown.value = false
  }, 150)
}

const handleVendorBlur = () => {
  setTimeout(() => {
    showVendorDropdown.value = false
  }, 150)
}

const handleFleetBlur = () => {
  setTimeout(() => {
    showFleetDropdown.value = false
  }, 150)
}

const handleDriverBlur = () => {
  setTimeout(() => {
    showDriverDropdown.value = false
  }, 150)
}

// Get name functions
const getIndustrySectorName = (id: number | string) => {
  const sector = props.industrySectors.find(s => s.id === id)
  return sector ? sector.name : ''
}

const getRouteName = (id: number | string) => {
  const route = props.routes.find(r => r.id === id)
  return route ? route.name : ''
}

const getVendorName = (id: number | string) => {
  const vendor = props.vendors.find(v => v.id === id)
  return vendor ? vendor.company : ''
}

const getFleetName = (id: number | string) => {
  const fleet = props.fleets.find(f => f.id === id)
  return fleet ? `${fleet.plate_number} - ${fleet.fleet_type?.name || 'Unknown Type'}` : ''
}

const getDriverName = (id: number | string) => {
  const driver = props.drivers.find(d => d.id === id)
  return driver ? `${driver.name} - ${driver.phone}` : ''
}

// Close dropdowns when clicking outside
const handleClickOutside = (event: Event) => {
  const target = event.target as HTMLElement
  if (!target.closest('.relative')) {
    showIndustrySectorDropdown.value = false
    showRouteDropdown.value = false
    showVendorDropdown.value = false
    showFleetDropdown.value = false
    showDriverDropdown.value = false
    showCustomerDropdown.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})

const submit = () => {
  // Set expedition_type based on current selection
  form.expedition_type = expeditionType.value
  
  // Clear the field that's not being used based on expedition type
  if (expeditionType.value === 'vendor') {
    form.fleet_id = ''
    form.driver_id = ''
  } else {
    form.vendor_id = ''
  }
  
  form.post(route('expeditions.store'))
}
</script>
