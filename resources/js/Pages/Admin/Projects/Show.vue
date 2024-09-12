<script setup lang="ts">

import Navheader from '@/Components/Backend/Navheader.vue';
import { Project } from "@/types";
import {Head, Link} from "@inertiajs/vue3";
import { IconArrowLeft } from "@tabler/icons-vue";
import { computed, ref } from "vue";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { IconDeviceProjector, IconDots, IconPhotoX, IconPencil, IconTableShortcut } from '@tabler/icons-vue';
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import Footnote from "@/Components/Front/Footnote.vue";

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
  layout: AuthLayout
})
</script>

<template>

  <Head :title="project.name" />

  <Navheader>

    <nav
      class="flex items-center w-full gap-6 dark:text-white dark:border-gray-700">
      <!-- Back to Projects Button -->
      <Link
        as="button"
        :href="route('projects.index')"
        class="flex items-center gap-2 text-2xl text-blue-600 transition-all duration-300 ease-in-out transform group dark:text-blue-400 hover:-translate-x-1 hover:text-blue-800 dark:hover:text-blue-600"
      >
        <IconArrowLeft class="size-8 hidden group-hover:inline-block" />

        <IconDeviceProjector class="size-8 inline-block group-hover:hidden" />

        <span class="group-hover:font-bold">All</span>
      </Link>

      <span class="flex-1"></span>

      <Link
        as="button"
        :href="route('auth.projects.create')"
        class="flex text-2xl items-center gap-2 py-0.5 font-semibold transition duration-300 hover:opacity-70">
        <IconPencil class="w-8 stroke-current" /> <span>Edit</span>
      </Link>

    </nav>

  </Navheader>

  <main class="flex-auto mb-10 sm:mb-14">

    <div class="mt-8 sm:px-8 sm:mt-16">

      <div class="w-full max-w-3xl mx-auto lg:px-8">

        <div class="relative px-4 sm:px-8 lg:px-12">

          <div class="max-w-2xl mx-auto lg:max-w-5xl">

            <div
              class="md:h-[calc(100vh-400px)] group my-10 max-w-full h-[30rem] relative flex flex-col w-full min-h-60 bg-center bg-cover rounded-xl hover:shadow-lg focus:outline-none focus:shadow-lg transition"
              :style="{ backgroundImage: `url(${project.poster})` }" />

            <header class="max-w-2xl mb-12">
              <h1
                class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl dark:text-zinc-100"
              >
                {{ project.name }}
              </h1>

              <section class="my-12 text-zinc-800 text-base prose prose-lg dark:text-zinc-100">
                <div v-html="project.description" />
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
                :key="index" class="break-inside-avoid relative group">

                <img
                  :src="image?.src"
                  @contextmenu.prevent
                  :alt="'Project image ' + index + 1"
                  class="w-full rounded-lg shadow-lg">

                <div
                  class="absolute top-1.5 right-1.5">

                  <Menu as="div" class="relative text-left z-40 hidden group-hover:inline-block">
                    <div>
                      <MenuButton
                        class="inline-flex w-full justify-center rounded-md bg-black/20 p-1 text-sm font-medium text-white hover:bg-black/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
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
                        class="absolute right-0 -mt-8 w-24 origin-bottom-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
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
                              :href="route('auth.projects.destroy', image.model_id)"
                            >
                              <IconTableShortcut
                                :active="active"
                                class="mr-2 h-5 w-5 text-violet-400"
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
                              :href="route('auth.projects.destroy', { project: image.model_id, image: image.id })"
                            >
                              <IconPhotoX
                                :active="active"
                                class="mr-2 h-5 w-5 text-violet-400"
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

          </div>

        </div>

      </div>

    </div>

  </main>

  <Footnote />

</template>
