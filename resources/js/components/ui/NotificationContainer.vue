<script setup lang="ts">
import { useNotifications } from '@/composables/useNotifications';
import NotificationToast from './NotificationToast.vue';

const { notifications, removeNotification } = useNotifications();
</script>

<template>
  <!-- Notification Container -->
  <div
    aria-live="assertive"
    class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50"
  >
    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
      <TransitionGroup
        tag="div"
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
        class="space-y-4"
      >
        <NotificationToast
          v-for="notification in notifications"
          :key="notification.id"
          v-bind="notification"
          @remove="removeNotification"
        />
      </TransitionGroup>
    </div>
  </div>
</template>
