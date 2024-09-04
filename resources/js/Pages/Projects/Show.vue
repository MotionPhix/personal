<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import {Head} from "@inertiajs/vue3";

defineProps({
  project: Object
})

defineOptions({
  layout: AppLayout
})
</script>

<template>

  <Head :title="project.name" />

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
                {{ project.name }}
              </h1>

              <p
                class="mt-6 text-base text-zinc-600 dark:text-zinc-400">
                {{ project.description }}
              </p>

            </header>

            <div class="mt-16 sm:mt-20">

              {{ project }}

            </div>

          </div>

        </div>

      </div>

    </div>

  </main>
</template>
