<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import { Image, Project } from "@/types";
import {Head, Link} from "@inertiajs/vue3";
import { IconArrowLeft } from "@tabler/icons-vue";
import { computed, onMounted, ref } from "vue";

const props = defineProps<{
  project: Project;
}>();

const masonryItems = ref([]);

const loadImages = () => {
  return props.project.images;
};

const processedImages = computed(() => {
  return props.project.images?.map(image => {
    const [width, height] = image.size.split('x').map(Number);
    return {
      ...image,
      aspectRatio: `${width} / ${height}`
    };
  });
});

defineOptions({
  layout: AppLayout
})
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

              <span class="group-hover:font-bold">Projects</span>
            </Link>

            <div
              class="md:h-[calc(100vh-400px)] group my-10 max-w-full h-[30rem] relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-none focus:shadow-lg transition"
              :style="{ backgroundImage: `url(${project.poster})` }" />

            <header class="max-w-2xl mb-12">
              <h1
                class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100"
              >
                {{ project.name }}
              </h1>

              <section class="my-12 text-base text-zinc-600 dark:text-zinc-400">
                <div v-html="project.description" class="prose prose-invert"></div>
              </section>

              <div class="mt-4">

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

                <dl class="flex flex-col gap-2 mt-6">

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
              :style="project?.images && project.images.length > 3 ? 'direction: rtl;' : ''">
              <div
                v-for="(image, index) in project?.images"
                :key="index" class="break-inside-avoid">
                <img
                  :src="image?.src"
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
