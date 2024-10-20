<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
  href: string;
  active?: boolean;
  as?: string;
}>();

const classes = computed(() =>
  props.active
    ? 'py-0.5 md:py-3 px-4 md:px-1 border-s-2 md:border-s-0 md:border-b-2 hover:text-gray-800 focus:outline-none dark:text-neutral-400 dark:border-neutral-200 dark:hover:text-neutral-200 transition relative border-lime-500 font-bold text-gray-800 relative'
    : 'py-0.5 md:py-3 px-4 md:px-1 border-s-2 md:border-s-0 md:border-b-2 hover:text-gray-800 focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-200 transition relative border-transparent text-gray-500 relative'
);
</script>

<template>
  <Link
    :href="href"
    :class="classes"
    :as="as ?? 'a'">
    <slot />
  </Link>
</template>
