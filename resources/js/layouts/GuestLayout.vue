<script setup lang="ts">
import ApplicationLogo from '@/components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { useDark } from '@vueuse/core';

const isDark = useDark()
</script>

<template>
  <div
    class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900">

    <div>

      <Link href="/" as="button">

        <ApplicationLogo class="w-20 h-20 text-gray-500 fill-current" />

      </Link>

    </div>

    <div
      class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-md dark:bg-gray-800 sm:rounded-lg">

      <slot />

    </div>

  </div>
</template>
