<script setup lang="ts">

import { Head, Link } from '@inertiajs/vue3';
import Navheader from '@/Components/Backend/Navheader.vue';
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { IconDeviceProjector, IconDots, IconPhotoX, IconPencil } from '@tabler/icons-vue';
import {Project} from "@/types";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import IconContacts from "@/Components/Icon/IconContacts.vue";

defineProps<{
  projects: Project[],
}>()

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
        class="flex items-center gap-2 py-1 text-xl font-semibold transition duration-300 hover:opacity-70 dark:text-gray-300"
      >
        <IconDeviceProjector class="w-8 h-8 stroke-current" /> <span>Add new</span>

      </Link>

      <h2 v-else class="text-xl font-semibold py-1.5">
        Explore projects
      </h2>

    </nav>

  </Navheader>

  <article class="max-w-2xl px-4 py-10 mx-auto sm:px-6 lg:px-8 lg:py-14">

    <!-- Conditional Section if no projects are available -->
    <div v-if="!projects.length" class="text-center">
      <IconPhotoX class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" />
      <h2 class="mt-4 text-2xl font-semibold text-gray-800 dark:text-neutral-200">No Projects Available</h2>
      <p class="mt-2 text-gray-600 dark:text-neutral-400">It seems there are no projects yet. Add new projects to get started!</p>
      <Link
        as="button"
        :href="route('auth.projects.create')"
        class="inline-flex items-center px-5 py-3 mt-6 text-sm font-medium text-white bg-indigo-600 rounded-md shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-600"
      >
        <IconDeviceProjector class="w-5 h-5 mr-2" />
        Add a Project
      </Link>
    </div>

    <!-- Grid -->
    <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2">

      <!-- Card -->
      <section
        class="flex flex-col h-full py-2 transition duration-300 border border-gray-200 group hover:border-transparent hover:shadow-lg focus:outline-none focus:border-transparent focus:shadow-lg rounded-xl dark:border-neutral-700 dark:hover:border-transparent dark:hover:shadow-black/40 dark:focus:border-transparent dark:focus:shadow-black/40"
        v-for="project in projects"
        :key="project.pid"
      >
        <div class="flex items-center px-2 pb-4 mt-auto gap-x-3">

          <div class="p-2 bg-gray-300 rounded-full">
            <IconContacts
              class="size-5" />
          </div>

          <div>
            <h5 class="text-sm text-gray-800 dark:text-neutral-200">
              {{ `${project.customer?.first_name} ${project.customer?.last_name}` }}
            </h5>
          </div>

          <div class="flex-1"></div>

          <Menu
            as="div"
            class="relative z-40 text-left rounded-full">

            <MenuButton
              class="inline-flex justify-center w-full"
            >
              <IconDots
                class="text-gray-700 size-5 dark:text-gray-300"
                aria-hidden="true"
              />
            </MenuButton>

            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-in"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <MenuItems
                class="absolute w-24 -mt-1 origin-bottom-right bg-white divide-y divide-gray-100 rounded-md shadow-lg -right-2 ring-1 ring-black/5 focus:outline-none"
              >
                <div class="px-1 py-1">
                  <MenuItem v-slot="{ active }">
                    <Link
                      as="button"
                      :class="[
                        active ? 'bg-violet-500 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route('auth.projects.edit', project.pid)"
                    >
                      <IconPencil
                        :active="active"
                        class="w-5 h-5 mr-2 text-violet-400"
                        aria-hidden="true"
                      />
                      Edit
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
                      :href="route('auth.projects.destroy', { project: project.id })"
                    >
                      <IconPhotoX
                        :active="active"
                        class="w-5 h-5 mr-2 text-violet-400"
                        aria-hidden="true"
                      />
                      Delete
                    </Link>
                  </MenuItem>
                </div>

              </MenuItems>

            </transition>

          </Menu>

        </div>

        <span class="flex-1 block" />

        <Link
          class="aspect-w-16 aspect-h-11"
          :href="route('auth.projects.detail', project.pid)">
          <img
            class="object-cover w-full rounded-xl"
            :src="project.poster_url as any" alt="Project Image">
        </Link>

        <Link
          class="px-2 pt-2"
          :href="route('auth.projects.detail', project.pid)">
          <p class="leading-tight text-gray-600 dark:text-neutral-400">
            {{ project.name }}
          </p>
        </Link>

      </section>
      <!-- End Card -->

    </div>
    <!-- End Grid -->

  </article>

</template>
