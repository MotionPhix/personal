<script setup lang="ts">
import { IconPlus } from '@tabler/icons-vue';

interface Props {
  title: string;
  description?: string;
  icon?: any;
  actionText?: string;
  actionRoute?: string;
  actionClick?: () => void;
  size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
});

const sizeClasses = {
  sm: {
    container: 'max-w-xs py-8',
    icon: 'size-8',
    title: 'text-base',
    description: 'text-xs',
    button: 'px-3 py-1.5 text-xs',
  },
  md: {
    container: 'max-w-sm py-12',
    icon: 'size-12',
    title: 'text-lg',
    description: 'text-sm',
    button: 'px-4 py-2 text-sm',
  },
  lg: {
    container: 'max-w-md py-16',
    icon: 'size-16',
    title: 'text-xl',
    description: 'text-base',
    button: 'px-6 py-3 text-base',
  },
};
</script>

<template>
  <div
    :class="[
      'flex flex-col items-center justify-center text-center px-6 mx-auto',
      sizeClasses[size].container
    ]"
  >
    <!-- Icon -->
    <div
      :class="[
        'flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 mb-4',
        sizeClasses[size].icon
      ]"
    >
      <component
        :is="icon"
        :class="[
          'text-gray-400 dark:text-gray-600',
          size === 'sm' ? 'w-4 h-4' : size === 'md' ? 'w-6 h-6' : 'w-8 h-8'
        ]"
      />
    </div>

    <!-- Title -->
    <h3
      :class="[
        'font-semibold text-gray-900 dark:text-white mb-2',
        sizeClasses[size].title
      ]"
    >
      {{ title }}
    </h3>

    <!-- Description -->
    <p
      v-if="description"
      :class="[
        'text-gray-600 dark:text-gray-400 mb-6',
        sizeClasses[size].description
      ]"
    >
      {{ description }}
    </p>

    <!-- Action Button -->
    <div v-if="actionText && (actionRoute || actionClick)">
      <Link
        v-if="actionRoute"
        :href="actionRoute"
        :class="[
          'inline-flex items-center justify-center font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md transition-colors gap-x-2',
          sizeClasses[size].button
        ]"
      >
        <IconPlus :class="size === 'sm' ? 'w-3 h-3' : 'w-4 h-4'" />
        {{ actionText }}
      </Link>

      <button
        v-else-if="actionClick"
        @click="actionClick"
        :class="[
          'inline-flex items-center justify-center font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md transition-colors gap-x-2',
          sizeClasses[size].button
        ]"
      >
        <IconPlus :class="size === 'sm' ? 'w-3 h-3' : 'w-4 h-4'" />
        {{ actionText }}
      </button>
    </div>
  </div>
</template>
