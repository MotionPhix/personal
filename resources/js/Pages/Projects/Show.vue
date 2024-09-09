<script setup lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";
import { Image, Project } from "@/types";
import {Head, Link} from "@inertiajs/vue3";
import { IconArrowLeft } from "@tabler/icons-vue";
import { computed, onMounted, ref } from "vue";

const props = defineProps<{
  project: Project;
}>();

// // Extract images from all projects
// const allImages = computed(() => {
//   return props.project.images ?? [];
// });

// // Function to determine the grid span based on image size (dynamic span)
// function getGridSpan(image: Image) {

//   const [width, height] = image.size.split('x').map(Number);

//   // Return the number of rows to span based on the image's height
//   return Math.ceil(height / 200);
// }

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

// onMounted(() => {
//   const images = loadImages();
//   masonryItems.value = images?.map(image => {
//     const [width, height] = image.size.split('x').map(Number);
//     return {
//       ...image,
//       aspectRatio: `${width} / ${height}`
//     };
//   });
// });

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
              :style="{ backgroundImage: `url(${project.poster})` }">

              <!-- <div class="w-2/3 pb-5 mt-auto md:max-w-lg ps-5 md:ps-10 md:pb-10">

                <span class="block text-white">
                  Nike React
                </span>

                <span
                  class="block text-xl text-white md:text-3xl">
                  Rewriting sport's playbook for billions of athletes
                </span>

              </div> -->

            </div>

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

            <!-- Project Images -->

            <!-- <div class="grid grid-cols-2 md:grid-cols-3 gap-4 auto-rows-[1fr]">

              <div
                v-for="(image, index) in allImages"
                :key="index"
                class="relative overflow-hidden rounded-lg"
                :style="{ gridRowEnd: `span ${getGridSpan(image)}` }"
              >
                <img
                  :src="image.src"
                  :alt="'Image ' + index"
                  class="object-cover w-full h-full"
                />
              </div>

            </div> -->

            <!-- <div class="grid grid-cols-2 gap-4 mb-8">
              <img
                v-for="image in project.images"
                :key="image.fid"
                :src="image.src"
                alt="Project Image"
                class="object-cover w-full rounded-lg shadow-lg"
              />
            </div> -->

            <!-- <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
              <div v-for="(item, index) in masonryItems" :key="index" class="overflow-hidden rounded-lg shadow-lg">
                <img :src="item.src" :alt="'Image ' + index" class="object-cover w-full" :style="{ aspectRatio: item.aspectRatio }">
              </div>
            </div> -->

            <div class="gap-4 space-y-4 columns-2 sm:columns-3" :style="project?.images?.length > 3 ? 'direction: rtl;' : ''">
              <div v-for="(image, index) in project.images" :key="index" class="break-inside-avoid">
                <img :src="image.src" @contextmenu.prevent :alt="'Project image ' + index + 1" class="w-full rounded-lg shadow-lg">
              </div>
            </div>

          </div>

        </div>

      </div>

    </div>

  </main>


</template>
