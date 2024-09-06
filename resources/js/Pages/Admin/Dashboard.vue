<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Navheader from "@/Components/Backend/Navheader.vue";
import { IconPlus, IconUser, IconBuildingEstate, IconBalloon } from "@tabler/icons-vue";

defineOptions({
  layout: AuthLayout
})
</script>

<template>
    <Head title="Dashboard" />

    <Navheader>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard
      </h2>

      <div class="hs-dropdown relative inline-flex">

        <button
          id="hs-dropdown-with-title"
          type="button"
          class="hs-dropdown-toggle p-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">

          <IconPlus stroke-width="3" size="16" />

        </button>

        <div
          class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-1 space-y-0.5 mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700"
          role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-title">

          <div class="py-2 first:pt-0 last:pb-0">
            <span class="block py-2 px-3 text-xs font-medium uppercase text-gray-400 dark:text-neutral-500">
              Actions
            </span>

            <Link
              class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
              :href="route('auth.customer.create')">
              <IconUser class="shrink-0 size-4" />
              Add customer
            </Link>

            <Link
              class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
              :href="route('auth.projects.create')">
              <IconBuildingEstate class="shrink-0 size-4" />
              Add project
            </Link>

            <Link
              class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700"
              :href="route('auth.downloads.create')">
              <IconBalloon class="shrink-0 size-4" />
              Add logo
            </Link>
          </div>

        </div>

      </div>

    </Navheader>

    <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                  <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>
              </div>
          </div>
      </div>
</template>
