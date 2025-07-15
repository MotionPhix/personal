<script setup lang="ts">
interface Props {
  status: string;
  type?: 'project' | 'customer' | 'priority' | 'custom';
  size?: 'sm' | 'md' | 'lg';
  variant?: 'solid' | 'outline' | 'soft';
  customColors?: {
    bg: string;
    text: string;
    border?: string;
  };
}

const props = withDefaults(defineProps<Props>(), {
  type: 'custom',
  size: 'md',
  variant: 'soft',
});

const sizeClasses = {
  sm: 'px-2 py-0.5 text-xs',
  md: 'px-2.5 py-1 text-xs',
  lg: 'px-3 py-1.5 text-sm',
};

const getStatusColors = (status: string, type: string) => {
  const colorMaps = {
    project: {
      'not_started': { bg: 'bg-gray-100', text: 'text-gray-800', border: 'border-gray-200' },
      'in_progress': { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-200' },
      'on_hold': { bg: 'bg-yellow-100', text: 'text-yellow-800', border: 'border-yellow-200' },
      'completed': { bg: 'bg-green-100', text: 'text-green-800', border: 'border-green-200' },
      'cancelled': { bg: 'bg-red-100', text: 'text-red-800', border: 'border-red-200' },
    },
    customer: {
      'active': { bg: 'bg-green-100', text: 'text-green-800', border: 'border-green-200' },
      'inactive': { bg: 'bg-gray-100', text: 'text-gray-800', border: 'border-gray-200' },
      'prospect': { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-200' },
    },
    priority: {
      'low': { bg: 'bg-green-100', text: 'text-green-800', border: 'border-green-200' },
      'medium': { bg: 'bg-yellow-100', text: 'text-yellow-800', border: 'border-yellow-200' },
      'high': { bg: 'bg-orange-100', text: 'text-orange-800', border: 'border-orange-200' },
      'urgent': { bg: 'bg-red-100', text: 'text-red-800', border: 'border-red-200' },
    },
  };

  return colorMaps[type]?.[status] || { bg: 'bg-gray-100', text: 'text-gray-800', border: 'border-gray-200' };
};

const colors = props.customColors || getStatusColors(props.status, props.type);

const variantClasses = {
  solid: `${colors.bg.replace('100', '600')} ${colors.text.replace('800', 'white')} border-transparent`,
  outline: `bg-transparent ${colors.text} ${colors.border} border`,
  soft: `${colors.bg} ${colors.text} border-transparent`,
};

const formatStatus = (status: string) => {
  return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};
</script>

<template>
  <span
    :class="[
      'inline-flex items-center font-medium rounded-full',
      sizeClasses[size],
      variantClasses[variant]
    ]"
  >
    {{ formatStatus(status) }}
  </span>
</template>
