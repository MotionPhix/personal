<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { IconMenu4, IconX } from '@tabler/icons-vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>

  <!-- ========== HEADER ========== -->
  <header class="sticky inset-x-0 top-0 z-50 flex flex-wrap w-full text-sm md:justify-start md:flex-nowrap">

    <nav
      class="mt-4 relative max-w-2xl w-full bg-white border border-gray-200 rounded-[2rem] mx-2 py-2.5 md:flex md:items-center md:justify-between md:py-0 md:px-4 md:mx-auto dark:bg-neutral-900 dark:border-neutral-700">
      <div class="flex items-center justify-between px-4 md:px-0">
        <!-- Logo -->
        <div>
          <Link
            class="flex items-center gap-1 text-xl font-semibold rounded-md dark:text-gray-300 focus:outline-none focus:opacity-80" :href="route('home')" aria-label="Ultrashots">

            <ApplicationLogo class="w-7" />

            <span class="text-2xl">ultrashots</span>

          </Link>
        </div>
        <!-- End Logo -->

        <div class="md:hidden">
          <!-- Toggle Button -->
          <button
            type="button"
            class="flex items-center justify-center text-gray-500 border border-gray-200 rounded-full hs-collapse-toggle size-8 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" id="hs-navbar-header-floating-collapse" aria-expanded="false"
            aria-controls="hs-navbar-header-floating"
            aria-label="Toggle navigation"
            data-hs-collapse="#hs-navbar-header-floating">

            <IconMenu4 :size="16" stroke="3" class="hs-collapse-open:hidden shrink-0" />

            <IconX :size="16" stroke="3" class="hidden hs-collapse-open:block shrink-0" />

          </button>
          <!-- End Toggle Button -->
        </div>
      </div>

      <div
        id="hs-navbar-header-floating"
        class="hidden overflow-hidden transition-all duration-300 hs-collapse basis-full grow md:block" aria-labelledby="hs-navbar-header-floating-collapse">
        <div
          class="flex flex-col gap-2 py-2 mt-3 md:flex-row md:items-center md:justify-end md:gap-3 md:mt-0 md:py-0 md:ps-7">
          <Link
            class="py-0.5 md:py-3 px-4 md:px-1 border-s-2 md:border-s-0 md:border-b-2 border-gray-800 font-medium text-gray-800 focus:outline-none dark:border-neutral-200 dark:text-neutral-200"
            :href="route('home')" aria-current="page">
            Home
          </Link>

          <Link
            class="py-0.5 md:py-3 px-4 md:px-1 border-s-2 md:border-s-0 md:border-b-2 border-transparent text-gray-500 hover:text-gray-800 focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-200"
            :href="route('projects.index')">
            Projects
          </Link>

          <a
            class="py-0.5 md:py-3 px-4 md:px-1 border-s-2 md:border-s-0 md:border-b-2 border-transparent text-gray-500 hover:text-gray-800 focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-200"
            href="#">
            Work
          </a>

          <a
            class="py-0.5 md:py-3 px-4 md:px-1 border-s-2 md:border-s-0 md:border-b-2 border-transparent text-gray-500 hover:text-gray-800 focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-200"
            href="#">
            Articles
          </a>
        </div>
      </div>
    </nav>
  </header>
  <!-- ========== END HEADER ========== -->

</template>
