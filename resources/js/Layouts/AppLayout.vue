<script setup lang="ts">
// import Navbar from '@/Components/Front/Navbar.vue';
import Footnote from '@/Components/Front/Footnote.vue';
import { useDark } from '@vueuse/core';
import ToastList from '@/Components/Backend/ToastList.vue';
import BetterNav from '@/Components/Front/BetterNav.vue';
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

    window.Echo.channel('logos')
      .listen('LogoUploaded', (e) => {
        console.log(e)
      })

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
