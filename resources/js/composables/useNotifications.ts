import {ref, reactive, readonly} from 'vue';

export interface Notification {
  id: string;
  type: 'success' | 'error' | 'warning' | 'info';
  title?: string;
  message: string;
  duration?: number;
  persistent?: boolean;
  actions?: Array<{
    label: string;
    action: () => void;
    style?: 'primary' | 'secondary';
  }>;
}

const notifications = ref<Notification[]>([]);

export function useNotifications() {
  const addNotification = (notification: Omit<Notification, 'id'>) => {
    const id = Math.random().toString(36).substr(2, 9);
    const newNotification: Notification = {
      id,
      duration: 5000,
      ...notification,
    };

    notifications.value.push(newNotification);

    // Auto-remove notification after duration (unless persistent)
    if (!newNotification.persistent && newNotification.duration) {
      setTimeout(() => {
        removeNotification(id);
      }, newNotification.duration);
    }

    return id;
  };

  const removeNotification = (id: string) => {
    const index = notifications.value.findIndex(n => n.id === id);
    if (index > -1) {
      notifications.value.splice(index, 1);
    }
  };

  const clearAll = () => {
    notifications.value = [];
  };

  // Convenience methods
  const success = (message: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'success', message, ...options });
  };

  const error = (message: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'error', message, duration: 8000, ...options });
  };

  const warning = (message: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'warning', message, ...options });
  };

  const info = (message: string, options?: Partial<Notification>) => {
    return addNotification({ type: 'info', message, ...options });
  };

  return {
    notifications: readonly(notifications),
    addNotification,
    removeNotification,
    clearAll,
    success,
    error,
    warning,
    info,
  };
}
