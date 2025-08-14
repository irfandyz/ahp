<template>
  <AppLayout :title="`Goods - ${expedition.order_number}`">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Expedition Goods</h1>
          <p class="text-muted-foreground">Order #{{ expedition.order_number }}</p>
        </div>
        <div class="flex items-center space-x-3">
          <Button 
            @click="editGoods" 
            variant="outline" 
            size="sm"
          >
            <Icon name="edit" class="h-4 w-4 mr-2" />
            Edit Goods
          </Button>
          <Button 
            @click="goBack" 
            variant="outline" 
            size="sm"
          >
            <Icon name="arrow-left" class="h-4 w-4 mr-2" />
            Back to Expeditions
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

      <!-- Goods Information -->
      <div v-if="expedition.expedition_goods" class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Goods Information</h2>
        
        <!-- Dispatch/Receive Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <h3 class="font-medium text-gray-900 mb-2">Dispatch Details</h3>
            <div class="space-y-2">
              <div>
                <p class="text-sm text-gray-600">Date</p>
                <p class="font-medium">{{ formatDate(expedition.expedition_goods.date_dispatch) || 'Not set' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Remarks</p>
                <p class="font-medium">{{ expedition.expedition_goods.remark_dispatch || 'No remarks' }}</p>
              </div>
            </div>
          </div>
          <div>
            <h3 class="font-medium text-gray-900 mb-2">Receive Details</h3>
            <div class="space-y-2">
              <div>
                <p class="text-sm text-gray-600">Date</p>
                <p class="font-medium">{{ formatDate(expedition.expedition_goods.date_receive) || 'Not set' }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-600">Remarks</p>
                <p class="font-medium">{{ expedition.expedition_goods.remark_receive || 'No remarks' }}</p>
              </div>
            </div>
          </div>
        </div>

                 <!-- Goods Summary Info -->
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
           <div>
             <h3 class="font-medium text-gray-900 mb-2">Goods Summary</h3>
             <div class="space-y-2">
               <div>
                 <p class="text-sm text-gray-600">Total Goods</p>
                 <p class="font-medium">{{ expedition.expedition_goods.total_goods || 'Not set' }}</p>
               </div>
               <div>
                 <p class="text-sm text-gray-600">Gross Weight</p>
                 <p class="font-medium">{{ expedition.expedition_goods.gross_weight ? `${expedition.expedition_goods.gross_weight} kg` : 'Not set' }}</p>
               </div>
             </div>
           </div>
           <div>
             <h3 class="font-medium text-gray-900 mb-2">Additional Details</h3>
             <div class="space-y-2">
               <div>
                 <p class="text-sm text-gray-600">Dimension Total</p>
                 <p class="font-medium">{{ expedition.expedition_goods.dimension_total ? `${expedition.expedition_goods.dimension_total} m³` : 'Not set' }}</p>
               </div>
               <div>
                 <p class="text-sm text-gray-600">Packaging</p>
                 <p class="font-medium">{{ expedition.expedition_goods.packaging || 'Not set' }}</p>
               </div>
             </div>
           </div>
         </div>
         
                   <!-- Goods Description -->
          <div v-if="expedition.expedition_goods.good_description" class="mb-6">
            <h3 class="font-medium text-gray-900 mb-2">Goods Description</h3>
            <p class="text-gray-700">{{ expedition.expedition_goods.good_description }}</p>
          </div>
          
          <!-- Special Instruction -->
          <div v-if="expedition.expedition_goods.special_instruction" class="mb-6">
            <h3 class="font-medium text-gray-900 mb-2">Special Instruction</h3>
            <p class="text-gray-700">{{ expedition.expedition_goods.special_instruction }}</p>
          </div>

        <!-- Goods Details Table -->
        <div v-if="expedition.expedition_goods.good_details && expedition.expedition_goods.good_details.length > 0">
          <h3 class="font-medium text-gray-900 mb-3">Goods Details</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
                             <thead class="bg-gray-50 border-b border-gray-200">
                 <tr>
                   <th class="text-left font-semibold text-gray-700 p-3">Name</th>
                   <th class="text-left font-semibold text-gray-700 p-3">Quantity</th>
                   <th class="text-left font-semibold text-gray-700 p-3">Unit</th>
                   <th class="text-left font-semibold text-gray-700 p-3">Remarks</th>
                 </tr>
               </thead>
               <tbody>
                 <tr 
                   v-for="good in expedition.expedition_goods.good_details" 
                   :key="good.id" 
                   class="border-b border-gray-100 hover:bg-gray-50 transition-colors"
                 >
                   <td class="p-3">{{ good.name }}</td>
                   <td class="p-3">{{ good.quantity }}</td>
                   <td class="p-3">{{ good.unit }}</td>
                   <td class="p-3">{{ good.remark || '-' }}</td>
                 </tr>
               </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- No Goods Message -->
      <div v-else class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm text-center">
        <Icon name="package" class="h-12 w-12 mx-auto text-gray-300 mb-4" />
        <p class="text-lg font-medium text-gray-900 mb-2">No Goods Information</p>
        <p class="text-gray-600 mb-4">This expedition doesn't have any goods information yet.</p>
        <Button @click="createGoods" variant="default">
          <Icon name="plus" class="h-4 w-4 mr-2" />
          Add Goods
        </Button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'

interface Props {
  expedition: any
}

const props = defineProps<Props>()

const formatDate = (date: string | Date | null) => {
  if (!date) return 'Not set'
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const editGoods = () => {
  router.visit(route('expeditions.goods.edit', props.expedition.id))
}

const createGoods = () => {
  router.visit(route('expeditions.goods.create', props.expedition.id))
}

const goBack = () => {
  router.visit(route('expeditions.index'))
}
</script>
