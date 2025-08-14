<template>
  <div class="shipping-instruction-print" ref="printArea">
    <!-- Print Header -->
    <div class="print-header">
      <h1 class="document-title">SHIPPING INSTRUCTION</h1>
      <div class="document-number">
        No: <span class="number-field">{{ expedition?.order_number || '_________' }}</span> /AHP-SI/ <span class="year-field">{{ new Date().getFullYear() }}</span>
      </div>
    </div>

    <!-- General Information -->
    <div class="section">
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
        <span class="value">{{ expedition?.customer?.name || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Alamat:</span>
        <span class="value">{{ expedition?.destination || '_________' }}</span>
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

    <!-- Vessel/Flight/Truck Details -->
    <div class="section">
      <h2 class="section-title">VESSEL/FLIGHT/TRUCK DETAILS</h2>
      <div class="info-row">
        <span class="label">Nama Ekspedisi:</span>
        <span class="value">{{ expedition?.vendor?.company || expedition?.fleet?.fleet_type?.name || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Voyage/Flight/Plate:</span>
        <span class="value">{{ expedition?.fleet?.plate_number || expedition?.vendor?.fleet || '_________' }}</span>
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
          <div class="marks-field">{{ expedition?.order_number || '_________' }}</div>
        </div>
        <div class="docs-item">
          <h3 class="docs-title">DOCUMENTS</h3>
          <div class="docs-field">Shipping Instruction, Invoice, Packing List</div>
        </div>
      </div>
    </div>

    <!-- Quantity & Descriptions of Goods -->
    <div class="section">
      <h2 class="section-title">QUANTITY & DESCRIPTIONS OF GOODS</h2>
      <div class="info-row">
        <span class="label">Jumlah:</span>
        <span class="value">{{ expedition?.description ? '1 Lot' : '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Deskripsi Barang:</span>
        <span class="value">{{ expedition?.description || '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Berat Kotor:</span>
        <span class="value">{{ expedition?.distance ? `${expedition.distance} kg` : '_________' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Dimensi Total:</span>
        <span class="value">_________</span>
      </div>
      <div class="info-row">
        <span class="label">Kemasan:</span>
        <span class="value">_________</span>
      </div>
    </div>

    <!-- Special Instructions -->
    <div class="section">
      <h2 class="section-title">SPECIAL INSTRUCTIONS / REMARKS</h2>
      <div class="instructions-box">
        <p>{{ expedition?.detail_route || 'Harap diperhatikan pengiriman barang dengan hati-hati dan sesuai dengan ketentuan yang berlaku.' }}</p>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer-section">
      <div class="footer-row">
        <span class="label">Instructed by:</span>
        <span class="value">{{ expedition?.user?.name || '_________' }}</span>
      </div>
      <div class="footer-row">
        <span class="label">Signature:</span>
        <span class="value">_________</span>
      </div>
    </div>

    <!-- Print Button (hidden when printing) -->
    <div class="print-actions" v-if="!isPrinting">
      <Button @click="printDocument" variant="default" size="lg">
        <Icon name="printer" class="h-4 w-4 mr-2" />
        Print Shipping Instruction
      </Button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Button } from '@/components/ui/button'
import { Icon } from '@/components/Icon'

interface Props {
  expedition?: any
}

const props = defineProps<Props>()
const printArea = ref<HTMLElement>()
const isPrinting = ref(false)

const formatDate = (date: string | Date | null) => {
  if (!date) return '_________'
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const printDocument = () => {
  isPrinting.value = true
  
  // Wait for next tick to ensure DOM is updated
  setTimeout(() => {
    const printContent = printArea.value?.innerHTML
    const originalContent = document.body.innerHTML
    
    // Replace body content with print content
    document.body.innerHTML = printContent || ''
    
    // Print
    window.print()
    
    // Restore original content
    document.body.innerHTML = originalContent
    
    // Re-mount Vue app
    window.location.reload()
  }, 100)
}

onMounted(() => {
  // Add print styles
  const style = document.createElement('style')
  style.textContent = `
    @media print {
      body * {
        visibility: hidden;
      }
      .shipping-instruction-print, .shipping-instruction-print * {
        visibility: visible;
      }
      .shipping-instruction-print {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
      }
      .print-actions {
        display: none !important;
      }
    }
  `
  document.head.appendChild(style)
})
</script>

<style scoped>
.shipping-instruction-print {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Times New Roman', serif;
  line-height: 1.6;
  color: #000;
  background: white;
}

.print-header {
  text-align: center;
  margin-bottom: 30px;
  border-bottom: 2px solid #000;
  padding-bottom: 20px;
}

.document-title {
  font-size: 24px;
  font-weight: bold;
  text-decoration: underline;
  margin-bottom: 15px;
}

.document-number {
  font-size: 16px;
}

.number-field, .year-field {
  text-decoration: underline;
  min-width: 100px;
  display: inline-block;
}

.section {
  margin-bottom: 25px;
}

.section-title {
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 15px;
  text-transform: uppercase;
}

.info-row {
  display: flex;
  margin-bottom: 10px;
  align-items: flex-start;
}

.label {
  font-weight: bold;
  min-width: 150px;
  margin-right: 10px;
}

.value {
  flex: 1;
  text-decoration: underline;
  min-height: 20px;
}

.port-section {
  display: flex;
  justify-content: space-between;
  gap: 20px;
}

.port-item {
  flex: 1;
  text-align: center;
}

.port-title {
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 10px;
  text-decoration: underline;
}

.port-field {
  min-height: 40px;
  border-bottom: 1px solid #000;
  padding: 5px;
}

.marks-docs-section {
  display: flex;
  gap: 20px;
}

.marks-item {
  flex: 2;
}

.docs-item {
  flex: 1;
}

.marks-title, .docs-title {
  font-size: 14px;
  font-weight: bold;
  margin-bottom: 10px;
  text-decoration: underline;
}

.marks-field, .docs-field {
  min-height: 40px;
  border-bottom: 1px solid #000;
  padding: 5px;
}

.instructions-box {
  border: 1px solid #000;
  min-height: 80px;
  padding: 15px;
  background: #f9f9f9;
}

.footer-section {
  margin-top: 40px;
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
  margin-top: 30px;
  padding: 20px;
  border-top: 1px solid #ddd;
}

/* Print-specific styles */
@media print {
  .shipping-instruction-print {
    padding: 0;
    margin: 0;
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
