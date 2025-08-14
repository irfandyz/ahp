<template>
  <div class="shipping-instruction-print">
    <!-- Logo Section -->
    <div class="logo-section">
      <img src="/assets/images/logo/logo.png" alt="ARKADAYA EXPRESS LOGISTICS" class="header-logo" />
    </div>

    <!-- Print Header -->
    <div class="print-header">
      <h1 class="document-title">SHIPPING INSTRUCTION</h1>
      <div class="document-number">
        No: <span class="number-field">{{ expedition?.order_number || '_________' }}</span> /AHP-SI/<span class="year-field">{{ String(new Date().getMonth() + 1).padStart(2, '0') }}/{{ new Date().getFullYear() }}</span>
      </div>
    </div>



    <!-- General Information -->
    <div class="section general-info">
      <div class="info-row">
        <span class="label">Date:</span>
        <span class="value">{{ formatDate(expedition?.input_date) }}</span>
      </div>
      <div class="info-row">
        <span class="label">To:</span>
        <span class="value">{{ expedition?.vendor?.company || expedition?.fleet?.fleet_type?.name || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Attn:</span>
        <span class="value">{{ expedition?.vendor?.pic || expedition?.driver?.name || '_________' }}</span>
      </div>
    </div>

    <!-- Shipper Section -->
    <div class="section">
      <h2 class="section-title">SHIPPER (PENGIRIM)</h2>
      <div class="info-row">
        <span class="label">Nama Perusahaan:</span>
        <span class="value">{{ expedition?.customer?.name || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Alamat:</span>
        <span class="value">{{ expedition?.customer?.address || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Kontak & Ph No:</span>
        <span class="value">{{ expedition?.customer?.phone || expedition?.customer?.pic_phone || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Email:</span>
        <span class="value">{{ expedition?.customer?.email || '_________' }}</span>
      </div>
    </div>

    <!-- Consignee Section -->
    <div class="section">
      <h2 class="section-title">CONSIGNEE (PENERIMA)</h2>
      <div class="info-row">
        <span class="label">Nama Perusahaan:</span>
        <span class="value">{{ expedition?.consignee?.company || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Alamat:</span>
        <span class="value">{{ expedition?.consignee?.address || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Kontak & Ph No:</span>
        <span class="value">{{ expedition?.consignee?.phone || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Email:</span>
        <span class="value">{{ expedition?.consignee?.email || '_________' }}</span>
      </div>
    </div>

    <!-- Vessel/Flight/Truck Details -->
    <div class="section">
      <h2 class="section-title">VESSEL/FLIGHT/TRUCK DETAILS</h2>
      <div class="info-row">
        <span class="label">Nama Ekspedisi:</span>
        <span class="value">{{ expedition?.vendor?.company || expedition?.fleet?.fleet_type?.name || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Voyage/Flight/Plate:</span>
        <span class="value">{{ expedition?.route?.name || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Driver/Supir:</span>
        <span class="value">{{ expedition?.driver?.name || expedition?.vendor?.pic || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">ETD:</span>
        <span class="value">{{ formatDate(expedition?.etd) }}</span>
      </div>
      <div class="info-row">
        <span class="label">ETA:</span>
        <span class="value">{{ formatDate(expedition?.eta) || '_________' }}</span>
      </div>
    </div>

    <!-- Port and Destination Section -->
    <div class="section">
      <div class="port-section">
        <div class="port-item">
          <h3 class="port-title">PORT OF LOADING</h3>
          <div class="port-field">{{ expedition?.origin || '_________' }}</div>
        </div>
        <div class="port-item">
          <h3 class="port-title">PORT OF DISCHARGE</h3>
          <div class="port-field">{{ expedition?.destination || '_________' }}</div>
        </div>
        <div class="port-item">
          <h3 class="port-title">FINAL DESTINATION</h3>
          <div class="port-field">{{ expedition?.destination || '_________' }}</div>
        </div>
      </div>
    </div>

    <!-- Marks & Numbers and Documents -->
    <div class="section">
      <div class="marks-docs-section">
        <div class="marks-item">
          <h3 class="marks-title">MARKS & NUMBERS</h3>
          <div class="marks-field">_________</div>
        </div>
        <div class="docs-item">
          <h3 class="docs-title">DOCUMENTS</h3>
          <div class="docs-field">_________</div>
        </div>
      </div>
    </div>

    <!-- Quantity & Descriptions of Goods -->
    <div class="section">
      <h2 class="section-title">QUANTITY & DESCRIPTIONS OF GOODS</h2>
      <div class="info-row">
        <span class="label">Jumlah:</span>
        <span class="value">{{ expedition?.expedition_goods?.total_goods || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Deskripsi Barang:</span>
        <span class="value">{{ expedition?.expedition_goods?.good_description || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Berat Kotor:</span>
        <span class="value">{{ expedition?.expedition_goods?.gross_weight || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Dimensi Total:</span>
        <span class="value">{{ expedition?.expedition_goods?.dimension_total || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Kemasan:</span>
        <span class="value">{{ expedition?.expedition_goods?.packaging || '_________' }}</span>
      </div>
    </div>

    <!-- Special Instructions -->
    <div class="section">
      <h2 class="section-title">SPECIAL INSTRUCTIONS / REMARKS</h2>
      <div class="instructions-box">
        <p>{{ expedition?.expedition_goods?.special_instruction || '_________' }}</p>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer-section">
      <div class="footer-row">
        <span class="label">Instructed by:</span>
        <span class="value">
          {{ expedition?.user?.name || '_________' }}
        </span>
      </div>
      <div class="footer-row">
        <span class="label">Signature:</span>
        <span class="value">
          <div v-if="expedition?.user?.signature" class="flex items-center">
            <img 
              :src="`/storage/${expedition.user.signature}?t=${Date.now()}`" 
              alt="Signature" 
              class="h-12 w-auto rounded"
              @error="handleImageError"
            />
          </div>
          <span v-else class="text-gray-600 font-mono">
            __________________
          </span>
        </span>
      </div>
    </div>

    <!-- Company Footer -->
    <div class="company-footer">
      <p>PT. ARKADAYA HAKATO PERSADA</p>
      <p>Jl. Raya Mabes Hankam No. 41 RT07/002 Jakarta Timur, DKI Jakarta</p>
      <p>Email: info@arkadaya.com</p>
    </div>

    <!-- Print Button -->
    <div class="print-actions">
      <Button @click="printDocument" variant="default" size="lg">
        <Icon name="printer" class="h-4 w-4 mr-2" />
        Print Shipping Instruction
      </Button>
      <Button @click="goBack" variant="outline" size="lg" class="ml-3">
        <Icon name="arrow-left" class="h-4 w-4 mr-2" />
        Back to Expeditions
      </Button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'


interface Props {
  expedition: {
    id: number
    order_number: string
    input_date: string
    etd: string
    origin: string
    destination: string
    eta: string
    user?: {
      id: number
      name: string
      signature?: string
    }
    customer?: {
      id: number
      name: string
      address?: string
      phone?: string
      pic_phone?: string
      email?: string
    }
    consignee?: {
      id: number
      company: string
      address?: string
      phone?: string
      email?: string
    }
    vendor?: {
      id: number
      company: string
      pic?: string
    }
    fleet?: {
      fleet_type?: {
        name: string
      }
    }
    driver?: {
      id: number
      name: string
    }
    route?: {
      id: number
      name: string
    }
    industry_sector?: {
      id: number
      name: string
    }
    expedition_goods?: {
      total_goods?: number
      good_description?: string
      gross_weight?: number
      dimension_total?: number
      packaging?: string
      special_instruction?: string
      good_details?: Array<{
        id: number
        name: string
        quantity: string
        unit: string
        remark?: string
      }>
    }
  }
}

const props = defineProps<Props>()

const formatDate = (date: string | Date | null) => {
  if (!date) return '_________'
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const printDocument = () => {
  window.print()
}

const goBack = () => {
  router.visit(route('expeditions.index'))
}

const handleImageError = (event: Event) => {
  const img = event.target as HTMLImageElement
  console.error('Failed to load signature image:', img.src)
  img.style.display = 'none'
}

onMounted(() => {
  setTimeout(() => {
    window.print()
  }, 500)
})
</script>

<style scoped>
.shipping-instruction-print {
  max-width: 600px;
  padding: 12px;
  font-family: 'Times New Roman', serif;
  line-height: 1.3;
  color: #000;
  background: white;
  font-size: 11px;
}

.logo-section {
  display: flex;
  justify-content: flex-end;
  width: 100%;
  margin-bottom: 8px;
}

.header-logo {
  width: 100px;
  opacity: 0.7;
  filter: brightness(0.8);
}

.print-header {
  text-align: center;
  margin: 0 auto 12px auto;
  padding-bottom: 12px;
  width: 100%;
}

.document-title {
  font-size: 16px;
  font-weight: bold;
  text-decoration: underline;
  margin: 0 0 8px 0;
  letter-spacing: 1.5px;
}

.document-number {
  font-size: 12px;
  text-decoration: underline;
}

.number-field, .year-field {
  text-decoration: underline;
  display: inline-block;
}

.section {
  margin-bottom: 12px;
}

.general-info {
  margin-bottom: 15px;
}

.section-title {
  font-size: 12px;
  font-weight: bold;
  margin-bottom: 6px;
  text-transform: uppercase;
  letter-spacing: 0.8px;
}

.info-row {
  display: flex;
  margin-bottom: 4px;
  align-items: flex-start;
}

.label {
  font-weight: bold;
  min-width: 110px;
  margin-right: 8px;
  letter-spacing: 0.2px;
}

.value {
  flex: 1;
  text-decoration: underline;
  min-height: 14px;
  letter-spacing: 0.1px;
}

.port-section {
  display: flex;
  justify-content: space-between;
  gap: 15px;
  margin-bottom: 8px;
}

.port-item {
  flex: 1;
  text-align: center;
}

.port-title {
  font-size: 11px;
  font-weight: bold;
  margin-bottom: 4px;
  text-decoration: underline;
  letter-spacing: 0.4px;
}

.port-field {
  min-height: 28px;
  border-bottom: 1px solid #000;
  padding: 3px;
}

.marks-docs-section {
  display: flex;
  gap: 20px;
  margin-bottom: 8px;
}

.marks-item {
  flex: 1;
}

.docs-item {
  flex: 1;
}

.marks-title, .docs-title {
  font-size: 11px;
  font-weight: bold;
  margin-bottom: 4px;
  text-decoration: underline;
  letter-spacing: 0.4px;
  text-align: center;
}

.marks-field, .docs-field {
  min-height: 28px;
  border-bottom: 1px solid #000;
  padding: 3px;
  text-align: center;
}

.instructions-box {
  border: 1px solid #000;
  min-height: 50px;
  padding: 8px;
  background: #f9f9f9;
  margin-bottom: 5px;
}

.footer-section {
  margin-top: 15px;
  display: flex;
  justify-content: space-between;
}

.footer-row {
  display: flex;
  align-items: center;
  gap: 10px;
}

.footer-row .label {
  min-width: auto;
  margin-right: 0;
}

.print-actions {
  text-align: center;
  margin-top: 15px;
  padding: 15px;
  border-top: 1px solid #ddd;
}

.company-footer {
  text-align: center;
  margin-top: 10px;
  font-size: 9px;
  color: #5c8bcc;
  line-height: 1.2;
}

.company-footer p {
  margin: 0;
  padding: 0;
}

@media print {
  .shipping-instruction-print {
    padding: 0;
    margin: 10px 80px;
    max-width: none;
  }
  
  .print-actions {
    display: none;
  }
  
  body {
    margin: 0;
    padding: 0;
  }
}
</style>
