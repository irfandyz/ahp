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
  value: string;
  class?: string;
}

const props = defineProps<Props>();

const select = inject<SelectContext>('select');

const isSelected = computed(() => select?.selectedValue.value === props.value);

const itemClass = computed(() => {
  const baseClasses = [
    'relative flex w-full cursor-pointer select-none items-center rounded-sm py-2 pl-8 pr-2 text-sm outline-none',
    'focus:bg-accent focus:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50',
    'hover:bg-accent hover:text-accent-foreground transition-colors duration-150'
  ];
  
  if (isSelected.value) {
    baseClasses.push('bg-accent text-accent-foreground font-medium');
  }
  
  return [...baseClasses, props.class].filter(Boolean).join(' ');
});

const handleClick = () => {
  if (select && !select.disabled) {
    select.updateValue(props.value);
  }
};

const handleKeyDown = (event: KeyboardEvent) => {
  if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault();
    handleClick();
  }
};
</script>

<template>
  <div
    :class="itemClass"
    :data-disabled="select?.disabled"
    :data-selected="isSelected"
    role="option"
    :aria-selected="isSelected"
    tabindex="0"
    @click="handleClick"
    @keydown="handleKeyDown"
  >
    <span class="absolute left-2 flex h-3.5 w-3.5 items-center justify-center">
      <svg
        v-if="isSelected"
        class="h-4 w-4 text-primary"
        fill="currentColor"
        viewBox="0 0 20 20"
      >
        <path
          fill-rule="evenodd"
          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
          clip-rule="evenodd"
        />
      </svg>
    </span>
    <slot />
  </div>
</template>
