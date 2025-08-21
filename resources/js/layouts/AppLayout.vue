<script setup lang="ts">
import Footnote from '@/components/front/Footnote.vue';
import { useDark } from '@vueuse/core';
import ToastList from '@/components/backend/ToastList.vue';
import ResponsiveNav from '@/components/front/ResponsiveNav.vue';

const isDark = useDark()
</script>

<template>
  <ToastList />

  <div class="min-h-screen bg-background">
    <ResponsiveNav />

    <!-- Page Content -->
    <main>
      <slot />
    </main>

    <Footnote />
  </div>
</template>
