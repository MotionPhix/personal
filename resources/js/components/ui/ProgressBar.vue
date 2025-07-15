<script setup lang="ts">
interface Props {
  value: number;
  max?: number;
  size?: 'sm' | 'md' | 'lg';
  color?: 'blue' | 'green' | 'yellow' | 'red' | 'purple' | 'gray';
  showLabel?: boolean;
  label?: string;
  animated?: boolean;
  striped?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  max: 100,
  size: 'md',
  color: 'blue',
  showLabel: false,
  animated: false,
  striped: false,
});

const percentage = computed(() => {
  return Math.min(Math.max((props.value / props.max) * 100, 0), 100);
});

const sizeClasses = {
  sm: 'h-1',
  md: 'h-2',
  lg: 'h-3',
};

const colorClasses = {
  blue: 'bg-blue-600',
  green: 'bg-green-600',
  yellow: 'bg-yellow-600',
  red: 'bg-red-600',
  purple: 'bg-purple-600',
  gray: 'bg-gray-600',
};

const getColorIntensity = (percentage: number) => {
  if (percentage >= 80) return 'green';
  if (percentage >= 60) return 'yellow';
  if (percentage >= 40) return 'yellow';
  if (percentage >= 20) return 'red';
  return 'red';
};

const dynamicColor = props.color === 'blue' ? getColorIntensity(percentage.value) : props.color;
</script>

<template>
  <div class="w-full">
    <!-- Label -->
    <div v-if="showLabel || label" class="flex justify-between items-center mb-1">
      <span v-if="label" class="text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ label }}
      </span>
      <span v-if="showLabel" class="text-sm text-gray-500 dark:text-gray-400">
        {{ Math.round(percentage) }}%
      </span>
    </div>

    <!-- Progress Bar -->
    <div
      :class="[
        'w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden',
        sizeClasses[size]
      ]"
    >
      <div
        :class="[
          'h-full transition-all duration-300 ease-out rounded-full',
          colorClasses[dynamicColor],
          striped ? 'bg-stripes' : '',
          animated ? 'animate-pulse' : ''
        ]"
        :style="{ width: `${percentage}%` }"
      >
        <!-- Striped pattern -->
        <div
          v-if="striped"
          class="h-full w-full opacity-25"
          style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.1) 10px, rgba(255,255,255,.1) 20px)"
        ></div>
      </div>
    </div>

    <!-- Value display -->
    <div v-if="$slots.default" class="mt-1">
      <slot :percentage="percentage" :value="value" :max="max" />
    </div>
  </div>
</template>

<style scoped>
@keyframes stripe-animation {
  0% {
    background-position: 0 0;
  }
  100% {
    background-position: 40px 0;
  }
}

.bg-stripes {
  animation: stripe-animation 1s linear infinite;
}
</style>
