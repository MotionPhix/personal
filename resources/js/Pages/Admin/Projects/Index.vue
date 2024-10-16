<script setup lang="ts">

import { Head, Link } from '@inertiajs/vue3';
import Navheader from '@/Components/Backend/Navheader.vue';
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { IconDots, IconPhotoX, IconPencil, IconPlus, IconTrash, IconListDetails } from '@tabler/icons-vue';
import {Project} from "@/types";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import IconContacts from "@/Components/Icon/IconContacts.vue";

defineProps<{
  projects: Project[],
  can?: Object
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
        <IconPlus class="w-8 h-8 stroke-current" /> <span>Add new</span>

      </Link>

      <h2 v-else class="text-xl font-semibold dark:text-gray-300">
        Explore projects
      </h2>

    </nav>

  </Navheader>

  <article
    class="max-w-2xl px-4 py-10 mx-auto sm:px-6 lg:px-8 lg:py-14">

    <!-- Conditional Section if no projects are available -->
    <div
      v-if="!projects.length"
      class="text-center">

      <IconPhotoX
        class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" />

      <h2
        class="mt-4 text-2xl font-semibold text-gray-800 dark:text-neutral-200">
        No Projects Available
      </h2>

      <p
        class="mt-2 text-gray-600 dark:text-neutral-400">
        It seems there are no projects yet. Add new projects to get started!
      </p>

      <Link
        as="button"
        :href="route('auth.projects.create')"
        class="inline-flex items-center gap-2 px-5 py-3 mt-6 text-sm font-medium text-white rounded-md shadow bg-lime-600 hover:bg-lime-500 focus:outline-none focus:ring-2 focus:ring-lime-500 dark:bg-lime-700 dark:hover:bg-lime-600"
      >
        <IconPlus class="size-5" />
        Add Project
      </Link>

    </div>

    <!-- load projects -->
    <div
      class="gap-4 space-y-4 columns-2 sm:columns-3"
      v-else>

      <div
        v-for="(project, idx ) in projects"
        :key="project.pid"
        class="relative break-inside-avoid group">

        <img
          loading="lazy"
          decoding="async"
          :src="project.poster_url as any"
          :alt="'Project ' + idx + 1"
          class="w-full rounded-lg shadow-lg">

        <div
          class="absolute top-1.5 right-1.5">

          <Menu
            as="div"
            class="relative hidden z-10 text-left rounded size-6 items-center bg-neutral-100 dark:bg-neutral-800 group:hover:flex">

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
                class="absolute w-24 mt-24 origin-bottom-right dark:bg-black/90 bg-white divide-y divide-gray-100 rounded-md shadow-lg right-1 ring-1 ring-black/5 focus:outline-none"
              >
                <div class="px-1 py-1">
                  <MenuItem v-slot="{ active }">
                    <Link
                      as="button"
                      :class="[
                        active ? 'bg-lime-500 text-white' : 'text-gray-900 dark:text-lime-500',
                        'flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route('auth.projects.detail', project.pid)"
                    >
                      <IconListDetails
                        :active="active"
                        class="w-5 h-5 mr-2 text-lime-400 group:hover:text-gray-100"
                        aria-hidden="true"
                      />
                      View
                    </Link>
                  </MenuItem>

                  <MenuItem v-slot="{ active }">
                    <Link
                      as="button"
                      :class="[
                        active ? 'bg-lime-500 text-white' : 'text-gray-900 dark:text-lime-500',
                        'flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route('auth.projects.edit', project.pid)"
                    >
                      <IconPencil
                        :active="active"
                        class="w-5 h-5 mr-2 text-lime-400 group:hover:text-gray-100"
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
                        active ? 'bg-lime-500 text-white' : 'text-gray-900 dark:text-lime-500',
                        'flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                      :href="route('auth.projects.destroy',  project.pid)"
                    >
                      <IconTrash
                        :active="active"
                        class="w-5 h-5 mr-2 text-lime-400 group:hover:text-gray-100"
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

        <div class="absolute bottom-0 inset-x-0 z-10">

          <div class="flex flex-col p-2 text-sm">
            <h3 class=" text-white group-hover:text-white/80 group-focus:text-white/80">
              {{ `${project.customer?.first_name} ${project.customer?.last_name}` }}
            </h3>

            <p class="text-xs mt-2 text-white/80">
              {{ project.name }}
            </p>
          </div>

        </div>

      </div>

    </div>


  </article>

</template>
