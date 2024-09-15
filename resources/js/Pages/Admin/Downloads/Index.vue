<script setup lang="ts">

import { Head, Link } from "@inertiajs/vue3"
import { Logo } from "@/types";
import LogoCard from "@/Components/Front/LogoCard.vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Navheader from "@/Components/Backend/Navheader.vue";
import { IconAlertCircle, IconUpload } from "@tabler/icons-vue";

defineProps<{
  logoFiles: Logo[]
}>()

defineOptions({
  layout: AuthLayout
})

</script>

<template>

  <Head title="Downloads" />

  <Navheader>

    <nav
      class="flex items-center w-full gap-1 mx-auto dark:text-white dark:border-gray-700">

      <h2 class="text-xl font-semibold dark:text-gray-300 sm:inline-block">
        Explore logos
      </h2>

      <span class="flex-1"></span>

      <Link
        as="button"
        :href="route('auth.downloads.create')"
        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 border border-transparent rounded-lg gap-x-2 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
        <IconUpload class="size-5" /> New
      </Link>

    </nav>

  </Navheader>

  <main
    class="flex-auto mb-10 sm:mb-14">

    <div class="mt-16 sm:px-8 sm:mt-16">

      <div
        class="w-full max-w-3xl mx-auto lg:px-8">

        <div class="relative px-4 sm:px-8 lg:px-12">

          <div class="max-w-2xl mx-auto lg:max-w-5xl">

            <header class="max-w-2xl">

              <h1
                class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100">
                Available logos
              </h1>

              <p
                class="mt-6 text-lg text-zinc-600 dark:text-zinc-400">
                Explore a collection of high-quality, vectorized logos from top companies and organisations across Malawi. These logos are optimized for various design and branding needs, ensuring scalability
                and clarity across all media.
              </p>

            </header>

            <div class="mt-16 sm:mt-20">

              <ul
                role="list"
                v-if="logoFiles.length"
                class="grid grid-cols-3 gap-2 lg:grid-cols-4">

                <li
                  class="relative flex flex-col items-start group"
                  v-for="logo in logoFiles" :key="logo.lid">

                  <LogoCard :logo-file="logo" />

                </li>

              </ul>

              <!-- Display this section if no logos are available -->
              <div v-else class="flex flex-col items-center justify-center mt-10 space-y-4 text-center text-zinc-600 dark:text-zinc-400">

                <IconAlertCircle class="text-gray-400 size-16 dark:text-gray-500" />

                <p class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                  No logos available at the moment
                </p>

                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                  Please check back later or click the button below to upload a new logo.
                </p>

                <Link
                  as="button"
                  :href="route('auth.downloads.create')"
                  class="inline-flex items-center px-4 py-2 mt-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:ring-offset-gray-900">
                  <IconUpload class="mr-2 size-5" /> Upload New Logo
                </Link>
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </main>

</template>
