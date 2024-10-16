<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import { Project } from "@/types";
import {Head, Link} from "@inertiajs/vue3";
import { IconArrowLeft } from "@tabler/icons-vue";

const props = defineProps<{
  project: Project;
}>();

defineOptions({
  layout: AppLayout
})

// Function to find a specific image by ID
const getImageById = (id: number) => {
  return props.project.media?.find(media => media.id === id)?.original_url || '';
};
</script>

<template>

  <Head :title="project.name" preserve-scroll preserve-state />

  <article class="w-full max-w-2xl px-4 mx-auto mt-8 mb-10 sm:mb-14 sm:px-8 sm:mt-16 lg:px-8">

    <!-- Back to Projects Button -->
    <Link
      as="button"
      :href="route('projects.index')"
      class="flex items-center gap-2 text-base text-blue-600 transition-all duration-300 ease-in-out transform group dark:text-blue-400 hover:-translate-x-1 hover:text-blue-800 dark:hover:text-blue-600"
    >
      <IconArrowLeft size="24" class="hidden group-hover:inline-block" />

      <span class="group-hover:font-bold">All</span>
    </Link>

    <div
      class="w-full h-full max-w-full my-10 bg-center bg-cover min-h-80 rounded-xl"
      :style="{ backgroundImage: `url(${ project.media?.length ? project?.media[0]?.original_url : '' })` }" />

    <header class="max-w-2xl mb-12">
      <h1
        class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100"
      >
        {{ project.name }}
      </h1>

      <section class="my-12 text-base prose sm:prose-md dark:prose-invert">
        <div v-html="project.description" />
      </section>

      <div class="grid grid-cols-2 gap-6 mt-4">

        <dl class="flex flex-col gap-2">

          <dt>
            <span class="text-sm text-gray-500 dark:text-neutral-500">
              Client
            </span>
          </dt>

          <dd class="pt-2 border-t border-gray-300 dark:border-neutral-500">

            <ul>

              <li class="text-sm font-bold text-gray-800 dark:text-neutral-200">
                {{ project?.customer?.first_name + ' ' + project?.customer?.last_name }}
              </li>

              <li class="text-xs text-gray-800 dark:text-neutral-200">
                {{ project?.customer?.company_name }}
              </li>

            </ul>

          </dd>

        </dl>

        <dl class="flex flex-col gap-2">

          <dt>
            <span class="text-sm text-gray-500 dark:text-neutral-500">
              Completed
            </span>
          </dt>

          <dd class="pt-2 border-t border-gray-300 dark:border-neutral-500">

            <ul>

              <li class="text-sm text-gray-800 dark:text-neutral-200">
                {{ project?.completion_date }}
              </li>

            </ul>

          </dd>

        </dl>

      </div>
    </header>

    <div
      class="gap-4 space-y-4 columns-2 sm:columns-3"
      :style="project?.media && project.media.length > 3 ? 'direction: ltr;' : 'rtl'">
      <div
        v-for="(image, index) in project?.media"
        :key="index" class="break-inside-avoid">
        <img
          loading="lazy"
          decoding="async" data-nimg="1"
          :src="image?.original_url"
          @contextmenu.prevent
          :alt="'Project image ' + index + 1"
          class="w-full rounded-lg shadow-lg">
      </div>
    </div>

  </article>


</template>
