<script setup lang="ts">
import { Project } from '@/types';
import { Link } from '@inertiajs/vue3';

defineProps<{
  projects?: Project[]
}>()
</script>

<template>
  <!-- Projects -->
  <div class="mt-10 sm:mt-14">
    <h2 class="mb-5 font-medium text-gray-800 dark:text-neutral-200">
      Projects
    </h2>

    <!-- Image Grid -->
    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">

      <Link
        class="relative block overflow-hidden rounded-lg group"
        :href="route('projects.show', project.pid)"
        v-for="project in projects"
        :key="project.pid">

        <img
          class="object-cover w-full bg-gray-100 rounded-lg size-40 dark:bg-neutral-800"
          :src="project.poster as string"
          alt="Project poster" />

        <div class="absolute transition opacity-0 bottom-1 end-1 group-hover:opacity-100">

          <div
            class="flex items-center px-2 py-1 text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-1 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">

            <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="11" cy="11" r="8" />
              <path d="m21 21-4.3-4.3" />
            </svg>

            <span class="text-xs">View</span>

          </div>

        </div>

      </Link>

    </div>
    <!-- End Image Grid -->
  </div>
  <!-- End Projects -->
</template>
