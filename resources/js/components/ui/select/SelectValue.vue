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
  placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Select an option',
});

const select = inject<SelectContext>('select');

const displayValue = computed(() => {
  if (select?.selectedValue.value) {
    return select.selectedValue.value;
  }
  return props.placeholder;
});

const valueClass = computed(() => {
  const baseClasses = ['block truncate'];
  
  if (!select?.selectedValue.value) {
    baseClasses.push('text-muted-foreground');
  } else {
    baseClasses.push('text-foreground');
  }
  
  return baseClasses.join(' ');
});
</script>

<template>
  <span :class="valueClass">
    {{ displayValue }}
  </span>
</template>
