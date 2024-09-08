<script setup lang="ts">
import Navheader from '@/Components/Backend/Navheader.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Image, Project } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { IconCapProjecting, IconDeviceProjector, IconDots, IconImageInPicture, IconPencil, IconTrash } from '@tabler/icons-vue';
import { computed } from 'vue';

const props = defineProps<{
  projects: Project[]
}>()

// Extract images from all projects
const allImages = computed(() => {
  return props.projects.flatMap(project => project.images ?? []);
});

// Function to determine the grid span based on image size (dynamic span)
function getGridSpan(image: Image) {

  const [width, height] = image.size.split('x').map(Number);

  // Return the number of rows to span based on the image's height
  return Math.ceil(height / 200);
}

const deleteImage = (id: Number) => {
  console.log("Delete image with ID:", id);
  // Implement the delete functionality
};

const editProject = (projectId: Number) => {
  console.log("Edit project with ID:", projectId);
  // Implement the edit functionality
};

defineOptions({
  layout: AuthLayout
})
</script>

<template>

  <Head title="Available Projects" />

  <Navheader>

    <nav
      class="flex items-center w-full gap-6 dark:text-white dark:border-gray-700">

      <Link
        as="button"
        v-if="projects.length"
        :href="route('auth.projects.create')"
        class="flex items-center gap-2 py-2 font-semibold transition duration-300 hover:opacity-70">
        <IconDeviceProjector class="w-5 h-5 stroke-current" /> <span>Add new</span>
      </Link>

      <h2 v-else class="text-xl font-semibold">
        Explore projects
      </h2>

    </nav>

  </Navheader>

  <main class="max-w-2xl px-4 py-10 mx-auto sm:px-6 lg:px-8 lg:py-14">

    <div class="w-full">

      <!-- Grid container with auto-rows for masonry layout -->
      <!-- <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[1fr]">

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

      <div class="gap-4 space-y-4 columns-2 sm:columns-3" :style="allImages.length > 3 ? 'direction: rtl;' : ''">

        <div
          v-for="(image, index) in allImages" :key="index"
          class="relative break-inside-avoid group">

          <img
            :src="image.src"
            @contextmenu.prevent
            :alt="'Project image ' + index + 1"
            class="w-full rounded-lg shadow-lg">

          <!-- Action Buttons (shown on hover) -->
          <div
            class="absolute flex items-center transition-opacity duration-300 opacity-0 gap-x-1 bottom-1 right-1 group-hover:opacity-100" style="direction: ltr;">

            <!-- Edit Button -->
            <Link
              as="button"
              preserve-scroll
              :href="route('auth.projects.edit', image.model_id)"
              class="p-1 text-blue-500 bg-gray-200 rounded-lg hover:text-blue-600"
            >
              <IconPencil size="24" />
            </Link>

            <div class="relative inline-flex hs-dropdown">
              <button
                id="hs-dropdown-with-icons"
                type="button"
                class="inline-flex items-center p-1 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg shadow-sm hs-dropdown-toggle gap-x-2 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                <IconDots size="24" />
                <!-- <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg> -->
              </button>

              <div
                class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-20 bg-white shadow-md rounded-lg p-1 space-y-0.5 mt-2 divide-y divide-gray-200 dark:bg-neutral-800 dark:border dark:border-neutral-700 z-50 dark:divide-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-icons">

                <div class="py-2 first:pt-0 last:pb-0">

                  <Link
                    as="button"
                    method="delete"
                    preserve-scroll
                    :href="route('auth.projects.destroy', { project: image.model_id })"
                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">

                    <IconCapProjecting class="shrink-0 size-5" />
                    Project

                  </Link>

                  <Link
                    as="button"
                    method="delete"
                    preserve-scroll
                    :href="route('auth.projects.destroy', { project: image.model_id, image: image.id})"
                    class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                    <IconImageInPicture class="shrink-0 size-5" />
                    Image
                  </Link>

                </div>

              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </main>

</template>
