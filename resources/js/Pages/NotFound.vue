<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useDark } from '@vueuse/core';

const isDark = useDark()

defineOptions({
  layout: GuestLayout
})
</script>

<template>

  <Head title="404 Error" />


  <div class="max-w-[50rem] flex flex-col mx-auto size-full">

  <!-- ========== MAIN CONTENT ========== -->
  <main id="content">
    <div class="px-4 py-10 text-center sm:px-6 lg:px-8">

      <h1
        class="block font-bold text-gray-800 text-7xl sm:text-9xl dark:text-white">404</h1>

      <div
        class="mt-3 text-gray-600 dark:text-neutral-400">
        Oops, something went wrong.
      </div>

      <div
        class="text-gray-600 dark:text-neutral-400">
        We couldn't find your page.
      </div>

      <div
        class="flex flex-col items-center justify-center gap-2 mt-5 sm:flex-row sm:gap-3">

        <a class="inline-flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg sm:w-auto gap-x-2 hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="../examples.html">
          <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
          Back to examples
        </a>
      </div>
    </div>
  </main>
  <!-- ========== END MAIN CONTENT ========== -->

  <!-- ========== FOOTER ========== -->
  <footer class="py-5 mt-auto text-center">
    <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
      <p class="text-sm text-gray-500 dark:text-neutral-500">© All Rights Reserved. {{ new Date().getFullYear() }}.</p>
    </div>
  </footer>
  <!-- ========== END FOOTER ========== -->
</div>


</template>

<script>
export default {
  name: 'NotFound',
};
</script>

<style scoped>
.not-found {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
  /* background-color: #f8f9fa; */
  text-align: center;
}

.not-found-content {
  max-width: 400px;
  margin: 0 auto;
}

h1 {
  font-size: 6rem;
  margin: 0;
  color: #dc3545;
}

h2 {
  font-size: 2rem;
  margin: 1rem 0;
}

p {
  margin-bottom: 2rem;
}

.btn {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  text-decoration: none;
  border-radius: 5px;
}

.btn:hover {
  background-color: #0056b3;
}
</style>
