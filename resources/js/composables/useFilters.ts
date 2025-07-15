import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';

interface FilterOptions {
  preserveState?: boolean;
  preserveScroll?: boolean;
  debounceMs?: number;
}

export function useFilters(initialFilters: Record<string, any> = {}, options: FilterOptions = {}) {
  const filters = ref({ ...initialFilters });
  const isFiltering = ref(false);

  const {
    preserveState = true,
    preserveScroll = true,
    debounceMs = 300,
  } = options;

  // Debounced filter application
  let debounceTimeout: NodeJS.Timeout;

  const applyFilters = () => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
      isFiltering.value = true;

      // Remove empty filters
      const cleanFilters = Object.fromEntries(
        Object.entries(filters.value).filter(([_, value]) => {
          if (value === null || value === undefined || value === '') return false;
          if (Array.isArray(value) && value.length === 0) return false;
          return true;
        })
      );

      router.get(window.location.pathname, cleanFilters, {
        preserveState,
        preserveScroll,
        onFinish: () => {
          isFiltering.value = false;
        },
      });
    }, debounceMs);
  };

  const setFilter = (key: string, value: any) => {
    filters.value[key] = value;
    applyFilters();
  };

  const removeFilter = (key: string) => {
    delete filters.value[key];
    applyFilters();
  };

  const clearFilters = () => {
    filters.value = {};
    applyFilters();
  };

  const toggleFilter = (key: string, value: any) => {
    if (filters.value[key] === value) {
      removeFilter(key);
    } else {
      setFilter(key, value);
    }
  };

  // Array filters (for multi-select)
  const addToArrayFilter = (key: string, value: any) => {
    if (!filters.value[key]) {
      filters.value[key] = [];
    }
    if (!filters.value[key].includes(value)) {
      filters.value[key].push(value);
      applyFilters();
    }
  };

  const removeFromArrayFilter = (key: string, value: any) => {
    if (filters.value[key]) {
      const index = filters.value[key].indexOf(value);
      if (index > -1) {
        filters.value[key].splice(index, 1);
        if (filters.value[key].length === 0) {
          delete filters.value[key];
        }
        applyFilters();
      }
    }
  };

  const toggleArrayFilter = (key: string, value: any) => {
    if (!filters.value[key]) {
      filters.value[key] = [];
    }

    const index = filters.value[key].indexOf(value);
    if (index > -1) {
      filters.value[key].splice(index, 1);
      if (filters.value[key].length === 0) {
        delete filters.value[key];
      }
    } else {
      filters.value[key].push(value);
    }
    applyFilters();
  };

  // Computed properties
  const hasFilters = computed(() => {
    return Object.keys(filters.value).length > 0;
  });

  const activeFiltersCount = computed(() => {
    return Object.values(filters.value).reduce((count, value) => {
      if (Array.isArray(value)) {
        return count + value.length;
      }
      return count + (value ? 1 : 0);
    }, 0);
  });

  // Search specific helpers
  const setSearch = (query: string) => {
    setFilter('search', query);
  };

  const clearSearch = () => {
    removeFilter('search');
  };

  // Sorting helpers
  const setSorting = (field: string, direction: 'asc' | 'desc' = 'asc') => {
    setFilter('sort_by', field);
    setFilter('sort_direction', direction);
  };

  const toggleSorting = (field: string) => {
    if (filters.value.sort_by === field) {
      const newDirection = filters.value.sort_direction === 'asc' ? 'desc' : 'asc';
      setSorting(field, newDirection);
    } else {
      setSorting(field, 'asc');
    }
  };

  // Pagination helpers
  const setPage = (page: number) => {
    setFilter('page', page);
  };

  const setPerPage = (perPage: number) => {
    setFilter('per_page', perPage);
    removeFilter('page'); // Reset to first page when changing per page
  };

  return {
    filters: readonly(filters),
    isFiltering: readonly(isFiltering),
    hasFilters,
    activeFiltersCount,
    setFilter,
    removeFilter,
    clearFilters,
    toggleFilter,
    addToArrayFilter,
    removeFromArrayFilter,
    toggleArrayFilter,
    setSearch,
    clearSearch,
    setSorting,
    toggleSorting,
    setPage,
    setPerPage,
  };
}
