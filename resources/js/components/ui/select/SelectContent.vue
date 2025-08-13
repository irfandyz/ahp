<script setup lang="ts">
import { computed, inject, onMounted, onUnmounted, ref } from 'vue';

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
const contentRef = ref<HTMLDivElement>();

const isVisible = computed(() => select?.isOpen.value);

const contentClass = computed(() => {
  const baseClasses = [
    'absolute top-full z-50 mt-1 w-full rounded-md border bg-popover text-popover-foreground shadow-md',
    'max-h-60 overflow-auto',
    'data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0',
    'data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95'
  ];
  
  return [...baseClasses, props.class].filter(Boolean).join(' ');
});

const handleClickOutside = (event: Event) => {
  if (contentRef.value && !contentRef.value.contains(event.target as Node)) {
    select?.closeDropdown();
  }
};

const handleEscapeKey = (event: KeyboardEvent) => {
  if (event.key === 'Escape') {
    select?.closeDropdown();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
  document.addEventListener('keydown', handleEscapeKey);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
  document.removeEventListener('keydown', handleEscapeKey);
});
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-200"
    enter-from-class="opacity-0 translate-y-1"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition ease-in duration-150"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-1"
  >
    <div
      v-if="isVisible"
      ref="contentRef"
      :class="contentClass"
      data-state="open"
    >
      <slot />
    </div>
  </Transition>
</template>
