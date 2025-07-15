<script setup lang="ts">
interface Props {
  size?: 'sm' | 'md' | 'lg' | 'xl';
  color?: 'primary' | 'secondary' | 'white' | 'gray';
  text?: string;
  overlay?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  color: 'primary',
  overlay: false,
});

const sizeClasses = {
  sm: 'w-4 h-4',
  md: 'w-6 h-6',
  lg: 'w-8 h-8',
  xl: 'w-12 h-12',
};

const colorClasses = {
  primary: 'text-blue-600',
  secondary: 'text-gray-600',
  white: 'text-white',
  gray: 'text-gray-400',
};
</script>

<template>
  <div
    :class="[
      'flex items-center justify-center',
      overlay ? 'fixed inset-0 bg-black bg-opacity-50 z-50' : '',
      props.text ? 'flex-col space-y-2' : ''
    ]"
  >
    <div class="relative">
      <div
        :class="[
          'animate-spin rounded-full border-2 border-transparent',
          sizeClasses[size],
          colorClasses[color]
        ]"
        style="border-top-color: currentColor; border-right-color: currentColor;"
      ></div>
    </div>

    <p
      v-if="text"
      :class="[
        'text-sm font-medium',
        overlay ? 'text-white' : 'text-gray-600 dark:text-gray-400'
      ]"
    >
      {{ text }}
    </p>
  </div>
</template>
