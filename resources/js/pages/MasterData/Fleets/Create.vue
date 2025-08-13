<template>
  <AppLayout title="Add Fleet Vehicle">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Add Fleet Vehicle</h1>
          <p class="text-muted-foreground">Create a new fleet vehicle</p>
        </div>
        <Link :href="route('fleets.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <Icon name="arrow-left" class="mr-2 h-4 w-4" />
          Back to Vehicles
        </Link>
      </div>

      <!-- Form -->
      <Card>
        <CardHeader>
          <CardTitle>Vehicle Information</CardTitle>
          <CardDescription>
            Enter the vehicle details below
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <!-- Fleet Type -->
              <div class="space-y-2">
                <Label for="fleet_type_id">Fleet Type *</Label>
                <select
                  id="fleet_type_id"
                  v-model="form.fleet_type_id"
                  :class="[
                    'w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500',
                    { 'border-red-500': form.errors.fleet_type_id }
                  ]"
                >
                  <option value="">Select fleet type</option>
                  <option 
                    v-for="fleetType in fleetTypes" 
                    :key="fleetType.id" 
                    :value="fleetType.id.toString()"
                  >
                    {{ fleetType.name }}
                  </option>
                </select>
                <p v-if="form.errors.fleet_type_id" class="text-sm text-red-600">
                  {{ form.errors.fleet_type_id }}
                </p>
              </div>

              <!-- Plate Number -->
              <div class="space-y-2">
                <Label for="plate_number">Plate Number *</Label>
                <Input
                  id="plate_number"
                  v-model="form.plate_number"
                  type="text"
                  :class="{ 'border-red-500': form.errors.plate_number }"
                  placeholder="Enter plate number"
                />
                <p v-if="form.errors.plate_number" class="text-sm text-red-600">
                  {{ form.errors.plate_number }}
                </p>
              </div>

              <!-- Description -->
              <div class="space-y-2 sm:col-span-2">
                <Label for="description">Description</Label>
                <Textarea
                  id="description"
                  v-model="form.description"
                  placeholder="Enter vehicle description (optional)"
                  :rows="3"
                />
                <p v-if="form.errors.description" class="text-sm text-red-600">
                  {{ form.errors.description }}
                </p>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
              <Link :href="route('fleets.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Cancel
              </Link>
              <Button type="submit" :disabled="form.processing">
                <Icon v-if="form.processing" name="loader" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Creating...' : 'Create Vehicle' }}
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
import AppLayout from '@/layouts/AppLayout.vue'
import Icon from '@/components/Icon.vue'
import Button from '@/components/ui/button/Button.vue'
import Card from '@/components/ui/card/Card.vue'
import CardContent from '@/components/ui/card/CardContent.vue'
import CardDescription from '@/components/ui/card/CardDescription.vue'
import CardHeader from '@/components/ui/card/CardHeader.vue'
import CardTitle from '@/components/ui/card/CardTitle.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import Textarea from '@/components/ui/textarea/Textarea.vue'


interface FleetType {
  id: number
  name: string
}

interface Props {
  fleetTypes: FleetType[]
}

const props = defineProps<Props>()

const form = useForm({
  fleet_type_id: '',
  plate_number: '',
  description: '',
})

const submit = () => {
  form.post(route('fleets.store'))
}
</script>
