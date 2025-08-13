<script setup lang="ts">
import { inject, computed } from 'vue';

interface SelectContext {
  isOpen: { value: boolean };
  selectedValue: { value: string };
  updateValue: (value: string) => void;
  closeDropdown: () => void;
  disabled: boolean;
}

interface Props {
  class?: string;
}

const props = defineProps<Props>();

const select = inject<SelectContext>('select');

const triggerClass = computed(() => {
  const baseClasses = [
    'flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background',
    'placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
    'disabled:cursor-not-allowed disabled:opacity-50',
    'hover:bg-accent/50 transition-colors duration-150'
  ];
  
  return [...baseClasses, props.class].filter(Boolean).join(' ');
});

const toggleOpen = () => {
  if (select && !select.disabled) {
    if (select.isOpen.value) {
      select.closeDropdown();
    } else {
      select.isOpen.value = true;
    }
  }
};

const handleKeyDown = (event: KeyboardEvent) => {
  if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault();
    toggleOpen();
  } else if (event.key === 'Escape' && select?.isOpen.value) {
    select.closeDropdown();
  }
};
</script>

<template>
  <button
    type="button"
    :class="triggerClass"
    :disabled="select?.disabled"
    :aria-expanded="select?.isOpen.value"
    :aria-haspopup="true"
    role="combobox"
    tabindex="0"
    @click="toggleOpen"
    @keydown="handleKeyDown"
  >
    <slot />
    <svg
      class="ml-2 h-4 w-4 transition-transform duration-200"
      :class="{ 'rotate-180': select?.isOpen.value }"
      fill="none"
      stroke="currentColor"
      viewBox="0 0 24 24"
    >
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
    </svg>
  </button>
</template>
