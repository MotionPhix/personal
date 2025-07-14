<script setup lang="ts">
// import Navbar from '@/components/front/Navbar.vue';
import Footnote from '@/components/front/Footnote.vue';
import { useDark } from '@vueuse/core';
import ToastList from '@/components/backend/ToastList.vue';
import BetterNav from '@/components/front/BetterNav.vue';
import { onMounted } from 'vue'
import { type IStaticMethods } from "preline/preline";

declare global {
  interface Window {
    HSStaticMethods: IStaticMethods;
  }
}

const isDark = useDark()

onMounted(() => {

  setTimeout(() => {

    window.HSStaticMethods.autoInit();

  }, 100)

})
</script>

<template>

  <ToastList />

  <div>

    <BetterNav />

    <!-- Page Content -->
    <main>

      <slot />

    </main>

    <Footnote />

  </div>
</template>
