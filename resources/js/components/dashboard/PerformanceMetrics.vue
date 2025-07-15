<script setup lang="ts">
import { IconClock, IconDollarSign, IconTarget, IconTrendingUp } from '@tabler/icons-vue';

interface PerformanceMetrics {
  total_estimated_hours: number;
  total_actual_hours: number;
  total_budget: number;
  hours_variance: number;
  efficiency_rate: number;
  average_project_duration: number;
  completion_rate: number;
}

const props = defineProps<{
  metrics: PerformanceMetrics;
}>();

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount);
};

const formatHours = (hours: number) => {
  return `${hours.toFixed(1)}h`;
};

const getVarianceColor = (variance: number) => {
  if (variance > 0) return 'text-red-600';
  if (variance < 0) return 'text-green-600';
  return 'text-gray-600';
};

const getEfficiencyColor = (rate: number) => {
  if (rate >= 90) return 'text-green-600';
  if (rate >= 70) return 'text-yellow-600';
  return 'text-red-600';
};
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Completion Rate -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
          <IconTarget class="w-5 h-5 text-green-600 mr-2" />
          <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Completion Rate</span>
        </div>
        <span class="text-lg font-semibold text-gray-900 dark:text-white">
          {{ metrics.completion_rate }}%
        </span>
      </div>
      <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
        <div
          class="bg-green-600 h-2 rounded-full transition-all duration-300"
          :style="{ width: `${metrics.completion_rate}%` }"
        ></div>
      </div>
    </div>

    <!-- Efficiency Rate -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex items-center justify-between mb-2">
        <div class="flex items-center">
          <IconTrendingUp class="w-5 h-5 text-blue-600 mr-2" />
          <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Efficiency Rate</span>
        </div>
        <span :class="['text-lg font-semibold', getEfficiencyColor(metrics.efficiency_rate)]">
          {{ metrics.efficiency_rate }}%
        </span>
      </div>
      <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
        <div
          :class="[
            'h-2 rounded-full transition-all duration-300',
            metrics.efficiency_rate >= 90 ? 'bg-green-600' :
            metrics.efficiency_rate >= 70 ? 'bg-yellow-600' : 'bg-red-600'
          ]"
          :style="{ width: `${Math.min(metrics.efficiency_rate, 100)}%` }"
        ></div>
      </div>
    </div>

    <!-- Total Budget -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <IconDollarSign class="w-5 h-5 text-green-600 mr-2" />
          <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Budget</span>
        </div>
        <span class="text-lg font-semibold text-gray-900 dark:text-white">
          {{ formatCurrency(metrics.total_budget) }}
        </span>
      </div>
      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
        Across all projects
      </p>
    </div>

    <!-- Average Duration -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <IconClock class="w-5 h-5 text-purple-600 mr-2" />
          <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Duration</span>
        </div>
        <span class="text-lg font-semibold text-gray-900 dark:text-white">
          {{ Math.round(metrics.average_project_duration) }} days
        </span>
      </div>
      <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
        Per project completion
      </p>
    </div>
  </div>

  <!-- Detailed Metrics -->
  <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Time Analysis</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Estimated vs Actual Hours -->
      <div class="space-y-2">
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-600 dark:text-gray-400">Estimated Hours</span>
          <span class="text-sm font-medium text-gray-900 dark:text-white">
            {{ formatHours(metrics.total_estimated_hours) }}
          </span>
        </div>
        <div class="flex justify-between items-center">
          <span class="text-sm text-gray-600 dark:text-gray-400">Actual Hours</span>
          <span class="text-sm font-medium text-gray-900 dark:text-white">
            {{ formatHours(metrics.total_actual_hours) }}
          </span>
        </div>
        <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
          <span class="text-sm text-gray-600 dark:text-gray-400">Variance</span>
          <span :class="['text-sm font-medium', getVarianceColor(metrics.hours_variance)]">
            {{ metrics.hours_variance > 0 ? '+' : '' }}{{ formatHours(metrics.hours_variance) }}
          </span>
        </div>
      </div>

      <!-- Hours Breakdown Chart Placeholder -->
      <div class="col-span-2">
        <div class="h-24 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
          <p class="text-sm text-gray-500 dark:text-gray-400">Hours breakdown chart placeholder</p>
        </div>
      </div>
    </div>
  </div>
</template>
