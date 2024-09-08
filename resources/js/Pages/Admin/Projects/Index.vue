<script setup lang="ts">
import Navheader from '@/Components/Backend/Navheader.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Image, Project } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { IconDeviceProjector } from '@tabler/icons-vue';
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
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[1fr]">

        <!-- Iterate over the chunked images -->
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

      </div>

    </div>

  </main>

</template>
