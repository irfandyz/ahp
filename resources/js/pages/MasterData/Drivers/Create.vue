<template>
  <AppLayout title="Add Driver">
    <div class="space-y-6 p-5">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Add Driver</h1>
          <p class="text-muted-foreground">Create a new driver for shipping operations</p>
        </div>
        <Link :href="route('drivers.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
          <Icon name="arrow-left" class="mr-2 h-4 w-4" />
          Back to Drivers
        </Link>
      </div>

      <!-- Form -->
      <Card>
        <CardHeader>
          <CardTitle>Driver Information</CardTitle>
          <CardDescription>
            Enter the driver's details below
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <!-- Name -->
              <div class="space-y-2">
                <Label for="name">Name *</Label>
                <Input
                  id="name"
                  v-model="form.name"
                  type="text"
                  :class="{ 'border-red-500': form.errors.name }"
                  placeholder="Enter driver name"
                />
                <p v-if="form.errors.name" class="text-sm text-red-600">
                  {{ form.errors.name }}
                </p>
              </div>

              <!-- Phone -->
              <div class="space-y-2">
                <Label for="phone">Phone</Label>
                <Input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  placeholder="Enter phone number"
                />
                <p v-if="form.errors.phone" class="text-sm text-red-600">
                  {{ form.errors.phone }}
                </p>
              </div>

              <!-- Email -->
              <div class="space-y-2">
                <Label for="email">Email</Label>
                <Input
                  id="email"
                  v-model="form.email"
                  type="email"
                  placeholder="Enter email address"
                />
                <p v-if="form.errors.email" class="text-sm text-red-600">
                  {{ form.errors.email }}
                </p>
              </div>

              <!-- Address -->
              <div class="space-y-2">
                <Label for="address">Address</Label>
                <Textarea
                  id="address"
                  v-model="form.address"
                  placeholder="Enter address"
                  :rows="3"
                />
                <p v-if="form.errors.address" class="text-sm text-red-600">
                  {{ form.errors.address }}
                </p>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
              <Link :href="route('drivers.index')" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Cancel
              </Link>
              <Button type="submit" :disabled="form.processing">
                <Icon v-if="form.processing" name="loader" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Creating...' : 'Create Driver' }}
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

const form = useForm({
  name: '',
  phone: '',
  email: '',
  address: '',
})

const submit = () => {
  form.post(route('drivers.store'))
}
</script>
