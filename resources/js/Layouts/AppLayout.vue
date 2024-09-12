<script setup lang="ts">
import Navbar from '@/Components/Front/Navbar.vue';
import Footnote from '@/Components/Front/Footnote.vue';
import { useDark } from '@vueuse/core';
import ToastList from '@/Components/Backend/ToastList.vue';

const isDark = useDark()
</script>

<template>

  <ToastList />

  <div>

    <Navbar />

    <!-- Page Content -->
    <main>

      <slot />

    </main>

    <Footnote />

  </div>
</template>
