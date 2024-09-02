<script setup lang="ts">
import Navbar from '@/Components/Front/Navbar.vue';
import Footnote from '@/Components/Front/Footnote.vue';
</script>

<template>
  <div>

    <Navbar />

    <!-- Page Content -->
    <main>

      <slot />

    </main>

    <Footnote />

  </div>
</template>
