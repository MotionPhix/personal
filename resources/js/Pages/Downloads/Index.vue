<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3"
import { Logo } from "@/types";
import LogoCard from "@/Components/Front/LogoCard.vue";
import { IconAlertCircle } from "@tabler/icons-vue";

defineProps<{
  logoFiles: Logo[]
}>()

defineOptions({
  layout: AppLayout
})

</script>

<template>

  <Head title="Polished up logos" />

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
                Download vectorized logos of leading companies in Malawi
              </h1>

              <p
                class="mt-6 text-lg text-zinc-600 dark:text-zinc-400">
                Explore my collection of high-quality, vectorized logos from top companies and organisations
                across Malawi. These logos are optimized for various design and branding needs, ensuring scalability
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
              <div
                v-else
                class="flex flex-col items-center justify-center mt-10 space-y-4 text-center text-zinc-600 dark:text-zinc-400">

                <IconAlertCircle class="text-gray-400 size-16 dark:text-gray-500" />

                <p class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">
                  No logos available at the moment
                </p>

                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                  Please check back later.
                </p>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </main>

</template>
