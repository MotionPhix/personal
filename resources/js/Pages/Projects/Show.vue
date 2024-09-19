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

  <main class="flex-auto mb-10 sm:mb-14">

    <div class="mt-8 sm:px-8 sm:mt-16">

      <div class="w-full max-w-3xl mx-auto lg:px-8">

        <div class="relative px-4 sm:px-8 lg:px-12">

          <div class="max-w-2xl mx-auto lg:max-w-5xl">
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
              class="group my-10 max-w-full h-[20rem] md:h-[30rem] relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-none focus:shadow-lg transition"
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

                  <dd>

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

                  <dd>

                    <ul>

                      <li class="text-sm text-gray-800 dark:text-neutral-200">
                        {{ project?.production }}
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

          </div>

        </div>

      </div>

    </div>

  </main>


</template>
