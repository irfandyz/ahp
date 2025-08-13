<script setup lang="ts">
import { computed, provide, ref, watch } from 'vue';

interface Props {
  modelValue?: string;
  disabled?: boolean;
  class?: string;
}

const props = defineProps<Props>();

const emit = defineEmits<{
  'update:modelValue': [value: string];
}>();

const isOpen = ref(false);
const selectedValue = ref(props.modelValue || '');

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
  selectedValue.value = newValue || '';
});

const selectClass = computed(() => {
  const baseClasses = [
    'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background',
    'placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
    'disabled:cursor-not-allowed disabled:opacity-50'
  ];
  
  return [...baseClasses, props.class].filter(Boolean).join(' ');
});

const updateValue = (value: string) => {
  selectedValue.value = value;
  emit('update:modelValue', value);
  isOpen.value = false;
};

const toggleOpen = () => {
  if (!props.disabled) {
    isOpen.value = !isOpen.value;
  }
};

// Close dropdown when clicking outside
const closeDropdown = () => {
  isOpen.value = false;
};

provide('select', {
  isOpen,
  selectedValue,
  updateValue,
  closeDropdown,
  disabled: props.disabled
});
</script>

<template>
  <div class="relative">
    <button
      type="button"
      :class="selectClass"
      :disabled="disabled"
      @click="toggleOpen"
    >
      <span v-if="selectedValue" class="block truncate">
        {{ selectedValue }}
      </span>
      <span v-else class="block truncate text-muted-foreground">
        Select an option
      </span>
      <svg
        class="ml-2 h-4 w-4 transition-transform duration-200"
        :class="{ 'rotate-180': isOpen }"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    
    <slot />
  </div>
</template>
