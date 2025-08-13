<template>
  <AppLayout title="Edit Route">
    <template #header>
      <div class="flex items-center justify-between">
                  <Heading title="Edit Route" />
        <Button variant="outline" as-child>
                  <Link :href="route('routes.index')">
          <Icon name="arrow-left" class="mr-2 h-4 w-4" />
          Back to Routes
        </Link>
        </Button>
      </div>
    </template>

    <div class="p-5">
      <Card>
        <CardHeader>
          <CardTitle>Edit Route</CardTitle>
          <CardDescription>
                          Update the route information below
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Route Code Field -->
            <div class="space-y-2">
              <Label for="code" class="text-sm font-medium">Route Code</Label>
              <Input
                id="code"
                v-model="form.code"
                type="text"
                placeholder="e.g., LAND, SEA, AIR, IMPORT_SEA"
                :class="form.errors.code ? 'border-red-500 focus:border-red-500' : ''"
              />
              <p v-if="form.errors.code" class="text-sm text-red-600 mt-1">
                {{ form.errors.code }}
              </p>
              <p class="text-sm text-gray-500">Use uppercase letters and underscores (e.g., IMPORT_SEA, EXPORT_AIR)</p>
            </div>

            <!-- Route Name Field -->
            <div class="space-y-2">
              <Label for="name" class="text-sm font-medium">Route Name</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="e.g., LAND, SEA, AIR"
                :class="form.errors.name ? 'border-red-500 focus:border-red-500' : ''"
              />
              <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">
                {{ form.errors.name }}
              </p>
            </div>

            <!-- Description Field -->
            <div class="space-y-2">
                          <Label for="description" class="text-sm font-medium">Description</Label>
            <Textarea
              id="description"
              v-model="form.description"
              placeholder="Describe the route and its use cases"
              :rows="4"
              :class="form.errors.description ? 'border-red-500 focus:border-red-500' : ''"
            />
            <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">
              {{ form.errors.description }}
            </p>
          </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
              <Button variant="outline" type="button" as-child>
                <Link :href="route('routes.index')">
                  Cancel
                </Link>
              </Button>
              <Button type="submit" :disabled="form.processing">
                <Icon v-if="form.processing" name="loader" class="mr-2 h-4 w-4 animate-spin" />
                {{ form.processing ? 'Updating...' : 'Update Route' }}
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
import Heading from '@/components/Heading.vue'
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

interface Route {
  id: number
  code: string
  name: string
  description: string
}

interface Props {
  route: Route
}

const props = defineProps<Props>()

const form = useForm({
  code: props.route.code,
  name: props.route.name,
  description: props.route.description
})

const submit = () => {
  form.put(route('routes.update', props.route.id))
}
</script>
