<script setup lang="ts">

import Navheader from '@/Components/Backend/Navheader.vue';
import { Project } from "@/types";
import {Head, Link} from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { IconDots, IconPhotoX, IconPencil, IconTableShortcut, IconApps, IconArrowLeft } from '@tabler/icons-vue';
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { random } from 'lodash';

const props = defineProps<{
  project: Project,
}>();

defineOptions({
  layout: AuthLayout
})

// Function to find a specific image by ID
// const getImageById = (id: number) => {
//   return props.project.media?.find(media => media.id === id)?.original_url || '';
// };

const getRandomImage = () => {
  const media = props.project.media;
  if (!media || media.length === 0) {
    return ''; // Return an empty string if there are no images
  }
  const randomIndex = Math.floor(Math.random() * media.length);
  return media[randomIndex].original_url;
};
</script>

<template>

  <Head :title="project.name" />

  <Navheader>

    <nav
      class="flex items-center w-full gap-6 dark:text-white dark:border-gray-700">
      <!-- Back to Projects Button -->
      <Link
        as="button"
        :href="route('auth.projects.index')"
        class="flex items-center gap-2 text-2xl text-blue-600 transition-all duration-300 ease-in-out transform group dark:text-blue-400 hover:-translate-x-1 hover:text-blue-800 dark:hover:text-blue-600"
      >
        <IconArrowLeft class="hidden size-8 group-hover:inline-block" />

        <IconApps class="inline-block size-8 group-hover:hidden" />

        <span class="group-hover:font-bold">All</span>
      </Link>

      <span class="flex-1"></span>

      <Link
        as="button"
        :href="route('auth.projects.edit', project.pid)"
        class="flex text-2xl items-center gap-2 py-0.5 font-semibold transition duration-300 hover:opacity-70">
        <IconPencil class="w-8 stroke-current" /> <span>Edit</span>
      </Link>

    </nav>

  </Navheader>

  <article class="mb-10 sm:mb-14 mt-8 sm:px-8 sm:mt-16 w-full mx-auto lg:px-8 relative px-4 max-w-2xl">

    <div
      class="w-full h-full max-w-full my-10 bg-center bg-cover min-h-80 rounded-xl"
      :style="{
        backgroundImage: `url(${ project.media?.length ? project.media[0].original_url : '' })`
      }" />

    <header class="max-w-2xl mb-12">
      <h1
        class="text-4xl font-bold tracking-tight prose sm:text-5xl dark:prose-invert"
      >
        {{ project.name }}
      </h1>

      <section
        class="my-12 prose prose-md dark:prose-invert">
        <div v-html="project.description" />
      </section>

      <div class="grid grid-cols-2 gap-6 mt-4">

        <dl class="flex flex-col gap-2">

          <dt>
            <span class="text-gray-500 dark:text-neutral-500">
              Client
            </span>
          </dt>

          <dd class="pt-2 border-t border-gray-300 dark:border-neutral-500">

            <ul>

              <li class="font-bold text-gray-800 dark:text-neutral-200">
                {{ project?.customer?.first_name + ' ' + project?.customer?.last_name }}
              </li>

              <li class="text-sm text-gray-800 dark:text-neutral-200">
                {{ project?.customer?.company_name }}
              </li>

            </ul>

          </dd>

        </dl>

        <dl class="flex flex-col gap-2">

          <dt>
            <span class="text-gray-500 dark:text-neutral-500">
              Completed
            </span>
          </dt>

          <dd class="pt-2 border-t border-gray-300 dark:border-neutral-500">

            <ul>

              <li class="text-gray-800 dark:text-neutral-200">
                {{ project?.production }}
              </li>

            </ul>

          </dd>

        </dl>

      </div>
    </header>

    <div
      class="gap-4 space-y-4 columns-2 sm:columns-3"
      :style="project.media && project.media?.length > 3 ? 'direction: ltr;' : 'rtl'">

      <div
        v-for="(media, index) in project.media"
        :key="index" class="relative break-inside-avoid group">

        <img
          loading="lazy"
          decoding="async" data-nimg="1"
          :src="media.original_url"
          @contextmenu.prevent
          :alt="'Project image ' + index + 1"
          class="w-full rounded-lg shadow-lg">

        <div
          class="absolute top-1.5 right-1.5">

          <Menu as="div" class="relative z-40 hidden text-left group-hover:inline-block">
            <div>
              <MenuButton
                class="inline-flex justify-center w-full p-1 text-sm font-medium text-white rounded-md bg-black/20 hover:bg-black/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
              >
                <IconDots
                  class="size-6 text-violet-200 hover:text-violet-100"
                  aria-hidden="true"
                />
              </MenuButton>
            </div>

            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <MenuItems
                class="absolute right-0 w-24 -mt-8 origin-bottom-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black/5 focus:outline-none"
                style="direction: ltr"
              >
                <div class="px-1 py-1">
                  <MenuItem v-slot="{ active }">
                    <Link
                      as="button"
                      method="delete"
                      :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route(
                        'auth.projects.destroy',
                        project.pid
                      )"
                    >
                      <IconTableShortcut
                        :active="active"
                        class="w-5 h-5 mr-2 text-violet-400"
                        aria-hidden="true"
                      />
                      Project
                    </Link>
                  </MenuItem>

                  <MenuItem v-slot="{ active }">
                    <Link
                      as="button"
                      method="delete"
                      :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route(
                        'auth.projects.destroy',
                        { project: project.pid, image: media }
                      )"
                    >
                      <IconPhotoX
                        :active="active"
                        class="w-5 h-5 mr-2 text-violet-400"
                        aria-hidden="true"
                      />
                      Image
                    </Link>
                  </MenuItem>
                </div>

              </MenuItems>

            </transition>

          </Menu>
        </div>

      </div>

    </div>

  </article>

</template>
