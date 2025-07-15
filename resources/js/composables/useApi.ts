import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

interface ApiState {
  loading: boolean;
  error: string | null;
  data: any;
}

interface ApiOptions {
  method?: 'GET' | 'POST' | 'PUT' | 'DELETE' | 'PATCH';
  data?: any;
  headers?: Record<string, string>;
  onSuccess?: (data: any) => void;
  onError?: (error: any) => void;
  preserveState?: boolean;
  preserveScroll?: boolean;
  replace?: boolean;
}

export function useApi() {
  const state = reactive<ApiState>({
    loading: false,
    error: null,
    data: null,
  });

  const request = async (url: string, options: ApiOptions = {}) => {
    state.loading = true;
    state.error = null;

    try {
      const response = await fetch(url, {
        method: options.method || 'GET',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
          ...options.headers,
        },
        body: options.data ? JSON.stringify(options.data) : undefined,
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();
      state.data = data;

      if (options.onSuccess) {
        options.onSuccess(data);
      }

      return data;
    } catch (error) {
      const errorMessage = error instanceof Error ? error.message : 'An error occurred';
      state.error = errorMessage;

      if (options.onError) {
        options.onError(error);
      }

      throw error;
    } finally {
      state.loading = false;
    }
  };

  const get = (url: string, options: Omit<ApiOptions, 'method' | 'data'> = {}) => {
    return request(url, { ...options, method: 'GET' });
  };

  const post = (url: string, data?: any, options: Omit<ApiOptions, 'method' | 'data'> = {}) => {
    return request(url, { ...options, method: 'POST', data });
  };

  const put = (url: string, data?: any, options: Omit<ApiOptions, 'method' | 'data'> = {}) => {
    return request(url, { ...options, method: 'PUT', data });
  };

  const patch = (url: string, data?: any, options: Omit<ApiOptions, 'method' | 'data'> = {}) => {
    return request(url, { ...options, method: 'PATCH', data });
  };

  const del = (url: string, options: Omit<ApiOptions, 'method' | 'data'> = {}) => {
    return request(url, { ...options, method: 'DELETE' });
  };

  // Inertia-specific methods
  const visit = (url: string, options: any = {}) => {
    state.loading = true;

    return router.visit(url, {
      preserveState: true,
      preserveScroll: true,
      onStart: () => {
        state.loading = true;
        state.error = null;
      },
      onSuccess: (page) => {
        state.loading = false;
        if (options.onSuccess) {
          options.onSuccess(page);
        }
      },
      onError: (errors) => {
        state.loading = false;
        state.error = Object.values(errors)[0] as string;
        if (options.onError) {
          options.onError(errors);
        }
      },
      ...options,
    });
  };

  const reload = (options: any = {}) => {
    return router.reload({
      preserveState: true,
      preserveScroll: true,
      onStart: () => {
        state.loading = true;
        state.error = null;
      },
      onFinish: () => {
        state.loading = false;
      },
      ...options,
    });
  };

  const reset = () => {
    state.loading = false;
    state.error = null;
    state.data = null;
  };

  return {
    state: readonly(state),
    request,
    get,
    post,
    put,
    patch,
    delete: del,
    visit,
    reload,
    reset,
    loading: computed(() => state.loading),
    error: computed(() => state.error),
    data: computed(() => state.data),
  };
}
