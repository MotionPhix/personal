<script setup lang="ts">

import { Head } from "@inertiajs/vue3"
import { Logo } from "@/types";
import LogoCard from "@/components/front/LogoCard.vue";
import AuthLayout from "@/layouts/AuthLayout.vue";
import Navheader from "@/components/backend/Navheader.vue";
import { IconAlertCircle, IconBookUpload, IconUpload } from "@tabler/icons-vue";
import { ModalLink } from '@inertiaui/modal-vue'

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

      <h2 class="text-xl font-semibold dark:text-gray-300">
        Explore logos
      </h2>

      <span class="flex-1"></span>

      <ModalLink
        as="button"
        preserve-scroll
        v-if="logoFiles.length"
        :href="route('auth.downloads.create')"
        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-800 border border-transparent rounded-lg gap-x-2 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
        <IconUpload class="size-5" /> New
      </ModalLink>

    </nav>

  </Navheader>

  <div class="max-w-2xl px-4 py-8 mx-auto">

    <header>

      <h1
        class="text-3xl text-zinc-800 sm:text-4xl dark:text-zinc-100">
        Uploaded logos
      </h1>

      <p
        class="mt-6 text-lg text-zinc-600 dark:text-zinc-400">
        Upload and manage logos here. Ensure your logos are optimized for various design and
        branding needs, providing scalability and clarity across all media.
      </p>

    </header>

    <div class="mt-16 sm:mt-20">

      <ul
        role="list"
        v-if="logoFiles.length"
        class="grid grid-cols-2 gap-4 sm:grid-cols-4">

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

        <ModalLink
          as="button"
          preserve-scroll
          :href="route('auth.downloads.create')"
          class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white border border-transparent rounded-md bg-lime-600 hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 dark:ring-offset-gray-900">
          <IconBookUpload class="mr-2 size-5" /> Upload Logo
        </ModalLink>
      </div>

    </div>

  </div>

</template>
