<template>
  <AppLayout :title="`Edit Goods - ${expedition.order_number}`">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Edit Goods</h1>
          <p class="text-muted-foreground">Order #{{ expedition.order_number }}</p>
        </div>
        <div class="flex items-center space-x-3">
          <Button 
            @click="goBack" 
            variant="outline" 
            size="sm"
          >
            <Icon name="arrow-left" class="h-4 w-4 mr-2" />
            Back to Goods
          </Button>
        </div>
      </div>

      <!-- Expedition Info -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Expedition Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-600">Customer</p>
            <p class="font-medium">{{ expedition.customer?.name }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Consignee</p>
            <p class="font-medium">{{ expedition.consignee?.company }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">Route</p>
            <p class="font-medium">{{ expedition.origin }} → {{ expedition.destination }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-600">ETD (Estimated Time of Departure)</p>
            <p class="font-medium">{{ formatDate(expedition.etd) }}</p>
          </div>
        </div>
      </div>

      <!-- Goods Form -->
      <form @submit.prevent="submitForm" class="space-y-6">
        <!-- Dispatch/Receive Info -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Dispatch & Receive Information</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Dispatch Date</label>
              <input
                v-model="form.date_dispatch"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Receive Date</label>
              <input
                v-model="form.date_receive"
                type="date"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Dispatch Remarks</label>
              <textarea
                v-model="form.remark_dispatch"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter dispatch remarks..."
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Receive Remarks</label>
              <textarea
                v-model="form.remark_receive"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter receive remarks..."
              ></textarea>
            </div>
          </div>
        </div>

                 <!-- Goods Summary Info -->
         <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
           <h2 class="text-lg font-semibold text-gray-900 mb-4">Goods Summary</h2>
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             <div>
               <label class="block text-sm font-medium text-gray-700 mb-2">Total Goods</label>
               <input
                 v-model="form.total_goods"
                 type="text"
                 min="0"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="Enter total goods..."
               />
             </div>
             <div>
               <label class="block text-sm font-medium text-gray-700 mb-2">Gross Weight (kg)</label>
               <input
                 v-model="form.gross_weight"
                 type="number"
                 min="0"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="Enter gross weight..."
               />
             </div>
             <div>
               <label class="block text-sm font-medium text-gray-700 mb-2">Dimension Total (m³)</label>
               <input
                 v-model="form.dimension_total"
                 type="number"
                 min="0"
                 step="0.01"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="Enter dimension total..."
               />
             </div>
             <div>
               <label class="block text-sm font-medium text-gray-700 mb-2">Packaging</label>
               <input
                 v-model="form.packaging"
                 type="text"
                 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="e.g., boxes, crates, pallets..."
               />
             </div>
           </div>
           
                       <!-- Good Description -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Goods Description</label>
              <input
                v-model="form.good_description"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter general description of all goods..."
              />
            </div>
            
            <!-- Special Instruction -->
            <div class="mt-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Special Instruction</label>
              <textarea
                v-model="form.special_instruction"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter special instructions for handling, delivery, or other requirements..."
              ></textarea>
            </div>
         </div>

        <!-- Goods Details -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Goods Details</h2>
            <Button 
              @click="addGood" 
              type="button"
              variant="outline" 
              size="sm"
            >
              <Icon name="plus" class="h-4 w-4 mr-2" />
              Add Good
            </Button>
          </div>
          
          <div v-if="form.goods.length === 0" class="text-center py-8 text-gray-500">
            <Icon name="package" class="h-12 w-12 mx-auto text-gray-300 mb-4" />
            <p>No goods added yet. Click "Add Good" to get started.</p>
          </div>

          <div v-else class="space-y-4">
            <div 
              v-for="(good, index) in form.goods" 
              :key="index"
              class="border border-gray-200 rounded-lg p-4"
            >
              <div class="flex items-center justify-between mb-3">
                <h3 class="font-medium text-gray-900">Good #{{ index + 1 }}</h3>
                <Button 
                  @click="removeGood(index)" 
                  type="button"
                  variant="outline" 
                  size="sm"
                  class="text-red-600 hover:text-red-700"
                >
                  <Icon name="trash" class="h-4 w-4 mr-2" />
                  Remove
                </Button>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                  <input
                    v-model="good.name"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter good name..."
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                  <input
                    v-model="good.quantity"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter quantity..."
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                  <input
                    v-model="good.unit"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., kg, pcs, boxes..."
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                  <input
                    v-model="good.remark"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Optional remarks..."
                  />
                </div>
              </div>
              
              
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end space-x-3">
          <Button 
            @click="goBack" 
            type="button"
            variant="outline"
          >
            Cancel
          </Button>
          <Button 
            type="submit"
            :disabled="isSubmitting"
            variant="default"
          >
            <Icon v-if="isSubmitting" name="loader" class="h-4 w-4 mr-2 animate-spin" />
            {{ isSubmitting ? 'Updating...' : 'Update Goods' }}
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'

interface Props {
  expedition: any
}

const props = defineProps<Props>()

const isSubmitting = ref(false)

const form = ref({
  remark_dispatch: '',
  remark_receive: '',
  date_dispatch: '',
  date_receive: '',
  total_goods: '',
  gross_weight: '',
  dimension_total: '',
  packaging: '',
  good_description: '',
  special_instruction: '',
  goods: [
    {
      name: '',
      quantity: '',
      unit: '',
      remark: ''
    }
  ]
})

const formatDate = (date: string | Date | null) => {
  if (!date) return 'Not set'
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const formatDateForInput = (date: string | Date | null) => {
  if (!date) return ''
  const d = new Date(date)
  return d.toISOString().split('T')[0]
}

const addGood = () => {
  form.value.goods.push({
    name: '',
    quantity: '',
    unit: '',
    remark: ''
  })
}

const removeGood = (index: number) => {
  if (form.value.goods.length > 1) {
    form.value.goods.splice(index, 1)
  }
}

const loadExistingData = () => {
  if (props.expedition.expedition_goods) {
    const goods = props.expedition.expedition_goods
    
    // Load dispatch/receive info
    form.value.remark_dispatch = goods.remark_dispatch || ''
    form.value.remark_receive = goods.remark_receive || ''
    form.value.date_dispatch = formatDateForInput(goods.date_dispatch)
    form.value.date_receive = formatDateForInput(goods.date_receive)
    
         // Load goods summary info
     form.value.total_goods = goods.total_goods || ''
     form.value.gross_weight = goods.gross_weight || ''
     form.value.dimension_total = goods.dimension_total || ''
     form.value.packaging = goods.packaging || ''
     form.value.good_description = goods.good_description || ''
     form.value.special_instruction = goods.special_instruction || ''
    
         // Load goods details
     if (goods.good_details && goods.good_details.length > 0) {
       form.value.goods = goods.good_details.map((good: any) => ({
         name: good.name || '',
         quantity: good.quantity || '',
         unit: good.unit || '',
         remark: good.remark || ''
       }))
     } else {
       // If no existing goods, start with one empty form
       form.value.goods = [{
         name: '',
         quantity: '',
         unit: '',
         remark: ''
       }]
     }
  }
}

const submitForm = () => {
  isSubmitting.value = true
  
  // Validate that at least one good has all required fields
  const validGoods = form.value.goods.filter(good => 
    good.name.trim() && good.quantity.trim() && good.unit.trim()
  )
  
  if (validGoods.length === 0) {
    alert('Please add at least one good with all required fields.')
    isSubmitting.value = false
    return
  }
  
  // Update form with valid goods only
  form.value.goods = validGoods
  
  router.put(route('expeditions.goods.update', props.expedition.id), form.value, {
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}

const goBack = () => {
  router.visit(route('expeditions.goods.show', props.expedition.id))
}

// Load existing data when component mounts
onMounted(() => {
  loadExistingData()
})
</script>
