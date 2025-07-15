<script setup lang="ts">
import { IconAlertTriangle, IconCalendar, IconBarChart3 } from '@tabler/icons-vue';

interface ProjectInsights {
  status_distribution: Record<string, number>;
  priority_distribution: Record<string, number>;
  production_types: string[];
  categories: string[];
  overdue_projects: number;
  upcoming_deadlines: Array<{
    pid: string;
    name: string;
    end_date: string;
    days_left: number;
    customer: string;
    priority: string;
    priority_color: string;
  }>;
}

const props = defineProps<{
  insights: ProjectInsights;
}>();

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    'not_started': 'bg-gray-500',
    'in_progress': 'bg-blue-500',
    'on_hold': 'bg-yellow-500',
    'completed': 'bg-green-500',
    'cancelled': 'bg-red-500',
  };
  return colors[status] || 'bg-gray-500';
};

const getPriorityColor = (priority: string) => {
  const colors: Record<string, string> = {
    'low': 'bg-green-100 text-green-800',
    'medium': 'bg-yellow-100 text-yellow-800',
    'high': 'bg-orange-100 text-orange-800',
    'urgent': 'bg-red-100 text-red-800',
  };
  return colors[priority] || 'bg-gray-100 text-gray-800';
};

const totalProjects = Object.values(props.insights.status_distribution).reduce((sum, count) => sum + count, 0);
</script>

<template>
  <div class="space-y-6">
    <!-- Status Distribution -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex items-center mb-4">
        <IconBarChart3 class="w-5 h-5 text-blue-600 mr-2" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Project Status Distribution</h3>
      </div>

      <div class="space-y-3">
        <div
          v-for="(count, status) in insights.status_distribution"
          :key="status"
          class="flex items-center justify-between"
        >
          <div class="flex items-center">
            <div :class="['w-3 h-3 rounded-full mr-3', getStatusColor(status)]"></div>
            <span class="text-sm text-gray-600 dark:text-gray-400 capitalize">
              {{ status.replace('_', ' ') }}
            </span>
          </div>
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ count }}</span>
            <span class="text-xs text-gray-500">
              ({{ totalProjects > 0 ? Math.round((count / totalProjects) * 100) : 0 }}%)
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div v-if="insights.upcoming_deadlines.length > 0" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex items-center mb-4">
        <IconCalendar class="w-5 h-5 text-orange-600 mr-2" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Upcoming Deadlines</h3>
      </div>

      <div class="space-y-3">
        <div
          v-for="project in insights.upcoming_deadlines"
          :key="project.pid"
          class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg"
        >
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ project.name }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ project.customer }}</p>
          </div>
          <div class="flex items-center space-x-2">
            <span :class="['px-2 py-1 text-xs rounded-full', getPriorityColor(project.priority)]">
              {{ project.priority }}
            </span>
            <div class="text-right">
              <p class="text-xs text-gray-500 dark:text-gray-400">{{ project.end_date }}</p>
              <p class="text-xs font-medium text-orange-600">{{ project.days_left }} days left</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Overdue Projects Alert -->
    <div v-if="insights.overdue_projects > 0" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
      <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
        <IconAlertTriangle class="w-6 h-6 text-red-600 mr-3" />
        <div>
          <h4 class="text-sm font-medium text-red-800 dark:text-red-200">
            {{ insights.overdue_projects }} Overdue Project{{ insights.overdue_projects > 1 ? 's' : '' }}
          </h4>
          <p class="text-xs text-red-600 dark:text-red-400">
            These projects require immediate attention
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
